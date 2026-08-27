<?php

namespace App\Http\Controllers\Auth;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerifyEmailController extends BaseController
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            $this->envoyerPremiereNewsletter($request->user());
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }

    private function envoyerPremiereNewsletter($utilisateur): void
    {
        if (! $utilisateur->wants_newsletter || ! $utilisateur->newsletter_category) {
            return;
        }

        $newsletter = Newsletter::where('category', $utilisateur->newsletter_category)
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->first();

        if (! $newsletter) {
            return;
        }

        try {
            Mail::to($utilisateur->email)->send(new NewsletterMail($newsletter, $utilisateur));
        } catch (\Exception $e) {
            Log::error('Echec de la premiere newsletter vers ' . $utilisateur->email . ' - ' . $e->getMessage());
        }
    }
}
