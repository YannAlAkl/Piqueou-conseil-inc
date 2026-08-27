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
    public function index()
    {
        $newsletters = Newsletter::orderByDesc('created_at')->paginate(10);

        return view('admin.newsletter.index', compact('newsletters'));
    }

    public function edit($id)
    {
        $newsletter = Newsletter::findOrFail($id);

        return view('admin.newsletter.edit', compact('newsletter'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'category' => 'required|in:cmmc,loi25,iso27001',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('newsletters', 'public');
        } else {
            unset($validatedData['image']);
        }
        
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->update($validatedData);

        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->delete();

        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter supprimée avec succès.');
    }

    public function retrieve()
    {
        $total = $this->retrieveArticles();

        return redirect()->route('admin.newsletter.index')->with('success', $total . ' nouvel(s) article(s) récupéré(s).');
    }

    public function retrieveArticles()
    {
        $sources = [
            'cmmc' => 'https://news.google.com/rss/search?q=CMMC+cybersecurity&hl=fr&gl=CA&ceid=CA:fr',
            'loi25' => 'https://news.google.com/rss/search?q=%22Loi+25%22+Quebec+vie+privee&hl=fr&gl=CA&ceid=CA:fr',
            'iso27001' => 'https://news.google.com/rss/search?q=ISO+27001&hl=fr&gl=CA&ceid=CA:fr',
        ];

        $totalCount = 0;

        foreach ($sources as $categorie => $url) {

            try {
                $response = Http::get($url);
            } catch (\Exception $e) {
                Log::error('Source injoignable : ' . $categorie . ' - ' . $e->getMessage());
                continue;
            }

            $xml = simplexml_load_string($response->body());
            $compteur = 0;

            foreach ($xml->channel->item as $item) {

                if ($compteur >= 3) {
                    break;
                }

                $lien = (string) $item->link;

                if (Newsletter::where('source_url', $lien)->exists()) {
                    continue;
                }

                Newsletter::create([
                    'title' => (string) $item->title,
                    'category' => $categorie,
                    'content' => trim(strip_tags((string) $item->description)),
                    'source_url' => $lien,
                    'status' => 'draft',
                ]);

                $compteur++;
                $totalCount++;
            }
        }

        return $totalCount;
    }

    public function sendNewsletter()
    {
        $total = $this->sendToSubscribers();

        return redirect()->route('admin.newsletter.index')->with('success', $total . ' newsletter(s) envoyée(s) aux abonnés.');
    }

    public function sendToSubscribers()
    {
        $categories = ['cmmc', 'loi25', 'iso27001'];
        $total = 0;

        foreach ($categories as $categorie) {

            $newsletter = Newsletter::where('category', $categorie)
                ->where('status', 'published')
                ->whereNull('sent_at')
                ->orderBy('created_at')
                ->first();

            if (! $newsletter) {
                continue;
            }

            $clients = User::where('wants_newsletter', true)
                ->where('account_status', 'active')
                ->where('newsletter_category', $categorie)
                ->whereNotNull('email_verified_at')
                ->whereHas('roles', function ($q) { return $q->where('name', 'client'); })
                ->get();

            foreach ($clients as $client) {

                try {
                    Mail::to($client->email)->send(new NewsletterMail($newsletter, $client));
                    $total++;
                } catch (\Exception $e) {
                    Log::error('Echec de l\'envoi de la newsletter vers ' . $client->email . ' - ' . $e->getMessage());
                }
            }

            $newsletter->sent_at = now();
            $newsletter->save();
        }

        return $total;
    }
}
