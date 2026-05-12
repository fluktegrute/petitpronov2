<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full transition-all hover:shadow-md">
    
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
        <h3 class="font-black text-slate-800 flex items-center gap-2">
            <span class="text-xl leading-none">🐴</span>
            Mon Poney
        </h3>
    </div>

    <div class="flex-1 flex flex-col">
        
        @if($userPony)
            <div class="p-6 flex flex-col items-center justify-center border-b border-slate-100 relative overflow-hidden bg-white">
                <div class="absolute"></div>
                
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full border-4 border-white shadow-lg overflow-hidden bg-slate-100 mb-3 ring-4 ring-indigo-50">
                        <img src="{{ asset($userPony->flag_path) }}" alt="{{ $userPony->nameFr }}" class="w-full h-full object-cover">
                    </div>
                    <span class="text-xl font-black text-slate-800 tracking-tight">{{ $userPony->nameFr }}</span>
                    
                    <div class="mt-2 flex items-center gap-2 bg-slate-800 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest shadow-sm">
                        <span>{{ $userPony->poolFr }}</span>
                        <span class="w-1 h-1 rounded-full bg-slate-500"></span>
                        <span class="{{ $teamPoolRanking < 2 ? 'text-emerald-400' : 'text-amber-400' }}">
                            {{ $teamPoolRanking }}{{ $teamPoolRanking === 1 ? 'er' : 'ème' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-5 bg-slate-50 flex-grow flex flex-col justify-center">
                @if($nextGame)
                    @php
                        $isHome = $nextGame->home_team_id === $userPony->id;
                        $opponent = $isHome ? $nextGame->awayTeam : $nextGame->homeTeam;
                    @endphp

                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center block mb-3">
                        Prochain match • {{ \Carbon\Carbon::parse($nextGame->kickoff_at)->translatedFormat('d M à H:i') }}
                    </span>
                    
                    <div class="flex items-center justify-center gap-4">
                        <div class="flex flex-col items-center w-1/3">
                            <img src="{{ asset($userPony->flag_path) }}" class="w-8 h-5 object-cover rounded shadow-sm opacity-80">
                            <span class="text-xs font-bold text-slate-700 mt-1 truncate w-full text-center">{{ $userPony->nameFr }}</span>
                        </div>
                        
                        <span class="text-xs font-black italic text-slate-300">VS</span>
                        
                        <div class="flex flex-col items-center w-1/3">
                            @if($opponent)
                                <img src="{{ asset($opponent->flag_path) }}" class="w-8 h-5 object-cover rounded shadow-sm">
                                <span class="text-xs font-bold text-slate-700 mt-1 truncate w-full text-center">{{ $opponent->nameFr }}</span>
                            @else
                                <div class="w-8 h-5 bg-slate-200 rounded border border-slate-300 flex items-center justify-center">
                                    <span class="text-[10px] font-bold text-slate-400">?</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 mt-1 italic">À définir</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center p-2">
                        @if($userPony->points < 3 && $teamPoolRanking > 2) 
                            <span class="inline-block p-3 bg-red-100 text-red-600 rounded-full mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            <span class="block text-sm font-bold text-red-600">Peut-être éliminé...</span>
                            <span class="block text-xs text-red-400 mt-1">Aucun match programmé.</span>
                        @else
                            <span class="inline-block p-3 bg-amber-100 text-amber-600 rounded-full mb-2 animate-pulse">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            <span class="block text-sm font-bold text-amber-700">En attente...</span>
                            <span class="block text-[10px] text-amber-600/70 uppercase tracking-widest mt-1">Adversaire non défini</span>
                        @endif
                    </div>
                @endif
            </div>

        @else
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-slate-50">
                <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center mb-4">
                    <span class="text-2xl opacity-50">🤷‍♂️</span>
                </div>
                <h4 class="font-bold text-slate-700 mb-2">Pas de Poney !</h4>
                <p class="text-xs text-slate-500 mb-4">Tu n'as pas désigné de vainqueur final pour le tournoi.</p>
                <a href="{{ route('profile') }}" class="text-xs font-black bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors uppercase tracking-widest">
                    Choisir maintenant
                </a>
            </div>
        @endif

    </div>
</div>