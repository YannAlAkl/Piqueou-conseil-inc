<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\UserQuestionnaire;
use App\Models\UserQuestionnaireAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class QuestionnaireController extends Controller
{
    public function index()
    {
        $questionnaires = Questionnaire::where('status', 'published')
            ->orderBy('title')
            ->get();
            
           

        $mesSoumissions = UserQuestionnaire::where('user_id', auth::id())
            ->get()
            ->keyBy('questionnaire_id');

        return view('client.questionnaire.index', compact('questionnaires', 'mesSoumissions'));
    }

    public function show(int $id)
    {
        $questionnaire = Questionnaire::with('questions.type')
            ->where('status', 'published')
            ->findOrFail($id);

        $soumission = UserQuestionnaire::firstOrCreate(
            [
                'user_id' => auth::id(),
                'questionnaire_id' => $questionnaire->id,
            ],
            [
                'status' => 'in_progress',
            ]
        );

        $reponses = UserQuestionnaireAnswer::where('user_id', auth::id())
            ->where('questionnaire_id', $questionnaire->id)
            ->get()
            ->keyBy('question_id');

        $modifiable = in_array($soumission->status, ['not_started', 'in_progress']);

        return view('client.questionnaire.show', compact('questionnaire', 'soumission', 'reponses', 'modifiable'));
    }

    public function submit(Request $request, int $id)
    {
        $questionnaire = Questionnaire::with('questions')
            ->where('status', 'published')
            ->findOrFail($id);

        $soumission = UserQuestionnaire::firstOrCreate(
            [
                'user_id' => auth::id(),
                'questionnaire_id' => $questionnaire->id,
            ],
            [
                'status' => 'in_progress',
            ]
        );

        if (! in_array($soumission->status, ['not_started', 'in_progress'])) {
            return redirect()->route('client.questionnaire.show', $questionnaire->id);
        }

        $request->validate([
            'answers' => 'nullable|array',
            'comments' => 'nullable|array',
        ]);

        $reponses = $request->input('answers', []);
        $commentaires = $request->input('comments', []);

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

        return redirect()->route('client.questionnaire.show', $questionnaire->id)
            ->with('success', 'Votre questionnaire a bien été envoyé.');
    }
}
