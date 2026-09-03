<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class clientController extends Controller
{
    // Liste paginée des utilisateurs ayant le rôle "client", triée par date de création décroissante
    public function index()
    {
        // Récupère les utilisateurs ayant le rôle "client" avec une pagination de 10 par page
        $clients = User::whereHas('roles', function ($q) { return $q->where('name', 'client'); })
            ->latest()
            ->paginate(10);

        return view('admin.client.index', compact('clients'));
    }

    // Affiche le formulaire de création d'un nouveau client
    public function create()
    {
        return view('admin.client.create');
    }
// Gère la soumission du formulaire de création d'un nouveau client
    public function store(Request $request)
    {
        // Valide les informations soumises pour la création d'un nouveau client
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'account_status' => 'nullable|in:pending,active,inactive',
            'wants_newsletter' => 'nullable|boolean',
            'newsletter_category' => 'nullable|string|in:cmmc,loi25,iso27001',
        ]);
        // Par défaut le compte est en attente si aucun statut n'est fourni
        $statut = $validated['account_status'] ?? 'pending';
        // Crée un nouvel utilisateur avec les informations validées et le rôle "client"
        $client = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_name' => $validated['company_name'],
            'phone' => $validated['phone'] ?? null,
            'account_status' => $statut,
            'activated_at' => $statut === 'active' ? now() : null,
            'wants_newsletter' => $request->boolean('wants_newsletter'),
            'newsletter_category' => $request->input('newsletter_category'),
        ]);
        // Attribution du rôle "client" à l'utilisateur nouvellement créé
        $client->assignRole('client');

        return redirect()->route('admin.client.index')->with('success', 'Client créé avec succès.');
    }

    // Affiche les détails d'un client spécifique
    public function show($id)
    {
        // Récupère le client ciblé par son ID
        $client = User::findOrFail($id);
        return view('admin.client.show', compact('client'));
    }

    // Affiche le formulaire d'édition des informations d'un client spécifique
    public function edit($id)
    {  // Récupère le client ciblé par son ID pour préremplir le formulaire d'édition
        $client = User::findOrFail($id);

        return view('admin.client.edit', compact('client'));
    }

    // Gère la soumission du formulaire de mise à jour des informations d'un client spécifique
    public function update(Request $request, $id)
    {
        // Valide les informations soumises pour la mise à jour du client
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'account_status' => 'nullable|in:pending,active,inactive',
            'wants_newsletter' => 'nullable|boolean',
            'newsletter_category' => 'nullable|string|in:cmmc,loi25,iso27001',
        ]);

        // Assure que la valeur de "wants_newsletter" est correctement interprétée comme un booléen
        $validated['wants_newsletter'] = $request->boolean('wants_newsletter');

        // Récupère le client ciblé par son ID
        $client = User::findOrFail($id);

        // Si le compte est activé et qu'il n'avait pas de date d'activation, on enregistre la date actuelle
        if (($validated['account_status'] ?? null) === 'active' && !$client->activated_at) {
            $validated['activated_at'] = now();
        }
        // Met à jour les informations du client avec les données validées
        $client->update($validated);

        // Redirige vers la liste des clients avec un message de succès
        return redirect()->route('admin.client.index')->with('success', 'Client mis à jour avec succès.');
    }


    // Supprime définitivement un client spécifique
    public function destroy($id)
    {
        // Récupère le client ciblé par son ID et le supprime de la base de données
        User::findOrFail($id)->delete();

        return redirect()->route('admin.client.index')->with('success', 'Client supprimé avec succès.');
    }

    // Active manuellement un compte client et lui envoie une notification de vérification d'email.
    public function activate($id)
    {
        // Récupère le client ciblé par son ID
        $client = User::findOrFail($id);
        // Passe le statut du compte à "active" et enregistre la date d'activation actuelle
        $client->account_status = 'active';
        // Enregistre la date d'activation seulement si le compte n'était pas déjà activé
        $client->activated_at = now();
        // Enregistre les modifications dans la base de données
        $client->save();

        try {
            // Envoie un email de vérification au client pour confirmer son adresse email
            $client->sendEmailVerificationNotification();

            return redirect()
            //  Redirige vers la liste des clients avec un message de succès indiquant que le compte a été activé et que l'email de vérification a été envoyé
                ->route('admin.client.index')
                ->with('success', 'Compte client activé. Un e-mail de vérification a été envoyé.');
        } catch (\Exception $e) {
            //Si l'envoi de l'email échoue, on informe l'admin mais le compte reste activé
            return redirect()
                ->route('admin.client.index')
                ->with('success', 'Compte client activé, mais l\'e-mail de vérification n\'a pas pu être envoyé.');
        }
    }
}
