<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full transition-all hover:shadow-md">
    
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
        <h3 class="font-black text-slate-800 flex items-center gap-2">
            <span class="text-xl leading-none text-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
            </span>
            Radar à Boosts
        </h3>
    </div>

    <div class="flex-1 flex flex-col">
        
        <div class="p-6 flex flex-col items-center justify-center border-b border-slate-100 relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600">
            <svg class="absolute top-0 right-0 text-white opacity-10 w-32 h-32 transform translate-x-8 -translate-y-8" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            
            <div class="relative z-10 flex flex-col items-center text-center">
                <span class="text-4xl mb-2 drop-shadow-md">🚀</span>
                <span class="text-xs font-black text-amber-100 uppercase tracking-widest mb-1">Inventaire</span>
                @if($this->boostsRemaining > 0)
                    <span class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        {{ $this->boostsRemaining }} Boost{{ $this->boostsRemaining > 1 ? 's' : '' }} restant{{ $this->boostsRemaining > 1 ? 's' : '' }}
                    </span>
                @else
                    <span class="text-xl sm:text-2xl font-black text-white tracking-tight">
                        À sec !
                    </span>
                @endif
            </div>
        </div>

        <div class="p-5 bg-white flex-grow flex flex-col justify-center">
            
            @if($this->boostsRemaining > 0 && $this->crazyBet)
                
                <div class="text-center mb-4">
                    <span class="inline-block bg-amber-100 text-amber-700 text-[10px] font-black px-2 py-1 rounded uppercase tracking-widest mb-2 border border-amber-200">
                        Le Coup de Folie
                    </span>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">
                        Une victoire surprise de <strong class="text-slate-900">{{ $this->crazyBet['underdog']->nameFr }}</strong> pourrait te rapporter jusqu'à <strong class="text-amber-600 text-base">{{ $this->crazyBet['potential'] }} points</strong> !
                    </p>
                </div>

                <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2 w-2/5">
                        <img src="{{ asset($this->crazyBet['game']->homeTeam->flag_path) }}" class="w-6 h-4 object-cover rounded shadow-sm">
                        <span class="font-bold text-slate-800 text-xs truncate">{{ $this->crazyBet['game']->homeTeam->nameFr }}</span>
                    </div>
                    <div class="w-1/5 text-center">
                        <span class="text-[10px] font-black text-amber-500 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100">Cote {{ number_format($this->crazyBet['odd'], 2, ',', ' ') }}</span>
                    </div>
                    <div class="flex items-center justify-end gap-2 w-2/5 text-right">
                        <span class="font-bold text-slate-800 text-xs truncate">{{ $this->crazyBet['game']->awayTeam->nameFr }}</span>
                        <img src="{{ asset($this->crazyBet['game']->awayTeam->flag_path) }}" class="w-6 h-4 object-cover rounded shadow-sm">
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('matches') }}" class="inline-block w-full bg-slate-900 hover:bg-slate-800 text-white text-[11px] font-black py-2.5 px-6 rounded-lg transition-colors border border-slate-700 uppercase tracking-widest shadow-sm">
                        Tenter le coup
                    </a>
                </div>

            @elseif($this->boostsRemaining == 0)
                
                <div class="text-center p-4">
                    <span class="text-3xl block mb-2 opacity-50">📉</span>
                    <p class="text-sm font-bold text-slate-700">Plus de carburant...</p>
                    <p class="text-xs text-slate-500 mt-1">Tu as utilisé tous tes boosts pour ce tournoi. Il va falloir compter sur ton talent brut maintenant !</p>
                </div>

            @else
                
                <div class="text-center p-4">
                    <span class="text-3xl block mb-2 opacity-50">🔮</span>
                    <p class="text-sm font-bold text-slate-700">Radar en recherche</p>
                    <p class="text-xs text-slate-500 mt-1">Les cotes des prochains matchs ne sont pas encore disponibles ou aucun coup fumant n'a été détecté.</p>
                </div>

            @endif

        </div>
    </div>
</div>