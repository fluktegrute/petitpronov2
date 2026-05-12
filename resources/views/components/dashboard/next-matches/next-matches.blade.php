<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
    
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
        <h3 class="font-black text-slate-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            À venir
        </h3>
        <a href="{{ route('matches') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-widest">
            Voir tout
        </a>
    </div>

    <div class="flex-1 overflow-y-auto">
        <ul class="divide-y divide-slate-100">
            @forelse($this->nextMatches as $game)
                <li class="p-4 sm:p-5 hover:bg-slate-50 transition-colors group">
                    
                    <div class="flex justify-between items-center text-xs text-slate-400 font-bold mb-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ \Carbon\Carbon::parse($game->kickoff_at)->translatedFormat('d M à H:i') }}
                        </span>
                        <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded uppercase tracking-wider">
                            {{ $game->stageFr ?? 'Tournoi' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 w-2/5">
                            @if($game->homeTeam)
                                <img src="{{ asset($game->homeTeam->flag_path) }}" class="w-6 h-4 sm:w-8 sm:h-5 object-cover rounded-sm shadow-sm">
                                <span class="font-bold text-slate-800 text-sm truncate">{{ $game->homeTeam->nameFr }}</span>
                            @else
                                <span class="font-bold text-slate-400 text-sm italic">À définir</span>
                            @endif
                        </div>

                        <div class="w-1/5 text-center">
                            <span class="text-xs font-black italic text-slate-300">VS</span>
                        </div>

                        <div class="flex items-center justify-end gap-2 w-2/5 text-right">
                            @if($game->awayTeam)
                                <span class="font-bold text-slate-800 text-sm truncate">{{ $game->awayTeam->nameFr }}</span>
                                <img src="{{ asset($game->awayTeam->flag_path) }}" class="w-6 h-4 sm:w-8 sm:h-5 object-cover rounded-sm shadow-sm">
                            @else
                                <span class="font-bold text-slate-400 text-sm italic">À définir</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 text-center opacity-80 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('matches') }}" class="inline-block w-full sm:w-auto bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[11px] font-black py-2 px-6 rounded-lg transition-colors border border-indigo-100 uppercase tracking-widest">
                            Aller pronostiquer
                        </a>
                    </div>
                </li>
            @empty
                <div class="p-8 text-center text-slate-500">
                    <span class="text-3xl mb-2 block">⏳</span>
                    <p class="text-sm font-medium">Aucun match prévu pour le moment.</p>
                </div>
            @endforelse
        </ul>
    </div>
</div>