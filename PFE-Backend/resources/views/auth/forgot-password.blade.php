<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Mot de passe oublié</h1>
        </div>

        <div class="mb-4 text-sm text-gray-600">
            Pas de problème. Indiquez votre adresse email et nous vous enverrons un lien de réinitialisation de mot de passe.
        </div>

        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="submit-btn">
                    Envoyer le lien de réinitialisation
                </button>
            </div>
        </form>
    </div>
</body>
</html>
