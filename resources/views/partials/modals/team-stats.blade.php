<div 
    x-data="{ statsModalOpen: @entangle('statsModalOpened') }"
    x-show="statsModalOpen"
    x-cloak
    class="relative z-50"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <div 
        x-show="statsModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100 backdrop-blur-sm"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 backdrop-blur-sm"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
    ></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <div 
                x-show="statsModalOpen"
                @click.outside="statsModalOpen = false"
                @keydown.escape.window="statsModalOpen = false"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8  w-2/3 border border-slate-100"
            >

                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-4" id="modal-title">
                        <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        
                        <span class="truncate">Les 10 derniers matches</span>
                        
                        @if($this->currentStatsTeam)
                            <img src="{{ asset($this->currentStatsTeam->flag_path) }}" class="w-6 h-4 sm:w-7 sm:h-5 object-cover rounded-sm border border-slate-300 shadow-sm shrink-0">
                            <span class="text-indigo-600 truncate">{{ $this->currentStatsTeam->nameFr }}</span>
                        @else
                            <span>l'équipe</span>
                        @endif
                    </h3>
                    <button @click="statsModalOpen = false" type="button" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200 p-1 rounded-lg transition-colors cursor-pointer touch-manipulation">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-6 w-6 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="bg-white">
                    @if($currentStats && $currentStats->isNotEmpty())
                        <ul class="divide-y divide-slate-200">
                            @foreach($currentStats as $history)
                                <li class="px-6 py-4 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4" wire:key="history_{{ $history->id }}">
                                    @php 
                                        $isHome = $history->home_team_id === $currentStatsTeamId; 
                                        $gameTrend = (int) $history->home_score <=> (int) $history->away_score;
                                        
                                        if ($gameTrend === 0) {
                                            $status = 'draw';
                                            $badgeClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                            $badgeText = 'N'; // Nul
                                        } elseif (($isHome && $gameTrend === 1) || (!$isHome && $gameTrend === -1)) {
                                            $status = 'win';
                                            $badgeClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                            $badgeText = 'V'; // Victoire
                                        } else {
                                            $status = 'loss';
                                            $badgeClass = 'bg-red-100 text-red-700 border-red-200';
                                            $badgeText = 'D'; // Défaite
                                        }

                                        $opponent = $isHome ? $history->awayTeam : $history->homeTeam;
                                        $myScore = $isHome ? $history->home_score : $history->away_score;
                                        $opponentScore = $isHome ? $history->away_score : $history->home_score;
                                    @endphp
                                    
                                    <div class="text-sm w-full sm:w-1/3 border-l-4 pl-3 
                                        {{ $status === 'win' ? 'border-emerald-400' : ($status === 'loss' ? 'border-red-400' : 'border-slate-300') }}">
                                        <div class="font-semibold text-slate-700">
                                            {{ \Carbon\Carbon::parse($history->date)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-slate-500 truncate" title="{{ $history->tournament }}">
                                            {{ $history->tournamentFr ?? $history->tournament }}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-start sm:justify-end gap-3 w-full sm:w-2/3">
                                        
                                        <span class="w-8 h-8 flex items-center justify-center rounded-full font-black text-sm border shadow-sm shrink-0 {{ $badgeClass }}">
                                            {{ $badgeText }}
                                        </span>

                                        <div class="font-black text-slate-800 text-base tracking-widest bg-white border border-slate-200 px-3 py-1 rounded-lg shadow-sm shrink-0">
                                            {{ $myScore }} - {{ $opponentScore }}
                                        </div>

                                        <div class="flex items-center gap-2 min-w-[140px] justify-start ml-2">
                                            <span class="text-sm font-semibold text-slate-600 truncate">
                                                {{ $opponent ? $opponent->nameFr : ($isHome ? __("api.team.{$history->away_team_name}") : __("api.team.{$history->home_team_name}")) }}
                                            </span>
                                            @if($opponent && $opponent->flag_path)
                                                <img src="{{ asset($opponent->flag_path) }}" class="w-6 h-4 sm:w-8 sm:h-5 rounded-sm object-cover shadow-sm border border-slate-200 shrink-0">
                                            @else
                                                <div class="w-6 h-4 sm:w-8 sm:h-5 rounded-sm bg-slate-200 border border-slate-300 flex items-center justify-center shrink-0">
                                                    <span class="text-[8px] text-slate-400 font-bold">?</span>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4m8 8l4-4-4-4"></path></svg>
                            </div>
                            <h4 class="text-base font-semibold text-slate-900">Aucun historique récent</h4>
                            <p class="text-sm text-slate-500 mt-1">Cette équipe n'a pas joué de match officiel depuis un moment.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>