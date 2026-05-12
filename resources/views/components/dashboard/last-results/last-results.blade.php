<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full transition-all hover:shadow-md">
    
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
        <h3 class="font-black text-slate-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            Derniers Résultats
        </h3>
        <a href="{{ route('matches') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-widest">
            Voir tout
        </a>
    </div>

    <div class="flex-1 flex flex-col justify-center bg-white">
        <ul class="divide-y divide-slate-50">
            @forelse($this->lastMatches as $game)
                <li class="p-4 sm:p-5 hover:bg-slate-50 transition-colors">
                    
                    <div class="flex justify-between items-center text-xs text-slate-400 font-bold mb-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ \Carbon\Carbon::parse($game->kickoff_at)->translatedFormat('d M') }}
                        </span>
                        <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded uppercase tracking-wider">
                            {{ $game->stageFr ?? 'Tournoi' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-1 sm:gap-2 w-[35%] text-center sm:text-left">
                            @if($game->homeTeam)
                                <img src="{{ asset($game->homeTeam->flag_path) }}" class="w-8 h-5 sm:w-10 sm:h-6 object-cover rounded-sm shadow-sm shrink-0">
                                <span class="font-bold text-slate-800 text-xs sm:text-sm leading-tight truncate w-full">{{ $game->homeTeam->nameFr }}</span>
                            @else
                                <span class="font-bold text-slate-400 text-xs italic">À définir</span>
                            @endif
                        </div>

                        <div class="w-[30%] flex justify-center">
                            @if(!is_null($game->home_score) && !is_null($game->away_score))
                                <div class="bg-slate-800 text-white font-black text-lg sm:text-xl px-3 sm:px-4 py-1 rounded-xl tracking-widest shadow-inner flex items-center justify-center gap-1">
                                    <span>{{ $game->home_score }}</span>
                                    <span class="text-slate-400 text-sm opacity-50">-</span>
                                    <span>{{ $game->away_score }}</span>
                                </div>
                            @else
                                <span class="text-[10px] sm:text-xs font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-1 rounded-lg uppercase tracking-widest animate-pulse">
                                    En cours
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center justify-end sm:items-start gap-1 sm:gap-2 w-[35%] text-center sm:text-right">
                            @if($game->awayTeam)
                                <span class="font-bold text-slate-800 text-xs sm:text-sm leading-tight truncate w-full">{{ $game->awayTeam->nameFr }}</span>
                                <img src="{{ asset($game->awayTeam->flag_path) }}" class="w-8 h-5 sm:w-10 sm:h-6 object-cover rounded-sm shadow-sm shrink-0">
                            @else
                                <span class="font-bold text-slate-400 text-xs italic">À définir</span>
                            @endif
                        </div>

                    </div>
                </li>
            @empty
                <div class="p-8 text-center text-slate-500">
                    <span class="text-3xl mb-2 block">⚽</span>
                    <p class="text-sm font-medium">Le tournoi n'a pas encore commencé !</p>
                </div>
            @endforelse
        </ul>
    </div>
</div>