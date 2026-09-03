<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\User;
use App\Models\UserQuestionnaire;
use App\Models\UserQuestionnaireAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\QuestionnaireSubmittedMail;
use Illuminate\Support\Facades\Mail;

class QuestionnaireController extends Controller
{

    // Affiche la liste des questionnaires disponibles pour le client
    public function index()
    {
        // Récupère tous les questionnaires publiés, triés par titre
        $questionnaires = Questionnaire::where('status', 'published')
        //  Trié par titre pour une meilleure lisibilité
            ->orderBy('title')
            ->get();

        // Récupère les soumissions du client courant, indexées par l'ID du questionnaire
        $mesSoumissions = UserQuestionnaire::where('user_id', auth::id())
        // Récupère les soumissions du client courant, indexées par l'ID du questionnaire
            ->get()
            // Indexe les soumissions par l'ID du questionnaire pour un accès rapide
            ->keyBy('questionnaire_id');

        return view('client.questionnaire.index', compact('questionnaires', 'mesSoumissions'));
    }


    // Affiche le questionnaire spécifique pour le client, avec ses réponses et la possibilité de modifier si le statut le permet
    public function show(int $id)
    {
        // Récupère le questionnaire avec ses questions et leurs types, uniquement s'il est publié
        $questionnaire = Questionnaire::with('questions.type')
        // Vérifie que le questionnaire est publié avant de le récupérer
            ->where('status', 'published')
            // Récupère le questionnaire ou échoue avec une erreur 404 si non trouvé
            ->findOrFail($id);
        // Récupère ou crée une soumission pour le questionnaire courant et le client courant, avec un statut par défaut "in_progress"
        $soumission = UserQuestionnaire::firstOrCreate(
            [
                // Vérifie si une soumission existe déjà pour le client courant et le questionnaire courant
                'user_id' => auth::id(),
                // Vérifie si une soumission existe déjà pour le client courant et le questionnaire courant
                'questionnaire_id' => $questionnaire->id,
            ],
            [
                // Si aucune soumission n'existe, crée une nouvelle soumission avec le statut "in_progress"
                'status' => 'in_progress',
            ]
        );
        // Récupère les réponses du client pour le questionnaire courant, indexées par l'ID de la question
        $reponses = UserQuestionnaireAnswer::where('user_id', auth::id())
        // Récupère les réponses du client pour le questionnaire courant, indexées par l'ID de la question
            ->where('questionnaire_id', $questionnaire->id)
            // Indexe les réponses par l'ID de la question pour un accès rapide
            ->get()
            // Indexe les réponses par l'ID de la question pour un accès rapide
            ->keyBy('question_id');
        // Détermine si le questionnaire est modifiable en fonction du statut de la soumission
        $modifiable = in_array($soumission->status, ['not_started', 'in_progress']);

        return view('client.questionnaire.show', compact('questionnaire', 'soumission', 'reponses', 'modifiable'));
    }

    // Gère la soumission des réponses du client pour un questionnaire spécifique
    public function submit(Request $request, int $id)
    {
        // Récupère le questionnaire avec ses questions et leurs types, uniquement s'il est publié
        $questionnaire = Questionnaire::with('questions')
        // Vérifie que le questionnaire est publié avant de le récupérer
            ->where('status', 'published')

            ->findOrFail($id);

        // Récupère ou crée une soumission pour le questionnaire courant et le client courant, avec un statut par défaut "in_progress"
        $soumission = UserQuestionnaire::firstOrCreate(
            [
                // Vérifie si une soumission existe déjà pour le client courant et le questionnaire courant
                'user_id' => auth::id(),
                'questionnaire_id' => $questionnaire->id,
            ],
            [
                // Si aucune soumission n'existe, crée une nouvelle soumission avec le statut "in_progress"
                'status' => 'in_progress',
            ]
        );

        // Vérifie si le questionnaire est modifiable en fonction du statut de la soumission
        if (! in_array($soumission->status, ['not_started', 'in_progress'])) {
            // Si le questionnaire n'est pas modifiable, redirige vers la page du questionnaire avec un message d'erreur
            return redirect()->route('client.questionnaire.show', $questionnaire->id);
        }
        // Valide les réponses et les commentaires soumis par le client
        $request->validate([
            // Valide que les réponses sont un tableau et que les commentaires sont également un tableau
            'answers' => 'nullable|array',
            // Valide que les commentaires sont un tableau, mais ils peuvent être absents
            'comments' => 'nullable|array',
        ]);
        // Récupère les réponses et les commentaires soumis par le client, avec des valeurs par défaut si elles sont absentes
        $reponses = $request->input('answers', []);
        $commentaires = $request->input('comments', []);

        // Parcourt chaque question du questionnaire pour enregistrer les réponses et les commentaires du client
        foreach ($questionnaire->questions as $question) {

            $valeur = $reponses[$question->id] ?? null;

            if (is_array($valeur)) {
                $valeur = implode(', ', $valeur);
            }
            UserQuestionnaireAnswer::updateOrCreate(
                [
                    'user_id' => auth::id(),
                    'question_id' => $question->id,
                ],
                [
                    'questionnaire_id' => $questionnaire->id,
                    'answer' => $valeur,
                    'client_comment' => $commentaires[$question->id] ?? null,
                    'answered_at' => now(),
                ]
            );
        }

        if ($request->input('action') !== 'envoyer') {
            $soumission->status = 'in_progress';
            $soumission->save();

            return redirect()->route('client.questionnaire.show', $questionnaire->id)
                ->with('success', 'Vos réponses ont été enregistrées.');
        }

        foreach ($questionnaire->questions as $question) {
            if ($question->required && empty($reponses[$question->id])) {
                return redirect()->route('client.questionnaire.show', $questionnaire->id)
                    ->with('error', 'Vous devez répondre à toutes les questions obligatoires avant d\'envoyer.');
            }
        }

        $soumission->status = 'submitted';
        $soumission->submitted_at = now();
        $soumission->save();

        $admin = User::whereHas('roles', function ($q) {
    return $q->where('name', 'admin');
    })->first();

$message = 'Votre questionnaire a bien été envoyé.';

try {
    Mail::to($admin->email)->send(new QuestionnaireSubmittedMail($soumission));
} catch (\Exception $e) {
    $message = 'Votre questionnaire a bien été envoyé, mais l\'email n\'a pas pu être envoyé à l\'administrateur.';
}

       return redirect()->route('client.questionnaire.show', $questionnaire->id)
    ->with('success', $message);
    }
}
