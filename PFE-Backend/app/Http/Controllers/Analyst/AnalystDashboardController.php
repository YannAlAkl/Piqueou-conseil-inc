<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\UserQuestionnaire;
use Illuminate\Support\Facades\Auth;

class AnalystDashboardController extends Controller
{
    public function dashboard()
    {
        $total = UserQuestionnaire::where('analyst_id', Auth::id())
            ->where('status', 'under_review')
            ->count();

        return view('analyst.dashboard', compact('total'));
    }
}
