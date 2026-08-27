<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Piqueou Consulting</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="form-container">
        <div class="form-header">
            <h2>Créer un compte </h2>
        </div>

        <form action="/register" method="POST">
            @csrf
            <div class="form-grid">

                <!-- first_name (NOT NULL) -->
                <div class="form-group">
                    <label for="first_name">Prénom *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                        maxlength="255">
                    <div class="error-message">
                        @foreach ($errors->get('first_name') ?? [] as $message)
                            {{ $message }}<br>
                        @endforeach
                    </div>
                </div>

                <!-- last_name (NULL) -->
                <div class="form-group">
                    <label for="last_name">Nom</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" maxlength="255">
                    <div class="error-message">
                        @foreach ($errors->get('last_name') ?? [] as $message)
                            {{ $message }}<br>
                        @endforeach
                    </div>
                </div>

                <!-- email (NOT NULL) -->
                <div class="form-group full-width">
                    <label for="email">Adresse e-mail *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="255">
                    <div class="error-message">
                        @foreach ($errors->get('email') ?? [] as $message)
                            {{ $message }}<br>
                        @endforeach
                    </div>
                </div>

                <!-- password (NOT NULL) -->
                <div class="form-group full-width">
                    <label for="password">Mot de passe *</label>
                    <input type="password" id="password" name="password" required maxlength="255">
                    <div class="error-message">
                        @foreach ($errors->get('password') ?? [] as $message)
                            {{ $message }}<br>
                        @endforeach
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="password_confirmation">Confirmer le mot de passe *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        maxlength="255">
                </div>

                <!-- company_name (NULL) -->
                <div class="form-group">
                    <label for="company_name">Nom de l'entreprise</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                        maxlength="255">
                </div>

                <!-- phone (NULL) -->

                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" maxlength="50">

                    @error('phone')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- wants_newsletter (tinyint - default 0) -->
                <div class="form-group full-width checkbox-group">
                    <input type="hidden" name="wants_newsletter" value="0">
                    <input type="checkbox" id="wants_newsletter" name="wants_newsletter" value="1">
                    <label for="wants_newsletter">S'abonner à la newsletter</label>
                </div>

                <!-- newsletter_category (NULL) -->
                <div class="form-group full-width" id="newsletter_category_container" style="display: none;">
                    <label for="newsletter_category">Catégorie de newsletter</label>
                    <select id="newsletter_category" name="newsletter_category">
                        <option value="" selected>-- Choisir une catégorie --</option>
                        <option value="cmmc">CMMC</option>
                        <option value="loi25">Loi 25</option>
                        <option value="iso27001">ISO 27001</option>
                    </select>
                </div>



                <!-- Bouton de soumission -->
                <div class="form-group full-width">
                    <button type="submit" class="submit-btn">S'inscrire</button>
                </div>
            </div>
        </form>
    </div>
    <script>

        const newsletterCheckbox =
            document.getElementById('wants_newsletter');

        const newsletterCategoryContainer =
            document.getElementById('newsletter_category_container');

        const newsletterCategory =
            document.getElementById('newsletter_category');


        newsletterCheckbox.addEventListener('change', function () {

            if (newsletterCheckbox.checked) {

                newsletterCategoryContainer.style.display = 'block';

            } else {

                newsletterCategoryContainer.style.display = 'none';

                newsletterCategory.value = '';

            }

        });

    </script>
</body>

</html>