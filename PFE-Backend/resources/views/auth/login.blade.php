<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - PIQUÉOU Conseil Inc.</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}"
</head>
<body>
    <div class="login-box">
        <img src="{{ asset('images/logo.png')  }}" alt="piqueou conseil inc" >
        <h1>Connexion</h1>

@auth
            <!-- Déconnexion -->
            <div class="logout-box">
                <p class="status-message">Vous êtes connecté(e) en tant que {{ auth()->user()->email }}.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-login">Se déconnecter</button>
                </form>
            </div>
        @else
            <!-- Statut de session -->
            <div class="status-message">{{ session('status') }}</div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Adresse email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    <div class="error-message">
                        @foreach ($errors->get('email') ?? [] as $message)
                            {{ $message }}<br>
                        @endforeach
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    <div class="error-message">
                        @foreach ($errors->get('password') ?? [] as $message)
                            {{ $message }}<br>
                        @endforeach
                    </div>
                </div>

                 <!-- Se souvenir de moi -->
               <div class="form-actions">
                    <a href="http://localhost:8080/">Retour à l'accueil</a>

                </div>

                <div class="form-actions">
                    <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                    <button type="submit" class="btn-login">Se connecter</button>
                </div>
            </form>
        @endauth
    </div>
</body>
</html>
