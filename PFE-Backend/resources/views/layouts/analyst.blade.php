<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espace analyste') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/analyst.css') }}">
</head>
<body class="analyst-page">

    <nav class="analyst-nav">
        <div class="analyst-container">
            <div class="analyst-nav-row">

                <div class="flex items-center gap-8">
                    <a href="{{ route('analyst.dashboard') }}" class="analyst-brand">Piqueou Conseil</a>

                    <div class="analyst-nav-links">
                        <a href="{{ route('analyst.dashboard') }}"
                           class="analyst-nav-link {{ request()->routeIs('analyst.dashboard') ? 'analyst-nav-link-active' : '' }}">
                            Accueil
                        </a>
                        <a href="{{ route('analyst.questionnaire.index') }}"
                           class="analyst-nav-link {{ request()->routeIs('analyst.questionnaire.*') ? 'analyst-nav-link-active' : '' }}">
                            Dossiers à analyser
                        </a>
                    </div>
                </div>

                <div class="analyst-user">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="analyst-logout">Déconnexion</button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <header class="analyst-header">
        <div class="analyst-container">
            <h1 class="analyst-title">@yield('title', 'Espace analyste')</h1>
            <p class="analyst-subtitle">@yield('subtitle')</p>
        </div>
    </header>

    <main class="analyst-container">
        <div class="analyst-content">

            @if (session('success'))
                <div class="analyst-alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="analyst-alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')

        </div>
    </main>

</body>
</html>
