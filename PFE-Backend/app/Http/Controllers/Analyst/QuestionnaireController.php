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
    public function index()
    {
        $questionnaires = UserQuestionnaire::with('user', 'questionnaire')
            ->where('analyst_id', Auth::id())
            ->whereIn('status',['under_review', 'completed'])
            ->get();

        return view('analyst.questionnaire.index', compact('questionnaires'));
    }

    public function show(int $id)
    {
        $soumission = UserQuestionnaire::with('questionnaire.questions.type', 'user')
            ->where('analyst_id', Auth::id())
            ->where('status', 'under_review')
            ->findOrFail($id);

        $reponses = UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
            ->where('questionnaire_id', $soumission->questionnaire_id)
            ->get()
            ->keyBy('question_id');

        return view('analyst.questionnaire.show', compact('soumission', 'reponses'));
    }

    public function store(Request $request, int $id)
    {
    $validated = $request->validate([
        'conclusion' => 'required|string',
        'recommendation' => 'required|array',
    ]);

    $soumission = UserQuestionnaire::where('analyst_id', Auth::id())
        ->whereIn('status', ['submitted', 'under_review'])
        ->findOrFail($id);

    $soumission->update([
        'conclusion' => $validated['conclusion'],
        'status'     => 'under_review',
    ]);

    // Utilisation correcte du tableau validé
    foreach ($validated['recommendation'] as $questionId => $recommendation) {
        UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
            ->where('question_id', $questionId)
            ->update(['analyst_recommendation' => $recommendation]);
    }

    //une fois l'analyse enregiste le stautue doit passer vers completed
     $soumission->status = 'completed';
     $soumission->save();

$message = 'Recommandations enregistrées avec succès.';

$admin = User::whereHas('roles', function ($q) {
    return $q->where('name', 'admin');
})->first();

try {
    Mail::to($soumission->user->email)->send(new QuestionnaireSubmittedMail($soumission));

    if ($admin) {
        Mail::to($admin->email)->send(new QuestionnaireSubmittedMail($soumission, true));
    }
} catch (\Exception $e) {
    $message = 'Recommandations enregistrées avec succès, mais l\'email n\'a pas pu être envoyé.';
}
    return redirect()
        ->route('analyst.questionnaire.index')
        ->with('success', $message);
    }
}