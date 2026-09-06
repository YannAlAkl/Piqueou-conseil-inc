<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Mail\QuestionnaireSubmittedMail;
use App\Models\User;
use App\Models\UserQuestionnaire;
use App\Models\UserQuestionnaireAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class QuestionnaireController extends Controller
{
    // Montre à l'analyste la liste de ses questionnaires
    public function index()
    {
        // Récupère les questionnaires avec leur utilisateur et leur questionnaire
        $questionnaires = UserQuestionnaire::with('user', 'questionnaire')
            // Filtre les questionnaires de l'analyste connecté
            ->where('analyst_id', Auth::id())
            // Filtre les questionnaires dont le statut est "under_review" ou "completed"
            ->whereIn('status', ['under_review', 'completed'])
            // Récupère tous les questionnaires correspondants
            ->get();

        // Passe les questionnaires à la vue pour affichage
        return view('analyst.questionnaire.index', compact('questionnaires'));
    }

    // Montre à l'analyste les détails d'un questionnaire spécifique
    public function show(int $id)
    {
        // Récupère le dossier avec ses relations questionnaire, questions, types et utilisateur
        $soumission = UserQuestionnaire::with('questionnaire.questions.type', 'user')
            // Vérifie que le dossier appartient à l'analyste connecté
            ->where('analyst_id', Auth::id())
            // Filtre les dossiers dont le statut est "under_review"
            ->where('status', 'under_review')
            // Trouve le dossier ou échoue si non trouvé
            ->findOrFail($id);

        // Récupère les réponses associées au dossier pour l'utilisateur et le questionnaire spécifiques
        $reponses = UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
            // Filtre les réponses pour le questionnaire spécifique du dossier
            ->where('questionnaire_id', $soumission->questionnaire_id)
            // Récupère toutes les réponses correspondantes
            ->get()
            // Organise les réponses récupérées par ID de question
            ->keyBy('question_id');

        // Passe le dossier et les réponses à la vue pour affichage des détails du dossier
        return view('analyst.questionnaire.show', compact('soumission', 'reponses'));
    }

    // Enregistre l'analyse et les recommandations de l'analyste
    public function store(Request $request, int $id)
    {
        // Valide les informations envoyées par l'analyste
        $validated = $request->validate([
            // Valide que la conclusion est obligatoire et contient du texte
            'conclusion' => 'required|string',
            // Valide que les recommandations sont obligatoires et sous forme de tableau
            'recommendation' => 'required|array',
        ]);

        // Trouve le dossier concerné pour l'analyste connecté
        $soumission = UserQuestionnaire::where('analyst_id', Auth::id())
            // Filtre les dossiers dont le statut est "submitted" ou "under_review"
            ->whereIn('status', ['submitted', 'under_review'])
            // Trouve le dossier ou échoue si non trouvé
            ->findOrFail($id);

        // Enregistre la conclusion et met à jour le statut du dossier
        $soumission->update([
            // Enregistre la conclusion de l'analyste
            'conclusion' => $validated['conclusion'],
            // Met à jour le statut du dossier pour indiquer qu'il est en cours d'examen
            'status'     => 'under_review',
        ]);

        // Parcourt les recommandations pour chaque question
        foreach ($validated['recommendation'] as $questionId => $recommendation) {
            // Récupère la réponse du client pour la question concernée
            UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
                // Filtre la réponse par ID de question
                ->where('question_id', $questionId)
                // Enregistre la recommandation de l'analyste
                ->update(['analyst_recommendation' => $recommendation]);
        }

        // Une fois l'analyse enregistrée, le statut doit passer à "completed"
        $soumission->status = 'completed';
        // Enregistre les modifications dans la base de données
        $soumission->save();
        // Prépare le message de succès pour le client et l'administrateur
        $message = 'Recommandations enregistrées avec succès. Un email a été envoyé au client et à l\'administrateur pour les informer de la soumission du questionnaire.';
        // Récupère le premier utilisateur ayant le rôle "admin"
        $admin = User::whereHas('roles', function ($q) {
            // Vérifie que le rôle de l'utilisateur est "admin"
            return $q->where('name', 'admin');
        })->first();
        // Envoie les emails de notification au client et à l'administrateur
        try {
            // Envoie un email au client pour l'informer du traitement du questionnaire
            Mail::to($soumission->user->email)->send(new QuestionnaireSubmittedMail($soumission));
            // Envoie un email à l'administrateur si un compte admin est trouvé
            if ($admin) {
                Mail::to($admin->email)->send(new QuestionnaireSubmittedMail($soumission, true));
            }
        } catch (\Exception $e) {
            // Si l'envoi de l'email échoue, on informe l'utilisateur mais le dossier reste enregistré
            $message = 'Recommandations enregistrées avec succès, mais l\'email n\'a pas pu être envoyé.';
        }
        // Redirige vers la liste des questionnaires avec un message de succès ou d'erreur
        return redirect()
            // Retourne à la liste des questionnaires de l'analyste
            ->route('analyst.questionnaire.index')
            // Affiche le message de résultat
            ->with('success', $message);
    }
     public function saveProgress(Request $request, int $id)
    {
        // Valide les informations envoyées par l'analyste
        $validated = $request->validate([
            // Valide que la conclusion est obligatoire et contient du texte
            'conclusion' => 'required|string',
            // Valide que les recommandations sont obligatoires et sous forme de tableau
            'recommendation' => 'required|array',
        ]);

        // Trouve le dossier concerné pour l'analyste connecté
        $soumission = UserQuestionnaire::where('analyst_id', Auth::id())
            // Filtre les dossiers dont le statut est "submitted" ou "under_review"
            ->whereIn('status', ['submitted', 'under_review'])
            // Trouve le dossier ou échoue si non trouvé
            ->findOrFail($id);

        // Enregistre la conclusion et met à jour le statut du dossier
        $soumission->update([
            // Enregistre la conclusion de l'analyste
            'conclusion' => $validated['conclusion'],
            // Met à jour le statut du dossier pour indiquer qu'il est en cours d'examen
            'status'     => 'under_review',
        ]);

        // Parcourt les recommandations pour chaque question
        foreach ($validated['recommendation'] as $questionId => $recommendation) {
            // Récupère la réponse du client pour la question concernée
            UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
                // Filtre la réponse par ID de question
                ->where('question_id', $questionId)
                // Enregistre la recommandation de l'analyste
                ->update(['analyst_recommendation' => $recommendation]);
        }

        // Une fois l'analyse enregistrée, le statut doit passer à "completed"
        $soumission->status = 'under_review';
        // Enregistre les modifications dans la base de données
        $soumission->save();
        // Prépare le message de succès pour le client et l'administrateur
        $message = 'Progression enregistrées avec succès.';
        // Redirige vers la liste des questionnaires avec un message de succès ou d'erreur
        return redirect()
            // Retourne à la liste des questionnaires de l'analyste
            ->route('analyst.questionnaire.index')
            // Affiche le message de résultat
            ->with('success', $message);
    }
}


