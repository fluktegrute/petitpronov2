<div 
    x-data="{ modalOpen: @entangle('h2hModalOpened') }"
    x-show="modalOpen"
    x-cloak
    class="relative z-50"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <div 
        x-show="modalOpen"
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
                x-show="modalOpen"
                @click.away="modalOpen = false"
                @keydown.escape.window="modalOpen = false"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8  w-2/3 border border-slate-100"
            >
                
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2" id="modal-title">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Historique des confrontations
                    </h3>
                    <button @click="modalOpen = false" type="button" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200 p-1 rounded-lg transition-colors">
                        <span class="sr-only">Fermer</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="bg-white">
                    @if($currentH2h && $currentH2h->isNotEmpty())
                        <ul class="divide-y divide-slate-200">
                            @foreach($currentH2h as $history)
                                <li class="px-6 py-4 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4" wire:key="history_{{ $history->id }}">
                                    
                                    <div class="text-sm w-full sm:w-1/4 sm:text-center">
                                        <div class="font-semibold text-slate-700">
                                            {{ \Carbon\Carbon::parse($history->date)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-slate-500 truncate" title="{{ $history->tournament }}">
                                            {{ $history->tournamentFr }}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-center gap-3 w-full sm:w-3/4">
                                        
                                        <div class="flex items-center justify-end gap-2 w-[40%]">
                                            <span @class([
                                                'font-bold text-sm sm:text-base truncate',
                                                'text-green-700 bg-green-100 rounded-lg border border-green-700 px-2 py-1' => (int) $history->home_score > (int) $history->away_score,
                                                'text-slate-500'  => (int) $history->home_score <= (int) $history->away_score,
                                            ])>
                                                {{ $history->homeTeam->nameFr }}
                                            </span>
                                            <img src="{{ asset($history->homeTeam->flag_path) }}" class="w-6 h-4 sm:w-8 sm:h-5 rounded-sm object-cover shadow-sm border border-slate-200 flex-shrink-0">
                                        </div>

                                        <div class="bg-slate-100 px-3 py-1 rounded-lg font-black text-slate-800 text-lg tracking-widest flex-shrink-0 border border-slate-200">
                                            {{ $history->home_score }} - {{ $history->away_score }}
                                        </div>

                                        <div class="flex items-center justify-start gap-2 w-[40%]">
                                            <img src="{{ asset($history->awayTeam->flag_path) }}" class="w-6 h-4 sm:w-8 sm:h-5 rounded-sm object-cover shadow-sm border border-slate-200 flex-shrink-0">
                                            <span @class([
                                                'font-bold text-sm sm:text-base truncate',
                                                'text-green-700 bg-green-100 rounded-lg border border-green-700 px-2 py-1' => (int) $history->away_score > (int) $history->home_score,
                                                'text-slate-500' => (int) $history->away_score <= (int) $history->home_score,
                                            ])>
                                                {{ $history->awayTeam->nameFr }}
                                            </span>
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
                            <p class="text-sm text-slate-500 mt-1">Ces deux équipes ne se sont jamais affrontées lors d'un match officiel dans notre base de données.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>