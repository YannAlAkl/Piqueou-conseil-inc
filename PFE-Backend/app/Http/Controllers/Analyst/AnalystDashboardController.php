<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use App\Models\UserQuestionnaireAnswer;
use Illuminate\Support\Facades\Auth;

class AnalystDashboardController extends Controller
{
    public function dashboard()
    {
        // Récupérer les soumissions de questionnaires assignées à l'analyste connecté
        $submissions = UserQuestionnaire::with('user', 'questionnaire')
            ->where('analyst_id', Auth::id())
            ->get();

        // Calculer le nombre total de soumissions en attente de révision et le nombre total de soumissions complétées
        $totalSubmissions = $submissions->count();
        $total = $submissions->where('status', 'under_review')->count();
        $completed = $submissions->where('status', 'completed')->count();

        // Initialiser les pourcentages de soumissions complétées et en attente
        $completedPercent = 0;
        $pendingPercent = 0;

        // Si le nombre total de soumissions est supérieur à zéro, calculer les pourcentages de soumissions complétées et en attente
        if ($totalSubmissions > 0) {
            // Calculer le pourcentage de soumissions complétées et en attente
            $completedPercent = round($completed * 100 / $totalSubmissions);
            // Calculer le pourcentage de soumissions en attente
            $pendingPercent = 100 - $completedPercent;
        }
        // Initialiser le compteur de recommandations et un tableau pour stocker les délais de soumission
        $recommendations = 0;
        // Initialiser un tableau pour stocker les délais de soumission
        $delays = [];

        // Si des soumissions existent, calculer le pourcentage de complétion et déduit le pourcentage en attente
        foreach ($submissions as $submission) {
            // Compter le nombre de recommandations pour chaque soumission en fonction des réponses de l'utilisateur
            $recommendations = $recommendations + UserQuestionnaireAnswer::where('user_id', $submission->user_id)
                // Filtrer les réponses par questionnaire_id pour ne compter que celles liées à la soumission actuelle
                ->where('questionnaire_id', $submission->questionnaire_id)
                // Compter uniquement les réponses qui ont une recommandation de l'analyste
                ->whereNotNull('analyst_recommendation')
                ->count();

            // Calculer le délai entre la soumission et la complétion pour chaque soumission
            if ($submission->submitted_at && $submission->completed_at) {
                // Calculer le délai en jours entre la date de soumission et la date de complétion
                $delays[] = $submission->submitted_at->diffInDays($submission->completed_at);
            }
        }
        // Initialiser la variable pour stocker le délai moyen de soumission
        $averageDelay = 0;

        // Si des délais existent, calculer le délai moyen de soumission
        if (count($delays) > 0) {
            // Calculer le délai moyen en jours en divisant la somme des délais par le nombre de délais et arrondir à une décimale
            $averageDelay = round(array_sum($delays) / count($delays), 1);
        }
        // Initialiser un tableau pour stocker les données de progression mensuelle
        $monthNames = ['', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        // Initialiser un tableau pour stocker les données de progression mensuelle
        $months = [];
        // Initialiser la valeur maximale de soumissions pour le calcul des hauteurs de barre dans le graphique
        $maximum = 1;
        // Parcourir les 6 derniers mois pour calculer le nombre de soumissions complétées par mois
        for ($i = 5; $i >= 0; $i--) {
            // Calculer la date de début du mois en cours moins $i mois
            $start = now()->startOfMonth()->subMonths($i);
            // Initialiser le compteur de soumissions complétées pour le mois en cours
            $value = 0;

            // Parcourir les soumissions pour compter celles qui ont été complétées dans le mois en cours
            foreach ($submissions as $submission) {
                // Vérifier si la soumission a été complétée et si la date de complétion correspond au mois en cours
                if ($submission->completed_at && $submission->completed_at->format('Y-m') === $start->format('Y-m')) {
                    // Incrémenter le compteur de soumissions complétées pour le mois en cours
                    $value++;
                }
            }
            // Mettre à jour la valeur maximale si le compteur de soumissions complétées pour le mois en cours est supérieur à la valeur maximale actuelle
            if ($value > $maximum) {
                // Mettre à jour la valeur maximale pour le calcul des hauteurs de barre dans le graphique
                $maximum = $value;
            }
            // Ajouter les données du mois en cours au tableau des mois avec le nom du mois et le nombre de soumissions complétées
            $months[] = [
                // Ajouter le nom du mois et le nombre de soumissions complétées pour le mois en cours au tableau des mois
                'label' => $monthNames[(int) $start->format('n')],
                // Ajouter la valeur du nombre de soumissions complétées pour le mois en cours au tableau des mois
                'value' => $value,
            ];
        }
        // Parcourir les mois pour calculer la hauteur de chaque barre dans le graphique en fonction de la valeur maximale
        foreach ($months as $index => $month) {
            $months[$index]['height'] = round($month['value'] * 100 / $maximum);
        }

        // Récupérer les 5 dernières soumissions pour l'analyste connecté, triées par date de mise à jour décroissante
        $latest = $submissions->sortByDesc('updated_at')->take(5);

        return view('analyst.dashboard', compact(
            'total',
            'completed',
            'totalSubmissions',
            'completedPercent',
            'pendingPercent',
            'recommendations',
            'averageDelay',
            'months',
            'latest'
        ));
    }
}
