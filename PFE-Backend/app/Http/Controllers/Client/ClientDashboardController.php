<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

// Affiche le tableau de bord du client
class ClientDashboardController extends Controller
{
    // Affiche le tableau de bord du client
    public function dashboard()
    {
        return view('client.dashboard');
    }
}
