<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
</head>
<body class="admin-page">

    <nav class="admin-nav">
        <div class="admin-container">
            <div class="admin-nav-row">

                <div class="flex items-center gap-8">
                    <a href="{{ route('admin.dashboard') }}" class="admin-brand">PIQUÉOU Admin</a>

                    <div class="admin-nav-links">

                        <a href="{{ route('admin.dashboard') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'admin-nav-link-active' : '' }}">
                            Tableau de bord
                        </a>

                        <div class="admin-dropdown">
                            <span class="admin-nav-link {{ request()->routeIs('admin.admin.*') || request()->routeIs('admin.analyst.*') || request()->routeIs('admin.client.*') ? 'admin-nav-link-active' : '' }}">
                                Utilisateurs &#9662;
                            </span>

                            <div class="admin-dropdown-menu">
                                <a href="{{ route('admin.admin.index') }}"
                                   class="admin-dropdown-item {{ request()->routeIs('admin.admin.*') ? 'admin-dropdown-item-active' : '' }}">
                                    Administrateurs
                                </a>
                                <a href="{{ route('admin.analyst.index') }}"
                                   class="admin-dropdown-item {{ request()->routeIs('admin.analyst.*') ? 'admin-dropdown-item-active' : '' }}">
                                    Analystes
                                </a>
                                <a href="{{ route('admin.client.index') }}"
                                   class="admin-dropdown-item {{ request()->routeIs('admin.client.*') ? 'admin-dropdown-item-active' : '' }}">
                                    Clients
                                </a>
                            </div>
                        </div>

                        <div class="admin-dropdown">
                            <span class="admin-nav-link {{ request()->routeIs('admin.submission.*') || request()->routeIs('admin.questionnaire.*') ? 'admin-nav-link-active' : '' }}">
                                Questionnaires &#9662;
                            </span>

                            <div class="admin-dropdown-menu">
                                <a href="{{ route('admin.submission.index') }}"
                                   class="admin-dropdown-item {{ request()->routeIs('admin.submission.*') ? 'admin-dropdown-item-active' : '' }}">
                                    Questionnaires envoyés
                                </a>
                                <a href="{{ route('admin.questionnaire.index') }}"
                                   class="admin-dropdown-item {{ request()->routeIs('admin.questionnaire.*') ? 'admin-dropdown-item-active' : '' }}">
                                    Questionnaires analysés
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('admin.newsletter.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.newsletter.*') ? 'admin-nav-link-active' : '' }}">
                            Newsletters
                        </a>

                    </div>

                <div class="admin-user">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline-flex items-center m-0">
                        @csrf
                        <button type="submit" class="admin-logout">Déconnexion</button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <header class="admin-header">
        <div class="admin-container">
            <div class="admin-header-row">
                <div>
                    <h1 class="admin-title">@yield('title', 'Administration')</h1>
                    <p class="admin-subtitle">@yield('subtitle')</p>
                </div>
                <div>
                    @yield('actions')
                </div>
            </div>
        </div>
    </header>

    <main class="admin-container">
        <div class="admin-content">

            @if (session('success'))
                <div class="admin-alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="admin-alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>
    </main>

    <div class="admin-modal" id="modal-confirmation">
        <div class="admin-modal-box">
            <h3 class="admin-modal-title" id="modal-titre"></h3>
            <p class="admin-modal-text" id="modal-texte"></p>

            <div class="admin-modal-actions">
                <button type="button" class="admin-btn admin-btn-gray" onclick="fermerModal()">Annuler</button>
                <button type="button" class="admin-btn" id="modal-bouton" onclick="validerModal()">Confirmer</button>
            </div>
        </div>
    </div>

    <script>
        var formulaireEnAttente = null;

        function ouvrirModal(formulaire, titre, texte, bouton, couleur) {
            formulaireEnAttente = formulaire;

            document.getElementById('modal-titre').innerText = titre;
            document.getElementById('modal-texte').innerText = texte;
            document.getElementById('modal-bouton').innerText = bouton;
            document.getElementById('modal-bouton').className = 'admin-btn ' + couleur;
            document.getElementById('modal-confirmation').style.display = 'flex';

            return false;
        }

        function fermerModal() {
            document.getElementById('modal-confirmation').style.display = 'none';
            formulaireEnAttente = null;
        }

        function validerModal() {
            formulaireEnAttente.submit();
        }
    </script>

    @vite(['resources/js/app.js'])
</body>
</html>


