<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\UserQuestionnaire;
use App\Models\User;
use App\Models\UserQuestionnaireAnswer;
use Illuminate\Http\Request;

class adminController extends Controller
{
    //
    public function dashboard()
    {
        // Compte les utilisateurs par rôle et par statut de compte pour les cartes du tableau de bord admin
        $stats = [
            'clients'  => User::whereHas('roles', function ($q) { return $q->where('name', 'client'); })->count(),
            'analysts' => User::whereHas('roles', function ($q) { return $q->where('name', 'analyst'); })->count(),
            'pending'  => User::where('account_status', 'pending')->count(),
            'active'   => User::where('account_status', 'active')->count(),
            'inactive' => User::where('account_status', 'inactive')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function edit($id)
    {
        // Récupère l'utilisateur ciblé pour préremplir le formulaire de modification des infos personnelles (nom,email, téléphone, entreprise)
     $user = User::findOrFail($id);

    return view('admin.user.edit', compact('user'));
    }


     // Valide les infos personnelles soumises, en excluant l'utilisateur courant de la contrainte d'unicité sur l'email
    public function update(Request $request, $id)
    {
        // Valide les informations soumises pour la mise à jour de l'utilisateur, en excluant l'utilisateur courant de la contrainte d'unicité sur l'email
        $validated = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone'        => 'nullable|string|max:30',
            'company_name' => 'nullable|string|max:255',
        ]);

        // Récupère l'utilisateur ciblé et met à jour ses informations avec les données validées
        $user = User::findOrFail($id);
        // Met à jour les informations de l'utilisateur avec les données validées
        $user->update($validated);

        return back()->with('success', 'Utilisateur mis à jour.');
    }


    // Supprime définitivement l'utilisateur ciblé (hard delete, pas de soft delete configuré sur ce modèle)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }
    public function activate( $id, $role)
    {
        // Vérifie que le rôle passé dans l'URL par l'admin correspond à un type de compte valide
        if (! in_array($role, ['client', 'analyst'])) {
            abort(404);
        }

        $user = User::findOrFail($id);

        // Empêche l'admin de réactiver un compte déjà actif
        if ($user->account_status === 'active') {
            return redirect()->route('login')->with('status', 'Ce compte est déjà actif.');
        }
        // L'admin fait passer le compte de "pending" à "active"
        $user->account_status = 'active';
        $user->activated_at = now();
        $user->save();

        $message = 'Le compte a été activé. Un email de vérification a été envoyé.';

        try {
            // Envoie un email de vérification à l'utilisateur pour confirmer son adresse email
            $user->sendEmailVerificationNotification();
        } catch (\Exception $e) {
            // Si l'envoi de l'email échoue, on informe l'admin mais le compte reste activé
            $message = 'Le compte a été activé mais l\'email de vérification n\'a pas pu être envoyé.';
        }

        return redirect()->route('login')->with('status', $message);
    }

    // Montre à l'administrateur la liste des dossiers en analyse ou terminés
    public function viewQuestionnaires()
    {
        // recupere les questionnaire completed c'est a dire repondus par le client et analyse par l'analyste
        $questionnaires = UserQuestionnaire::with('user', 'questionnaire')
        //filtre pour ne recuperer que les questionnaires completed ou under_review
            ->whereIn('status', ['under_review','completed'])
            ->get();
        return view('admin.questionnaire.index', compact('questionnaires'));
    }


    // montre à l'administrateur ce qui se trouve dans chacun des dossier analyse ou terminés
   public function showQuestionnaire($id)
   {
        // recupere le contenus des questionnaires completed c'est a dire repondus par le client et analyse par l'analyste
        $soumission = UserQuestionnaire::with('user', 'analyst', 'questionnaire.questions.type')
        ->where('status', 'completed')//filtre pour ne recuperer que les questionnaires completed
        ->findOrFail($id);// recupere le questionnaire completed correspondant à l'id fourni


        // recupere les reponses du questionnaire completed correspondant à l'id fourni
        $reponses = UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
        //filtre pour ne recuperer que les reponses du questionnaire completed correspondant à l'id fourni
            ->where('questionnaire_id', $soumission->questionnaire_id)
            ->get()
            ->keyBy('question_id');

            return view('admin.questionnaire.show', compact('soumission', 'reponses'));
    }

}

