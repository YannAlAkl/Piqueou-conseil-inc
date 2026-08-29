<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>@yield('titre')</title>
</head>
<body style="background-color:#f6f9f9; font-family:Arial, Helvetica, sans-serif; color:#444d4d; padding:24px;">

    <div style="max-width:600px; margin:0 auto; background-color:#ffffff; border-top:4px solid #22c55e; border-radius:10px; padding:28px;">

        <img src="{{ $message->embed(public_path('images/logo.png')) }}"
             alt="PIQUÉOU Conseil Inc." width="160"
             style="display:block; border:0; margin:0 0 6px 0;">
        <p style="color:#018880; font-size:13px; margin:0 0 20px 0;">Cybersécurité et conformité</p>

        @yield('contenu')

        <hr style="border:0; border-top:1px solid #e2e8f0; margin:24px 0 14px 0;">

        <p style="font-size:12px; color:#94a3b8; margin:0;">
            Email envoyé automatiquement par le portail PIQUÉOU Conseil Inc.
        </p>
    </div>

</body>
</html>
