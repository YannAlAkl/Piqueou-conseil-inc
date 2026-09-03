<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use Illuminate\Support\Facades\Auth;

class AnalystDashboardController extends Controller
{
    // Affiche le tableau de bord de l'analyste avec le nombre total de questionnaires en cours d'examen
    public function dashboard()
    {
        // Compte le nombre total de questionnaires assignés à l'analyste courant et dont le statut est "under_review"
        $total = UserQuestionnaire::where('analyst_id', Auth::id())
            // Filtre les questionnaires dont le statut est "under_review"
            ->where('status', 'under_review')
            // Compte le nombre total de questionnaires correspondant aux critères
            ->count();
        // Passe la variable $total à la vue du tableau de bord de l'analyste pour affichage
        return view('analyst.dashboard', compact('total'));
    }
}
