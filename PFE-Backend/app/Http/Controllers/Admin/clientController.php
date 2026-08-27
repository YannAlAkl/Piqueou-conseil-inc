<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class clientController extends Controller
{
    public function index()
    {
        $clients = User::whereHas('roles', function ($q) { return $q->where('name', 'client'); })
            ->latest()
            ->paginate(10);

        return view('admin.client.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.client.create');
    }

    public function store(Request $request)
    {
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

        $statut = $validated['account_status'] ?? 'pending';

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

        $client->assignRole('client');

        return redirect()->route('admin.client.index')->with('success', 'Client créé avec succès.');
    }

    public function show($id)
    {
        $client = User::findOrFail($id);

        return view('admin.client.show', compact('client'));
    }

    public function edit($id)
    {
        $client = User::findOrFail($id);

        return view('admin.client.edit', compact('client'));
    }

    public function uptade(Request $request, $id)
    {
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

        $validated['wants_newsletter'] = $request->boolean('wants_newsletter');

        $client = User::findOrFail($id);

        if (($validated['account_status'] ?? null) === 'active' && !$client->activated_at) {
            $validated['activated_at'] = now();
        }

        $client->update($validated);

        return redirect()->route('admin.client.index')->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('admin.client.index')->with('success', 'Client supprimé avec succès.');
    }

    public function activate($id)
    {
        $client = User::findOrFail($id);

        $client->account_status = 'active';
        $client->activated_at = now();
        $client->save();

        try {
            $client->sendEmailVerificationNotification();

            return redirect()
                ->route('admin.client.index')
                ->with('success', 'Compte client activé. Un e-mail de vérification a été envoyé.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.client.index')
                ->with('success', 'Compte client activé, mais l\'e-mail de vérification n\'a pas pu être envoyé.');
        }
    }
}
