<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\UserQuestionnaire;
use App\Models\UserQuestionnaireAnswer;
use Composer\XdebugHandler\Status;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{


    public function dashboard()
    {
        // Récupérer les questionnaires publiés avec leurs questions
        $questionnaires = Questionnaire::with('questions')
            ->where('status', 'published')
            ->orderBy('title')
            ->get();
        // Récupérer les soumissions de questionnaires de l'utilisateur connecté
        $submissions = UserQuestionnaire::where('user_id', Auth::id())
            ->get()
            ->keyBy('questionnaire_id');

        // Récupérer les réponses aux questionnaires de l'utilisateur connecté
        $answers = UserQuestionnaireAnswer::where('user_id', Auth::id())->get();

        // Initialiser le compteur de questions totales
        $totalQuestions = 0;
        // Compter le nombre total de questions dans tous les questionnaires publiés
        foreach ($questionnaires as $questionnaire) {
            // Ajouter le nombre de questions du questionnaire actuel au total
            $totalQuestions = $totalQuestions + count($questionnaire->questions);
        }

        // Initialiser les compteurs de questions répondues et de recommandations
        $answeredQuestions = 0;
        $recommendations = 0;

        // parcourir les réponses pour compter les questions répondues et les recommandations
        foreach ($answers as $answer) {
            // Vérifier si la réponse n'est pas nulle ou vide pour compter les questions répondues
            if ($answer->answer !== null && $answer->answer !== '') {
                // Incrémenter le compteur de questions répondues
                $answeredQuestions++;
            }
            // Vérifier si une recommandation de l'analyste est présente pour compter les recommandations
            if ($answer->analyst_recommendation !== null && $answer->analyst_recommendation !== '') {
                // Incrémenter le compteur de recommandations
                $recommendations++;
            }
        }
        // Initialiser le pourcentage de progression
        $progress = 0;

        // Si le nombre total de questions est supérieur à zéro, calculer le pourcentage de progression
        if ($totalQuestions > 0) {
            // Calculer le pourcentage de progression en fonction des questions répondues et du total de questions
            $progress = round($answeredQuestions * 100 / $totalQuestions);
        }
        // Initialiser la liste de progression pour chaque questionnaire
        $progressList = [];
        // parcourir chaque questionnaire pour calculer le pourcentage de progression spécifique à chaque questionnaire
        foreach ($questionnaires as $questionnaire) {
            // Initialiser le compteur de questions répondues pour le questionnaire actuel
            $answered = 0;
            // Parcourir les réponses pour compter celles qui appartiennent au questionnaire actuel et qui ne sont pas nulles ou vides
            foreach ($answers as $answer) {
                // Vérifier si la réponse appartient au questionnaire actuel et si elle n'est pas nulle ou vide
                if ($answer->questionnaire_id === $questionnaire->id && $answer->answer !== null && $answer->answer !== '') {
                    // Incrémenter le compteur de questions répondues pour le questionnaire actuel
                    $answered++;
                }
            }
            // Compter le nombre total de questions dans le questionnaire actuel
            $questionCount = count($questionnaire->questions);
            // Initialiser le pourcentage de progression pour le questionnaire actuel
            $percent = 0;
            // Si le nombre de questions dans le questionnaire actuel est supérieur à zéro, calculer le pourcentage de progression spécifique à ce questionnaire
            if ($questionCount > 0) {
                $percent = round($answered * 100 / $questionCount);
            }
            // Ajouter le pourcentage de progression du questionnaire actuel à la liste de progression
            $progressList[$questionnaire->id] = $percent;
        }
        // Compter le nombre de questionnaires complétés et en cours pour l'utilisateur connecté
        $completed = $submissions->where('status', 'completed')->count();
        // Compter le nombre de questionnaires en cours pour l'utilisateur connecté
        $inProgress = $submissions->whereIn('status', ['in_progress', 'submitted', 'under_review'])->count();

        // Déterminer l'étape actuelle du processus de soumission en fonction du statut des soumissions de l'utilisateur connecté
        $step/*Status*/ = 'not_started';

        // Si l'utilisateur a des soumissions, déterminer l'étape actuelle en fonction du statut de la dernière soumission mise à jour
        if ($submissions->count() > 0) {
            // Récupérer le statut de la dernière soumission mise à jour pour déterminer l'étape actuelle
            $step = $submissions->sortByDesc('updated_at')->first()->status;
        }
        // Initialiser le numéro de l'étape actuelle à 1 (non commencé)
        $stepNumber = 1;

        //si l'étape actuelle est "en cours", définir le numéro de l'étape à 2
        if ($step === 'in_progress') {
            $stepNumber = 2;
        //si l'étape actuelle est "soumis", définir le numéro de l'étape à 3
        } elseif ($step === 'submitted') {
            $stepNumber = 3;
        //si l'étape actuelle est "en révision", définir le numéro de l'étape à 4
        } elseif ($step === 'under_review') {
            $stepNumber = 4;
        //si l'étape actuelle est "complété", définir le numéro de l'étape à 5
        } elseif ($step === 'completed') {
            $stepNumber = 5;
        }

        // Définir les catégories de newsletter disponibles
        $categories = [
            'cmmc' => 'CMMC',
            'loi25' => 'Loi 25',
            'iso27001' => 'ISO 27001',
        ];
        // Récupérer le nom de la catégorie de newsletter de l'utilisateur connecté, ou définir "Non définie" si la catégorie n'est pas trouvée
        $newsletterName = $categories[Auth::user()->newsletter_category] ?? 'Non définie';

        return view('client.dashboard', compact(
            'questionnaires',
            'submissions',
            'progressList',
            'totalQuestions',
            'answeredQuestions',
            'progress',
            'recommendations',
            'completed',
            'inProgress',
            'stepNumber',
            'newsletterName'
        ));
    }
}
