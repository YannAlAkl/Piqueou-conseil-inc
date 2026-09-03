<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    // Liste paginée des newsletters, triée par date de création décroissante
    public function index()
    {
        // Récupère les newsletters avec une pagination de 10 par page, triées par date de création décroissante
        $newsletters = Newsletter::orderByDesc('created_at')->paginate(10);

        return view('admin.newsletter.index', compact('newsletters'));
    }

    // Affiche le formulaire de création d'une nouvelle newsletter
    public function edit($id)
    {
        $newsletter = Newsletter::findOrFail($id);

        return view('admin.newsletter.edit', compact('newsletter'));
    }

    // Met à jour une newsletter existante avec les données soumises depuis le formulaire d'édition
    public function update(Request $request, $id)
    {
        // Valide les données soumises pour la mise à jour de la newsletter
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'category' => 'required|in:cmmc,loi25,iso27001',
            'image' => 'nullable|image|max:2048',
        ]);

        // Si une image est fournie, elle est stockée et son chemin est ajouté aux données validées
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('newsletters', 'public');
        } else {
            // Si aucune image n'est fournie, on s'assure que la clé 'image' n'est pas présente dans les données validées
            unset($validatedData['image']);
        }
        // Récupère la newsletter existante et met à jour ses données avec les données validées
        $newsletter = Newsletter::findOrFail($id);
        // Met à jour la newsletter avec les données validées
        $newsletter->update($validatedData);

        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter mise à jour avec succès.');
    }

    // Supprime une newsletter existante
    public function destroy($id)
    {
        // Récupère la newsletter existante et la supprime
        $newsletter = Newsletter::findOrFail($id);
        // Supprime la newsletter de la base de données
        $newsletter->delete();

        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter supprimée avec succès.');
    }

    // Récupère les articles depuis les sources RSS et les enregistre dans la base de données
    public function retrieve()
    {
        // Appelle la méthode retrieveArticles pour récupérer les articles depuis les sources RSS et obtenir le nombre total d'articles récupérés
        $total = $this->retrieveArticles();

        return redirect()->route('admin.newsletter.index')->with('success', $total . ' nouvel(s) article(s) récupéré(s).');
    }

    // Récupère les articles depuis les sources RSS et les enregistre dans la base de données
    public function retrieveArticles()
    {
        $sources = [
            'cmmc' => 'https://news.google.com/rss/search?q=CMMC+cybersecurity&hl=fr&gl=CA&ceid=CA:fr',
            'loi25' => 'https://news.google.com/rss/search?q=%22Loi+25%22+Quebec+vie+privee&hl=fr&gl=CA&ceid=CA:fr',
            'iso27001' => 'https://news.google.com/rss/search?q=ISO+27001&hl=fr&gl=CA&ceid=CA:fr',
        ];

        // Initialise un compteur pour le nombre total d'articles récupérés
        $totalCount = 0;

        // Parcourt chaque source RSS pour récupérer les articles
        foreach ($sources as $categorie => $url) {
            // Tente de récupérer le flux RSS depuis l'URL de la source
            try {
                $response = Http::get($url);
            } catch (\Exception $e) {
                // Si la source est injoignable, on log l'erreur et on continue avec la prochaine source
                Log::error('Source injoignable : ' . $categorie . ' - ' . $e->getMessage());
                continue;
            }
            // Si la réponse n'est pas réussie, on log l'erreur et on continue avec la prochaine source
            $xml = simplexml_load_string($response->body());
            $compteur = 0;
            // Parcourt chaque article dans le flux RSS
            foreach ($xml->channel->item as $item) {

                // Limite le nombre d'articles récupérés à 3 par source pour éviter de surcharger la base de données
                if ($compteur >= 3) {
                    break;
                }
                // Vérifie si l'article existe déjà dans la base de données en comparant l'URL source
                $lien = (string) $item->link;
                // Si l'article existe déjà, on passe au suivant
                if (Newsletter::where('source_url', $lien)->exists()) {
                    continue;
                }
                // Crée un nouvel enregistrement de newsletter avec les données de l'article récupéré
                Newsletter::create([
                    'title' => (string) $item->title,
                    'category' => $categorie,
                    'content' => trim(strip_tags((string) $item->description)),
                    'source_url' => $lien,
                    'status' => 'draft',
                ]);
                // Incrémente le compteur pour le nombre d'articles récupérés pour cette source
                $compteur++;
                $totalCount++;
            }
        }
        // Retourne le nombre total d'articles récupérés depuis toutes les sources
        return $totalCount;
    }

    // Envoie les newsletters aux abonnés en fonction de leur catégorie et de leur statut
    public function sendNewsletter()
    {
        // Appelle la méthode sendToSubscribers pour envoyer les newsletters aux abonnés et obtenir le nombre total de newsletters envoyées
        $total = $this->sendToSubscribers();
        // Redirige vers la liste des newsletters avec un message de succès indiquant le nombre total de newsletters envoyées
        return redirect()->route('admin.newsletter.index')->with('success', $total . ' newsletter(s) envoyée(s) aux abonnés.');
    }

    // Envoie les newsletters aux abonnés en fonction de leur catégorie et de leur statut
    public function sendToSubscribers()
    {
        // Définit les catégories de newsletters à traiter
        $categories = ['cmmc', 'loi25', 'iso27001'];
        // Initialise un compteur pour le nombre total de newsletters envoyées
        $total = 0;
        // Parcourt chaque catégorie pour envoyer les newsletters aux abonnés correspondants
        foreach ($categories as $categorie) {

            // Récupère la première newsletter publiée et non encore envoyée pour la catégorie en cours
            $newsletter = Newsletter::where('category', $categorie)
            // Filtre les newsletters dont le statut est "published"
                ->where('status', 'published')
                // Filtre les newsletters qui n'ont pas encore été envoyées (sent_at est null)
                ->whereNull('sent_at')
                // Trie les newsletters par date de création pour obtenir la plus récente
                ->orderBy('created_at')
                // Récupère la première newsletter correspondant aux critères
                ->first();
            // Si aucune newsletter n'est trouvée pour la catégorie en cours, on passe à la catégorie suivante
            if (! $newsletter) {
                continue;
            }
            // Récupère les clients abonnés à la newsletter pour la catégorie en cours, qui ont un compte actif et dont l'email est vérifié
            $clients = User::where('wants_newsletter', true)
                // Filtre les clients dont le statut de compte est "active"
                ->where('account_status', 'active')
                // Filtre les clients qui ont choisi la catégorie de newsletter en cours
                ->where('newsletter_category', $categorie)
                // Filtre les clients dont l'email est vérifié (email_verified_at n'est pas null)
                ->whereNotNull('email_verified_at')
                // Filtre les clients qui ont le rôle "client" pour s'assurer qu'on envoie la newsletter uniquement aux clients
                ->whereHas('roles', function ($q) { return $q->where('name', 'client'); })
                ->get();
            // Parcourt chaque client abonné pour envoyer la newsletter
            foreach ($clients as $client) {

                try {
                    // Envoie la newsletter par email au client abonné
                    Mail::to($client->email)->send(new NewsletterMail($newsletter, $client));
                    // Incrémente le compteur pour le nombre total de newsletters envoyées
                    $total++;
                } catch (\Exception $e) {
                    // Si l'envoi de l'email échoue, on log l'erreur pour le suivi et on continue avec le client suivant
                    Log::error('Echec de l\'envoi de la newsletter vers ' . $client->email . ' - ' . $e->getMessage());
                }
            }
            // Marque la newsletter comme envoyée en enregistrant la date d'envoi
            $newsletter->sent_at = now();
            // Enregistre les modifications dans la base de données pour la newsletter envoyée
            $newsletter->save();
        }
        // Retourne le nombre total de newsletters envoyées aux abonnés
        return $total;
    }
}
