<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);

    // Redirection personnalisée pour les invités (non connectés)
        $middleware->redirectGuestsTo(function (Request $request) {
            // Si l'invité tente d'accéder à l'URL de déconnexion
            if ($request->is('logout')) {
                return 'http://localhost:8000/login'; // Remplacez par l'URL de votre vitrine statique
            }

            // Comportement par défaut pour le reste de l'application
            return route('login');
        });
    })


    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
