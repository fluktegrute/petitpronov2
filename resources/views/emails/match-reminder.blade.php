<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel pronos</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:32px 16px;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:16px 16px 0 0;padding:32px 32px 24px;text-align:center;">
                        <p style="margin:0 0 8px;font-size:36px;">⚽</p>
                        <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:800;letter-spacing:-0.3px;">
                            Rappel : matchs sans pronos
                        </h1>
                        <p style="margin:8px 0 0;color:#c7d2fe;font-size:14px;">
                            Ces matchs débutent dans environ 12 heures
                        </p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="background:#ffffff;padding:28px 32px;">

                        <p style="margin:0 0 20px;color:#374151;font-size:15px;">
                            Salut <strong>{{ $user->name }}</strong> 👋
                        </p>
                        <p style="margin:0 0 24px;color:#6b7280;font-size:14px;line-height:1.6;">
                            Tu n'as pas encore posé de prono pour {{ $games->count() === 1 ? 'ce match' : 'ces matchs' }}.
                            Il te reste encore un peu de temps !
                        </p>

                        @foreach ($games as $game)
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                                <tr>
                                    <td style="background:#f9fafb;padding:14px 18px;">
                                        <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;">
                                            {{ $game->stageFr }}{{ $game->group ? ' · ' . $game->groupFr : '' }}
                                        </p>
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="text-align:center;width:40%;">
                                                    <p style="margin:0;font-size:15px;font-weight:700;color:#111827;">
                                                        {{ $game->homeTeam->nameFr }}
                                                    </p>
                                                </td>
                                                <td style="text-align:center;width:20%;">
                                                    <span style="display:inline-block;background:#4f46e5;color:#ffffff;font-size:12px;font-weight:800;padding:4px 10px;border-radius:20px;">
                                                        VS
                                                    </span>
                                                </td>
                                                <td style="text-align:center;width:40%;">
                                                    <p style="margin:0;font-size:15px;font-weight:700;color:#111827;">
                                                        {{ $game->awayTeam->nameFr }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <p style="margin:8px 0 0;font-size:12px;color:#9ca3af;text-align:center;">
                                            🕐 {{ ucfirst($game->kickoff_at->translatedFormat('l d F Y \à H\hi')) }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        @endforeach

                        <div style="margin-top:28px;text-align:center;">
                            <a href="{{ url('/matches') }}"
                               style="display:inline-block;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:14px 32px;border-radius:10px;">
                                Poser mes pronos →
                            </a>
                        </div>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f9fafb;border-radius:0 0 16px 16px;padding:20px 32px;text-align:center;border-top:1px solid #e5e7eb;">
                        <p style="margin:0 0 6px;font-size:12px;color:#9ca3af;">
                            Tu reçois cet e-mail car tu as activé les rappels de matchs dans ton profil.
                        </p>
                        <p style="margin:0;font-size:12px;color:#9ca3af;">
                            <a href="{{ url('/profile') }}" style="color:#6b7280;text-decoration:underline;">Gérer mes préférences</a>
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
