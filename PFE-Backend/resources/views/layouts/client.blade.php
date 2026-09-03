<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espace client') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
</head>
<body class="client-page">

    <nav class="client-nav">
        <div class="client-container">
            <div class="client-nav-row">

                <div class="flex items-center gap-8">
                    <a href="{{ route('client.dashboard') }}" class="client-brand">PIQUÉOU Conseil Inc.</a>

                    <div class="client-nav-links">
                        <a href="{{ route('client.dashboard') }}"
                           class="client-nav-link {{ request()->routeIs('client.dashboard') ? 'client-nav-link-active' : '' }}">
                            Accueil
                        </a>
                        <a href="{{ route('client.questionnaire.index') }}"
                           class="client-nav-link {{ request()->routeIs('client.questionnaire.*') ? 'client-nav-link-active' : '' }}">
                            Mes questionnaires
                        </a>
                    </div>
                </div>

                <div class="client-user">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="client-logout">Déconnexion</button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <header class="client-header">
        <div class="client-container">
            <h1 class="client-title">@yield('title', 'Espace client')</h1>
            <p class="client-subtitle">@yield('subtitle')</p>
        </div>
    </header>

    <main class="client-container">
        <div class="client-content">

            @if (session('success'))
                <div class="client-alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="client-alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')

        </div>
    </main>

    <div class="client-modal" id="modal-confirmation">
        <div class="client-modal-box">
            <h3 class="client-modal-title" id="modal-titre"></h3>
            <p class="client-modal-text" id="modal-texte"></p>

            <div class="client-modal-actions">
                <button type="button" class="client-btn client-btn-gray" onclick="fermerModal()">Annuler</button>
                <button type="button" class="client-btn client-btn-blue" onclick="validerModal()">Confirmer</button>
            </div>
        </div>
    </div>

    <script>
        var formulaireEnAttente = null;

        function ouvrirModal(formulaire, titre, texte) {
            formulaireEnAttente = formulaire;

            document.getElementById('modal-titre').innerText = titre;
            document.getElementById('modal-texte').innerText = texte;
            document.getElementById('modal-confirmation').style.display = 'flex';
        }

        function fermerModal() {
            document.getElementById('modal-confirmation').style.display = 'none';
            formulaireEnAttente = null;
        }

       function validerModal() {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

    document.getElementById('action-choisie').value = 'envoyer';
    formulaireEnAttente.submit();
}
    </script>
    @vite(['resources/js/app.js'])
</body>
</html>

