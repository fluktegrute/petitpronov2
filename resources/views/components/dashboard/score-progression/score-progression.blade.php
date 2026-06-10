@php
    $data = $this->chartData;
    $scores = $data['scores'];
    $labels = $data['labels'];
    $statuses = $data['statuses'];
    $n = count($scores);
    $hasData = $n > 1;

    if ($hasData) {
        $minScore = min($scores);
        $maxScore = max($scores);
        $range = max($maxScore - $minScore, 1);
        $finalScore = end($scores);

        $W = 560; $H = 140;
        $padL = 42; $padR = 14; $padT = 14; $padB = 28;
        $gW = $W - $padL - $padR;
        $gH = $H - $padT - $padB;
        $baseY = $padT + $gH;

        $dots = [];
        foreach ($scores as $i => $s) {
            $x = $padL + ($n > 1 ? ($i / ($n - 1)) : 0.5) * $gW;
            $y = ($padT + $gH) - (($s - $minScore) / $range) * $gH;
            $dots[] = ['x' => round($x, 2), 'y' => round($y, 2), 'score' => $s, 'label' => $labels[$i], 'status' => $statuses[$i]];
        }

        $polylineCoords = implode(' ', array_map(fn($d) => "{$d['x']},{$d['y']}", $dots));
        $areaCoords = $polylineCoords . " {$dots[$n-1]['x']},$baseY {$dots[0]['x']},$baseY";

        // Labels X (max 7 points)
        $maxLabels = 7;
        if ($n <= $maxLabels) {
            $labelIdx = range(0, $n - 1);
        } else {
            $labelIdx = [0];
            $step = ($n - 1) / ($maxLabels - 1);
            for ($i = 1; $i < $maxLabels - 1; $i++) {
                $labelIdx[] = (int) round($i * $step);
            }
            $labelIdx[] = $n - 1;
            $labelIdx = array_unique($labelIdx);
        }

        // Ligne zéro
        $zeroY = null;
        if ($minScore < 0 && $maxScore > 0) {
            $zeroY = ($padT + $gH) - ((0 - $minScore) / $range) * $gH;
        }

        // Labels Y (3 niveaux)
        $yLevels = [
            ['val' => $minScore, 'y' => $padT + $gH],
            ['val' => (int) round($minScore + $range / 2), 'y' => $padT + $gH / 2],
            ['val' => $maxScore, 'y' => $padT],
        ];
    }
@endphp

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col transition-all hover:shadow-md md:col-span-2">

    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
        <h3 class="font-black text-slate-800 flex items-center gap-2">
            <span class="text-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </span>
            Progression des points
        </h3>
        @if($hasData)
            <span class="text-sm font-black {{ $finalScore >= 0 ? 'text-indigo-600' : 'text-red-500' }}">
                {{ $finalScore >= 0 ? '+' : '' }}{{ $finalScore }} pts
            </span>
        @endif
    </div>

    <div class="p-4 sm:p-6 flex-1 flex items-center justify-center">
        @if(!$hasData)
            <div class="text-center py-8">
                <span class="text-4xl block mb-3 opacity-40">📊</span>
                <p class="text-sm font-bold text-slate-600">Aucune donnée pour l'instant</p>
                <p class="text-xs text-slate-400 mt-1">Ta courbe apparaîtra après ton premier match joué.</p>
            </div>
        @else
            <div class="w-full overflow-x-auto">
                <svg viewBox="0 0 {{ $W }} {{ $H }}" class="w-full" style="min-width: 280px;">
                    <defs>
                        <linearGradient id="progressGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#6366f1" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#6366f1" stop-opacity="0.02"/>
                        </linearGradient>
                    </defs>

                    {{-- Lignes de grille horizontales --}}
                    @foreach($yLevels as $level)
                        <line x1="{{ $padL }}" y1="{{ $level['y'] }}" x2="{{ $W - $padR }}" y2="{{ $level['y'] }}"
                              stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4,4"/>
                        <text x="{{ $padL - 5 }}" y="{{ $level['y'] + 4 }}" text-anchor="end"
                              font-size="9" fill="#94a3b8" font-family="Inter, sans-serif" font-weight="600">
                            {{ $level['val'] }}
                        </text>
                    @endforeach

                    {{-- Ligne zéro (si scores négatifs et positifs) --}}
                    @if($zeroY !== null)
                        <line x1="{{ $padL }}" y1="{{ $zeroY }}" x2="{{ $W - $padR }}" y2="{{ $zeroY }}"
                              stroke="#94a3b8" stroke-width="1.5"/>
                    @endif

                    {{-- Remplissage sous la courbe --}}
                    <polygon points="{{ $areaCoords }}" fill="url(#progressGrad)"/>

                    {{-- Courbe principale --}}
                    <polyline points="{{ $polylineCoords }}"
                              fill="none" stroke="#6366f1" stroke-width="2.5"
                              stroke-linejoin="round" stroke-linecap="round"/>

                    {{-- Points de données --}}
                    @foreach($dots as $i => $dot)
                        @if($i > 0)
                            @php
                                $dotColor = match($dot['status']) {
                                    'exact'   => '#10b981',
                                    'trend'   => '#6366f1',
                                    'lost'    => '#94a3b8',
                                    'cheated' => '#ef4444',
                                    default   => '#6366f1',
                                };
                            @endphp
                            <circle cx="{{ $dot['x'] }}" cy="{{ $dot['y'] }}" r="4"
                                    fill="{{ $dotColor }}" stroke="white" stroke-width="1.5">
                                <title>{{ $dot['label'] }} — {{ $dot['score'] >= 0 ? '+' : '' }}{{ $dot['score'] }} pts</title>
                            </circle>
                        @else
                            <circle cx="{{ $dot['x'] }}" cy="{{ $dot['y'] }}" r="3"
                                    fill="#cbd5e1" stroke="white" stroke-width="1.5"/>
                        @endif
                    @endforeach

                    {{-- Labels axe X --}}
                    @foreach($labelIdx as $idx)
                        <text x="{{ $dots[$idx]['x'] }}" y="{{ $H - 6 }}" text-anchor="middle"
                              font-size="9" fill="#94a3b8" font-family="Inter, sans-serif" font-weight="600">
                            {{ $dots[$idx]['label'] }}
                        </text>
                    @endforeach
                </svg>

                {{-- Légende --}}
                <div class="flex items-center justify-center gap-4 mt-1">
                    <span class="flex items-center gap-1 text-[10px] font-semibold text-slate-500">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Exact
                    </span>
                    <span class="flex items-center gap-1 text-[10px] font-semibold text-slate-500">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Tendance
                    </span>
                    <span class="flex items-center gap-1 text-[10px] font-semibold text-slate-500">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-slate-400"></span> Raté
                    </span>
                </div>
            </div>
        @endif
    </div>

</div>
