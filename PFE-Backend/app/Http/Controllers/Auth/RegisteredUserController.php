<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ClientRegisteredMail;
use App\Mail\NewClientAdminMail;
use App\Models\User;
use App\Rules\ProfessionalEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class, new ProfessionalEmail()],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^(\+?\d{1,3}[- ]?)?\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}(([- ]?(ext|x|ext\.|#)[- ]?\d{1,5})?)$/i'
            ],
            'company_name' => ['nullable', 'string', 'max:255'],
            'wants_newsletter' => ['nullable', 'boolean'],
            'newsletter_category' => ['nullable', 'in:cmmc,loi25,iso27001'],
        ], [
            'phone.regex' => 'Le numéro de téléphone doit inclure un code régional valide (ex: (514) 555-0123 ou 514-555-0123).',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'account_status' => 'pending',
            'wants_newsletter' => $request->boolean('wants_newsletter'),
            'newsletter_category' => $request->newsletter_category,
        ]);

        $user->assignRole('client');

        $lienAdmin = route('login');

        $message = 'Votre compte a bien été créé. Un email a été envoyé à l\'administrateur. Il doit être activé par un administrateur avant de pouvoir vous connecter.';

        try {
            Mail::to($user->email)->send(new ClientRegisteredMail($user));

            Mail::to(config('services.admin_email'))
                ->send(new NewClientAdminMail($user, $lienAdmin));
        } catch (\Exception $e) {
            $message = 'Votre compte a bien été créé. Il doit être activé par un administrateur avant de pouvoir vous connecter.';
        }

        return redirect()->route('login')->with('status', $message);
    }
}
