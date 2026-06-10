<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récap du jour</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:32px 16px;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#0f172a,#1e3a5f);border-radius:16px 16px 0 0;padding:32px 32px 24px;text-align:center;">
                        <p style="margin:0 0 8px;font-size:36px;">📊</p>
                        <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:800;letter-spacing:-0.3px;">
                            Récap du jour
                        </h1>
                        <p style="margin:8px 0 0;color:#94a3b8;font-size:14px;">
                            {{ \Carbon\Carbon::today()->locale('fr')->translatedFormat('l d F') }}
                        </p>
                    </td>
                </tr>

                {{-- Ranking banner --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:20px 32px;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="text-align:center;width:33%;">
                                    <p style="margin:0;color:#c7d2fe;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Classement</p>
                                    <p style="margin:4px 0 0;color:#ffffff;font-size:26px;font-weight:800;">
                                        #{{ $currentRank }}
                                        <span style="font-size:13px;font-weight:500;color:#c7d2fe;">/ {{ $totalUsers }}</span>
                                    </p>
                                </td>
                                <td style="text-align:center;width:33%;border-left:1px solid rgba(255,255,255,0.2);border-right:1px solid rgba(255,255,255,0.2);">
                                    <p style="margin:0;color:#c7d2fe;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Points totaux</p>
                                    <p style="margin:4px 0 0;color:#ffffff;font-size:26px;font-weight:800;">
                                        {{ $user->total_points }}
                                    </p>
                                </td>
                                <td style="text-align:center;width:33%;">
                                    <p style="margin:0;color:#c7d2fe;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Pts aujourd'hui</p>
                                    <p style="margin:4px 0 0;font-size:26px;font-weight:800;{{ $pointsEarnedToday >= 0 ? 'color:#86efac;' : 'color:#fca5a5;' }}">
                                        {{ $pointsEarnedToday > 0 ? '+' : '' }}{{ $pointsEarnedToday }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- Match results --}}
                <tr>
                    <td style="background:#ffffff;padding:28px 32px;">

                        <p style="margin:0 0 20px;color:#374151;font-size:15px;">
                            Salut <strong>{{ $user->name }}</strong> 👋
                        </p>
                        <p style="margin:0 0 20px;color:#6b7280;font-size:14px;">
                            Voici les résultats des matchs d'aujourd'hui et tes pronos :
                        </p>

                        @foreach ($finishedGames as $game)
                            @php
                                $prediction = $userPredictions->get($game->id);
                                $statusColors = [
                                    'exact'    => ['bg' => '#dcfce7', 'border' => '#16a34a', 'text' => '#15803d', 'label' => 'Exact !'],
                                    'trend'    => ['bg' => '#fef9c3', 'border' => '#ca8a04', 'text' => '#a16207', 'label' => 'Tendance'],
                                    'lost'     => ['bg' => '#fee2e2', 'border' => '#dc2626', 'text' => '#b91c1c', 'label' => 'Raté'],
                                    'cheating' => ['bg' => '#fee2e2', 'border' => '#dc2626', 'text' => '#b91c1c', 'label' => 'Triche'],
                                    'placed'   => ['bg' => '#f1f5f9', 'border' => '#94a3b8', 'text' => '#64748b', 'label' => 'En attente'],
                                ];
                                $style = $prediction ? ($statusColors[$prediction->status] ?? $statusColors['placed']) : null;
                            @endphp

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                                <tr>
                                    <td style="background:#f9fafb;padding:14px 18px;">
                                        <p style="margin:0 0 8px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;">
                                            {{ $game->stageFr }}{{ $game->group ? ' · ' . $game->groupFr : '' }}
                                        </p>
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="text-align:center;width:38%;">
                                                    <p style="margin:0;font-size:14px;font-weight:700;color:#111827;">{{ $game->homeTeam->nameFr }}</p>
                                                </td>
                                                <td style="text-align:center;width:24%;">
                                                    <span style="display:inline-block;background:#111827;color:#ffffff;font-size:16px;font-weight:800;padding:4px 12px;border-radius:8px;">
                                                        {{ $game->home_score }} – {{ $game->away_score }}
                                                    </span>
                                                </td>
                                                <td style="text-align:center;width:38%;">
                                                    <p style="margin:0;font-size:14px;font-weight:700;color:#111827;">{{ $game->awayTeam->nameFr }}</p>
                                                </td>
                                            </tr>
                                        </table>

                                        @if ($prediction)
                                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;background:{{ $style['bg'] }};border:1px solid {{ $style['border'] }};border-radius:8px;">
                                                <tr>
                                                    <td style="padding:8px 12px;">
                                                        <table width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td>
                                                                    <span style="font-size:12px;font-weight:700;color:{{ $style['text'] }};">{{ $style['label'] }}</span>
                                                                    <span style="font-size:12px;color:{{ $style['text'] }};margin-left:6px;">
                                                                        Ton prono : {{ $prediction->home_score }} – {{ $prediction->away_score }}
                                                                    </span>
                                                                </td>
                                                                <td style="text-align:right;">
                                                                    <span style="font-size:13px;font-weight:800;color:{{ $style['text'] }};">
                                                                        {{ $prediction->points > 0 ? '+' : '' }}{{ $prediction->points }} pts
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        @else
                                            <p style="margin:8px 0 0;font-size:12px;color:#9ca3af;text-align:center;font-style:italic;">
                                                Aucun prono posé pour ce match
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @endforeach

                        <div style="margin-top:28px;text-align:center;">
                            <a href="{{ url('/standings') }}"
                               style="display:inline-block;background:linear-gradient(135deg,#0f172a,#1e3a5f);color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:14px 32px;border-radius:10px;">
                                Voir le classement complet →
                            </a>
                        </div>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f9fafb;border-radius:0 0 16px 16px;padding:20px 32px;text-align:center;border-top:1px solid #e5e7eb;">
                        <p style="margin:0 0 6px;font-size:12px;color:#9ca3af;">
                            Tu reçois cet e-mail car tu as activé le récapitulatif quotidien dans ton profil.
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
