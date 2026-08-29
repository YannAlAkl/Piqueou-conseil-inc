<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $newsletter->title }}</title>
</head>
<body style="margin:0; padding:0; background-color:#0d1117;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d1117;">
        <tr>
            <td align="center" style="padding:24px 12px;">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px; width:100%; background-color:#161b22; border:1px solid #1f6feb; border-radius:16px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#0d1117; padding:28px 32px 20px 32px; border-bottom:1px solid #21262d;">
                            <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:11px; letter-spacing:2px; color:#2dd4ff; text-transform:uppercase;">
                                🔒 PIQUÉOU Conseil Inc. &nbsp;·&nbsp; Sécurité &amp; Actualités
                            </p>
                            <h1 style="margin:10px 0 4px 0; font-family:Arial, Helvetica, sans-serif; font-size:30px; line-height:1.15; font-weight:bold; color:#2dd4ff;">
                                {{ $newsletter->nomCategorie() }}
                            </h1>
                            <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#8b949e;">
                                Votre actualité de la semaine
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:28px 32px 0 32px;">
                            <p style="margin:0 0 16px 0; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#e6edf3;">
                                Bonjour <strong style="color:#ffffff;">{{ $client->first_name }}</strong>,
                            </p>
                            <p style="margin:0 0 24px 0; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:1.6; color:#c9d1d9;">
                                Voici votre actualité de la semaine sur
                                <strong style="color:#2dd4ff;">{{ $newsletter->nomCategorie() }}</strong>.
                            </p>
                        </td>
                    </tr>

                    @if ($newsletter->image)
                        <tr>
                            <td style="padding:0 32px 24px 32px;">
                                <img src="{{ $message->embed(storage_path('app/public/' . $newsletter->image)) }}"
                                    alt="" width="536"
                                    style="display:block; border:0; width:100%; max-width:536px; height:auto; border-radius:8px;">
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding:0 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="background-color:#0d1117; border-left:4px solid #2dd4ff; border-radius:8px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <h2 style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:20px; line-height:1.3; font-weight:bold; color:#ffffff;">
                                            {{ $newsletter->title }}
                                        </h2>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:22px 32px 8px 32px;">
                            <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:1.7; color:#c9d1d9;">
                                {{ $newsletter->content }}
                            </p>
                        </td>
                    </tr>

                    @if ($newsletter->source_url)
                        <tr>
                            <td align="center" style="padding:28px 32px 8px 32px;">
                                <table role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" bgcolor="#2dd4ff" style="border-radius:8px;">
                                            <a href="{{ $newsletter->source_url }}"
                                                style="display:inline-block; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:bold; color:#0d1117; text-decoration:none; padding:14px 32px; border-radius:8px;">
                                                Lire l'article original &nbsp;→
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding:24px 32px 28px 32px;">
                            <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#e6edf3;">
                                L'équipe <strong style="color:#2dd4ff;">PIQUÉOU Conseil Inc.</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color:#0d1117; padding:20px 32px; border-top:1px solid #21262d;">
                            <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:1.6; color:#6e7681;">
                                Pour ne plus recevoir ces emails, décochez la newsletter dans
                                <a href="{{ route('profile.edit') }}" style="color:#2dd4ff; text-decoration:underline;">votre profil</a>.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
