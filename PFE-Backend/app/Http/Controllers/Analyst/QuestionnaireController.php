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
// Envoi d'un email au client et à l'administrateur pour les informer de la soumission du questionnaire
$message = 'Recommandations enregistrées avec succès. Un email a été envoyé au client et à l\'administrateur pour les informer de la soumission du questionnaire.';
// Récupère le premier utilisateur ayant le rôle "admin" pour l'envoi de l'email
$admin = User::whereHas('roles', function ($q) {
    return $q->where('name', 'admin');
})->first();
// Envoi des emails de notification au client et à l'administrateur
try {
    Mail::to($soumission->user->email)->send(new QuestionnaireSubmittedMail($soumission));
// Envoi d'un email à l'administrateur si un compte admin est trouvé
    if ($admin) {
        Mail::to($admin->email)->send(new QuestionnaireSubmittedMail($soumission, true));
    }
    // Si l'envoi des emails réussit, on peut définir un message de succès
} catch (\Exception $e) {
    $message = 'Recommandations enregistrées avec succès, mais l\'email n\'a pas pu être envoyé.';
}
// Redirection vers la liste des questionnaires de l'analyste avec un message de succès
    return redirect()
        ->route('analyst.questionnaire.index')
        ->with('success', $message);
    }
}
