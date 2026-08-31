<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminAccountMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminAccountController extends Controller
{
    public function index()
    {
        $admins = User::whereHas('roles', function ($q) { return $q->where('name', 'admin'); })
            ->latest()
            ->paginate(10);

        return view('admin.admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email',
            'phone'          => 'nullable|string|max:30',
            'company_name'   => 'nullable|string|max:255',
            'account_status' => 'nullable|in:pending,active,inactive',
        ]);

        $statut = $validated['account_status'] ?? 'active';
        $motDePasse = Str::random(10);

        $admin = User::create([
            'first_name'     => $validated['first_name'],
            'last_name'      => $validated['last_name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($motDePasse),
            'phone'          => $validated['phone'] ?? null,
            'company_name'   => $validated['company_name'] ?? null,
            'account_status' => $statut,
            'activated_at'   => $statut === 'active' ? now() : null,
        ]);

        $admin->assignRole('admin');

        $message = 'Admin ajouté. Ses identifiants lui ont été envoyés par email.';

        try {
            Mail::to($admin->email)->send(new AdminAccountMail($admin, $motDePasse));
        } catch (\Exception $e) {
            $message = 'Admin ajouté mais l\'email n\'a pas pu être envoyé. Mot de passe : ' . $motDePasse;
        }

        return redirect()->route('admin.admin.index')->with('success', $message);
    }

    public function show($id)
    {
        $admin = User::findOrFail($id);
        return view('admin.admin.show', compact('admin'));
    }

    public function edit($id)
    {
        $admin = User::findOrFail($id);
        return view('admin.admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email,' . $id,
            'phone'          => 'nullable|string|max:30',
            'company_name'   => 'nullable|string|max:255',
            'account_status' => 'nullable|in:pending,active,inactive',
        ]);

        $admin = User::findOrFail($id);

        // Un admin ne peut pas desactiver son propre compte (evite de se bloquer l'acces)
        if ((int) $id === (int) Auth::id()) {
            $validated['account_status'] = 'active';
        }

        if (($validated['account_status'] ?? null) === 'active' && ! $admin->activated_at) {
            $validated['activated_at'] = now();
        }

        $admin->update($validated);

        return redirect()->route('admin.admin.index')->with('success', 'Administrateur mis à jour.');
    }

    public function destroy($id)
    {
        User ::findOrFail($id)->delete();
        return redirect()->route('admin.admin.index')->with('success', 'Analyste supprimé.');
    }

    public function verify($id)
{
    $admin = User::findOrFail($id);

    $admin->account_status = 'active';
    $admin->activated_at = now();
    $admin->save();

    $message = 'Compte admin activé et email de vérification envoyé.';

    try {
        $admin->sendEmailVerificationNotification();
    } catch (\Exception $e) {
        // L'activation reste effective même si l'envoi de l'email échoue
        $message = 'Compte admin activé mais l\'email de vérification n\'a pas pu être envoyé.';
    }

    return redirect()->route('admin.admin.index')->with('success', $message);
    }
}
