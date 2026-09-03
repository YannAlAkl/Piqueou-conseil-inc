<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Mail\AnalystAssignedMail;
use App\Models\User;
use App\Models\UserQuestionnaire;
use App\Models\UserQuestionnaireAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubmissionController extends Controller
{

    // Montre à l'administrateur la liste des dossiers et la liste des analystes
    public function index()
    {
        // Récupère les soumissions avec leurs relations utilisateur, questionnaire et analyste, filtrées par statut et paginées
        $soumissions = UserQuestionnaire::with('user', 'questionnaire', 'analyst')
            // Filtre les soumissions dont le statut est "submitted", "under_review" ou "completed"
            ->whereIn('status', ['submitted', 'under_review', 'completed'])
            // Trie les soumissions par date de soumission décroissante
            ->orderByDesc('submitted_at')
            // Pagine les résultats pour afficher 15 soumissions par page
            ->paginate(15);

         // Récupère les analystes actifs pour les afficher dans la vue
        $analystes = User::whereHas('roles', function ($q) { return $q->where('name', 'analyst'); })
        // Filtre les analystes dont le statut de compte est "active"
            ->where('account_status', 'active')
            // Trie les analystes par prénom pour un affichage ordonné
            ->orderBy('first_name')
            // Récupère tous les analystes correspondants aux critères
            ->get();
            // Passe les soumissions et les analystes à la vue pour affichage
        return view('admin.submission.index', compact('soumissions', 'analystes'));
    }

    // Assiger un dossier à un analyste
    public function assign(Request $request, $id)
    {
        // Vérifie que l'analyste choisi existe vraiment
        $validated = $request->validate([
            // Valide que l'ID de l'analyste est requis et qu'il existe dans la table des utilisateurs
            'analyst_id' => 'required|exists:users,id',
        ]);

        // Trouve le dossier concerné
        $soumission = UserQuestionnaire::with('user', 'questionnaire')->findOrFail($id);

        // Si le dossier est déjà fini, on arrête  là
        if ($soumission->status === 'completed') {
            return back()->with('error', 'Ce dossier est déjà terminé.');
        }

        // Récupére la personne choisie et vérifie qu'elle est bien analyste
        $analyste = User::findOrFail($validated['analyst_id']);
        // Vérifie que l'utilisateur choisi a bien le rôle d'analyste
        if (!$analyste->hasRole('analyst')) {
            // Si l'utilisateur choisi n'est pas un analyste, retourne un message d'erreur
            return back()->with('error', 'Cet utilisateur n\'est pas un analyste.');
        }
        // Assigne le dossier à l'analyste choisi et met à jour le statut du dossier
        $soumission->analyst_id = $analyste->id;
        // Met à jour le statut du dossier pour indiquer qu'il est en cours d'examen par l'analyste
        $soumission->status = 'under_review';
        // Enregistre les modifications dans la base de données pour le dossier assigné
        $soumission->save();

        $message = 'Dossier assigné à ' . $analyste->name . '. Un email lui a été envoyé.';

        try {
            // Envoie un email à l'analyste pour l'informer de l'assignation du dossier
            Mail::to($analyste->email)->send(new AnalystAssignedMail($soumission, $analyste));
        } catch (\Exception $e) {
            // Si l'envoi de l'email échoue, on informe l'administrateur mais le dossier reste assigné
            $message = 'Dossier assigné à ' . $analyste->name . ' mais l\'email n\'a pas pu être envoyé.';
        }
        // Redirige vers la liste des dossiers avec un message de succès ou d'erreur selon le cas
        return back()->with('success', $message);
    }

    // Montre à l'administrateur les détails d'un dossier spécifique
    public function viewQuestionnaire($id)
    {
        // Récupère le dossier avec ses relations utilisateur, analyste et questionnaire, ou échoue si non trouvé
        $soumission = UserQuestionnaire::with('user', 'analyst', 'questionnaire.questions.type')
            // Filtre les dossiers dont le statut est "submitted", "under_review" ou "completed"
            ->findOrFail($id);

            // Récupère les réponses associées au dossier pour l'utilisateur et le questionnaire spécifiques, et les organise par ID de question
        $reponses = UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
        // Filtre les réponses pour le questionnaire spécifique du dossier
            ->where('questionnaire_id', $soumission->questionnaire_id)
            // Récupère toutes les réponses correspondantes
            ->get()
            // Organise les réponses récupérées par ID de question pour un accès facile dans la vue
            ->keyBy('question_id');
        // Passe le dossier et les réponses à la vue pour affichage des détails du dossier
        return view('admin.submission.show', compact('soumission', 'reponses'));
    }
}
