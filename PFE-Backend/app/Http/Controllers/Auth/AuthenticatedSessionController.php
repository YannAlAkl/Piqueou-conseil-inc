<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
        public function store(LoginRequest $request): RedirectResponse
        {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Les informations de connexion sont incorrectes.',
            ]);
        }

        // Les vérifications d'activation et d'e-mail concernent uniquement les clients.
        if ($user->hasRole('client')) {

            if (in_array($user->account_status, ['pending', 'inactive'])) {
                return back()->withErrors([
                    'email' => 'Votre compte n\'est pas encore activé. Veuillez attendre sa validation par un administrateur.',
                ]);
            }
        }

        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Impossible d\'authentifier l\'utilisateur.',
            ]);
        }
        return redirect()->route('dashboard');

    
    }
    /**
     * Destroy an authenticated session.
     */
   public function destroy(Request $request): RedirectResponse
   {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect(config('app.front_office_url'));
   }
}
