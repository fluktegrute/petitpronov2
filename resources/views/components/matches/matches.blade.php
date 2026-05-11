<div class="max-w-4xl mx-auto p-4 sm:p-6 space-y-8">

    @if(!auth()->user()->winner_team_id)
        <div class="bg-amber-50 border-l-4 border-amber-500 rounded-r-xl p-4 shadow-sm flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <h3 class="text-amber-800 font-bold">Action requise</h3>
                <p class="text-amber-700 text-sm mt-1">
                    Tu n'as pas encore choisi ton poney ! 
                    <a href="{{ route('profile') }}" class="font-bold underline hover:text-amber-900">Va le faire sur ton profil</a> avant le début de la compétition.
                </p>
            </div>
        </div>
    @endif

    <div class="flex justify-center">
        <div class="bg-slate-200/50 p-1 rounded-xl inline-flex gap-1 shadow-inner">
            <button 
                class="px-6 py-2 rounded-lg transition-all text-sm {{ $tab == "upcoming" ? "bg-white text-indigo-700 font-bold shadow-sm" : "text-slate-500 font-medium hover:text-slate-700 hover:bg-slate-200" }}" 
                wire:click="$set('tab', 'upcoming')"
            >
                À venir
            </button>
            <button 
                class="px-6 py-2 rounded-lg transition-all text-sm {{ $tab == "upcoming" ? "text-slate-500 font-medium hover:text-slate-700 hover:bg-slate-200" : "bg-white text-indigo-700 font-bold shadow-sm" }}" 
                wire:click="$set('tab', 'past')"
            >
                Passés
            </button>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($this->games as $game)
            @php
                $gameIsStarted = now()->isAfter($game->kickoff_at);
                
                $prono = $predictions[$game->id] ?? []; 
                
                $hasProno = isset($prono['home_score']) && isset($prono['away_score']);
                
                $status = $prono['status'] ?? 'lost';
                $isBoosted = $prono['is_boosted'] ?? false;
                $points = $prono['points'] ?? 0;
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-shadow hover:shadow-md" wire:key="game_{{ $game->id }}">
                
                <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-2 text-sm">
                    <div class="flex items-center gap-2 text-slate-500 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $game->formattedDate }}
                    </div>
                    <div class="font-semibold text-slate-700 bg-slate-200/50 px-3 py-1 rounded-full text-xs">
                        {{ $game->stageFr }}{{ $game->stage == "GROUP_STAGE" ? " • {$game->groupFr}" : "" }}
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between max-w-lg mx-auto">
                        
                        <div class="flex flex-col items-center gap-3 w-1/3">
                            <img src="{{ asset($game->homeTeam->flag_path) }}" alt="{{ $game->homeTeam->name }}" class="h-9 sm:h-12 object-cover rounded shadow-sm border border-slate-100">
                            <div class="flex gap-1">
                                <span class="font-bold text-slate-900 text-center leading-tight">{{ $game->homeTeam->nameFr }}</span>
                                <span class="text-indigo-600 cursor-pointer" wire:click="openStatsModal({{ $game->homeTeam->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>
                                </span>
                            </div>
                            @if($tab === 'upcoming')
                                <input type="number" min="0" max="20" 
                                    wire:model.live.debounce="predictions.{{ $game->id }}.home_score"
                                    class="w-16 h-12 text-center text-2xl font-black text-indigo-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    {{ $gameIsStarted ? 'disabled' : '' }}>
                            @else 
                                <span class="w-16 h-12 pt-[0.4rem] text-center text-2xl font-black text-indigo-900 bg-slate-50 border border-slate-200 rounded-xl">
                                    {{ $prono['home_score'] ?? '-' }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-col items-center gap-4 w-1/3">
                            @if($tab === 'upcoming')
                                <span class="text-slate-400 font-black italic text-lg">VS</span>
                                <button class="text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1" wire:click="openH2hModal({{ $game->id }})">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Stats H2H
                                </button>
                            @else
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Score final</span>
                                    <div class="bg-slate-800 text-white font-black text-lg px-3 py-1 rounded shadow-inner">
                                        {{ $game->home_score ?? '-' }} - {{ $game->away_score ?? '-' }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col items-center gap-3 w-1/3">
                            <img src="{{ asset($game->awayTeam->flag_path) }}" alt="{{ $game->awayTeam->name }}" class="h-9 sm:h-12 object-cover rounded shadow-sm border border-slate-100">
                            <div class="flex gap-1">
                                <span class="font-bold text-slate-900 text-center leading-tight">{{ $game->awayTeam->nameFr }}</span>
                                <span class="text-indigo-600 cursor-pointer" wire:click="openStatsModal({{ $game->awayTeam->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>
                                </span>
                            </div>
                            @if($tab === 'upcoming')
                                <input type="number" min="0" max="20" 
                                    wire:model.live.debounce="predictions.{{ $game->id }}.away_score"
                                    class="w-16 h-12 text-center text-2xl font-black text-indigo-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                    {{ $gameIsStarted ? 'disabled' : '' }}>
                            @else 
                                <span class="w-16 h-12 pt-[0.4rem] text-center text-2xl font-black text-indigo-900 bg-slate-50 border border-slate-200 rounded-xl">
                                    {{ $prono['away_score'] ?? '-' }}
                                </span>
                            @endif
                        </div>

                    </div>

                    @if($tab === 'upcoming' && $game->odds_home)
                        <div class="max-w-md mx-auto mt-6 pt-5 border-t border-slate-100 flex justify-around items-center">
                            <div class="flex flex-col items-center gap-1" title="Cote Victoire {{ $game->homeTeam->nameFr }}">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">1</span>
                                <span class="text-xs font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg shadow-sm">
                                    {{ number_format($game->odds_home, 2, ',', ' ') }}
                                </span>
                            </div>
                            <div class="flex flex-col items-center gap-1" title="Cote Match Nul">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">NUL</span>
                                <span class="text-xs font-black text-slate-600 bg-slate-100 border border-slate-200 px-3 py-1 rounded-lg shadow-sm">
                                    {{ number_format($game->odds_draw, 2, ',', ' ') }}
                                </span>
                            </div>
                            <div class="flex flex-col items-center gap-1" title="Cote Victoire {{ $game->awayTeam->nameFr }}">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">2</span>
                                <span class="text-xs font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg shadow-sm">
                                    {{ number_format($game->odds_away, 2, ',', ' ') }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                @if($tab === 'upcoming')
                    <div class="bg-indigo-50/50 px-6 py-4 border-t border-indigo-50 flex justify-center">
                        <label class="inline-flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="checkbox" 
                                    wire:model.live.debounce="predictions.{{ $game->id }}.is_boosted"
                                    class="peer w-5 h-5 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer transition-all"
                                    {{ $gameIsStarted ? 'disabled' : '' }}>
                            </div>
                            <div class="flex flex-col text-sm font-semibold text-slate-700 group-hover:text-indigo-900 transition-colors">
                                <span class="text-center">Utiliser un booster sur ce match</span>
                                <span class="text-center text-indigo-600 font-bold text-xs ml-1">(il t'en reste {{ auth()->user()->boosts_remaining }})</span>
                            </div>
                        </label>
                    </div>
                @else
                    <div @class([
                        'px-6 py-3 border-t flex flex-col sm:flex-row justify-between items-center gap-3 transition-colors',
                        'bg-emerald-50 border-emerald-100' => $status === 'exact' && $hasProno,
                        'bg-blue-50 border-blue-100'       => $status === 'trend' && $hasProno,
                        'bg-slate-50 border-slate-100'     => ($status === 'lost' || $status === 'cheated' || !$hasProno),
                    ])>
                        <div class="flex items-center gap-2">
                            @if(!$hasProno)
                                <span class="font-bold text-slate-500">Aucun prono enregistré</span>
                            @elseif($status === 'exact')
                                <span class="text-emerald-500 text-lg">🎯</span>
                                <span class="font-black text-emerald-800 tracking-tight text-sm uppercase">Score Exact</span>
                            @elseif($status === 'trend')
                                <span class="text-blue-500 text-lg">📈</span>
                                <span class="font-black text-blue-800 tracking-tight text-sm uppercase">Bonne Tendance</span>
                            @else
                                <span class="text-slate-400 text-lg">❌</span>
                                <span class="font-black text-slate-500 tracking-tight text-sm uppercase">Prono Raté</span>
                            @endif

                            @if($isBoosted && $hasProno)
                                <span class="ml-3 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black bg-orange-100 text-orange-800 border border-orange-200">
                                    🚀 Boost
                                </span>
                            @endif
                        </div>
                        
                        <div class="font-black text-xl {{ $points > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                            + {{ $points }} pt{{ $points > 1 ? 's' : '' }}
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-2xl border border-slate-200 border-dashed">
                <p class="text-slate-500 font-medium">Aucun match à afficher pour le moment.</p>
            </div>
        @endforelse
    </div>
    @include('partials/modals/h2h')
    @include('partials/modals/team-stats')
</div>