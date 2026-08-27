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

        $soumissions = UserQuestionnaire::with('user', 'questionnaire', 'analyst')
            ->whereIn('status', ['submitted', 'under_review', 'completed'])
            ->orderByDesc('submitted_at')
            ->paginate(15);

        $analystes = User::whereHas('roles', function ($q) { return $q->where('name', 'analyst'); })
            ->where('account_status', 'active')
            ->orderBy('first_name')
            ->get();

        return view('admin.submission.index', compact('soumissions', 'analystes'));
    }

    // Assiger un dossier à un analyste
    public function assign(Request $request, $id)
    {
        // Vérifie que l'analyste choisi existe vraiment
        $validated = $request->validate([
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

        if (!$analyste->hasRole('analyst')) {
            return back()->with('error', 'Cet utilisateur n\'est pas un analyste.');
        }

        $soumission->analyst_id = $analyste->id;
        $soumission->status = 'under_review';
        $soumission->save();

        $message = 'Dossier assigné à ' . $analyste->name . '. Un email lui a été envoyé.';

        try {
            Mail::to($analyste->email)->send(new AnalystAssignedMail($soumission, $analyste));
        } catch (\Exception $e) {
            $message = 'Dossier assigné à ' . $analyste->name . ' mais l\'email n\'a pas pu être envoyé.';
        }

        return back()->with('success', $message);
    }

    public function viewQuestionnaire($id)
    {
        $soumission = UserQuestionnaire::with('user', 'analyst', 'questionnaire.questions.type')
            ->findOrFail($id);

        $reponses = UserQuestionnaireAnswer::where('user_id', $soumission->user_id)
            ->where('questionnaire_id', $soumission->questionnaire_id)
            ->get()
            ->keyBy('question_id');

        return view('admin.submission.show', compact('soumission', 'reponses'));
    }
}
