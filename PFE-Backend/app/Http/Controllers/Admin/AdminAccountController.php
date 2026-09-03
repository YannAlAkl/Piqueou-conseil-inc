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

// recupere par groupe de 10  des compte avec le role admin
class AdminAccountController extends Controller
{

    // Liste paginée des utilisateurs ayant le rôle "admin", triée par date de création décroissante
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


    // recupere par groupe de 10  des compte avec le role analyst
    public function store(Request $request)
    {
        // valide les informations du nouvel admin
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users,email',
            'phone'          => 'nullable|string|max:30',
            'company_name'   => 'nullable|string|max:255',
            'account_status' => 'nullable|in:pending,active,inactive',
        ]);
        // Par défaut le compte est actif si aucun statut n'est fourni
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

            //N'enregistre une date d'activation que si le compte est créer directement actif
            'activated_at'   => $statut === 'active' ? now() : null,
        ]);

        // Attribution du rôle administrateur
        $admin->assignRole('admin');

        $message = 'Admin ajouté. Ses identifiants lui ont été envoyés par email.';

        try {
            Mail::to($admin->email)->send(new AdminAccountMail($admin, $motDePasse));
        } catch (\Exception $e) {
            // Si l'email échoue, on affiche quand meme le mots de passe généré pour ne pas bloqquer l'admin
            $message = 'Admin ajouté mais l\'email n\'a pas pu être envoyé. Mot de passe : ' . $motDePasse;
        }

        return redirect()->route('admin.admin.index')->with('success', $message);
    }

    public function show($id)
    {
        // Récupère l'utilisateur avec le rôle "admin" correspondant à l'ID fourni
        $admin = User::findOrFail($id);
        return view('admin.admin.show', compact('admin'));
    }

    public function edit($id)
    {
        // Récupère l'utilisateur avec le rôle "admin" correspondant à l'ID fourni pour l'édition
        $admin = User::findOrFail($id);
        return view('admin.admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        // Valide les infos modifiées , en excultant l'analyste courant de la contrainte d'unicité de l'email
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
        //enregistrer la date d'activition seulement au premier passage vers "active"
        if (($validated['account_status'] ?? null) === 'active' && ! $admin->activated_at) {
            $validated['activated_at'] = now();
        }

        $admin->update($validated);

        return redirect()->route('admin.admin.index')->with('success', 'Administrateur mis à jour.');
    }



    // supression d'un compte admin
    public function destroy($id)
    {
       // Récupère l'utilisateur avec le rôle "admin" correspondant à l'ID fourni et le supprime
        User ::findOrFail($id)->delete();
        return redirect()->route('admin.admin.index')->with('success', 'Administrateur supprimé.');
    }

    // Active manuellement un compte administrateur et lui envoie une notification de vérification d'email.
    public function verify($id)
    {
    //recupere l'utilisateur ciblée
    $admin = User::findOrFail($id);

    //Passage du statut à actif et enregistrement de la date d'activation
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
