<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col transition-all hover:shadow-md">

    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2 shrink-0">
        <span class="text-amber-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
        </span>
        <h3 class="font-black text-slate-800">Meilleur prono</h3>
    </div>

    <div class="flex flex-col divide-y divide-slate-100 flex-1">

        {{-- Mon meilleur prono --}}
        <div class="p-5 flex flex-col gap-3">
            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Mon meilleur coup</span>

            @if($this->myBest)
                @php $p = $this->myBest; $g = $p->game; @endphp
                <div class="flex flex-col gap-2">
                    {{-- Match --}}
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <img src="{{ asset($g->homeTeam->flag_path) }}" class="w-6 h-4 object-cover rounded shadow-sm shrink-0">
                            <span class="text-xs font-bold text-slate-700 truncate">{{ $g->homeTeam->nameFr }}</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 shrink-0">vs</span>
                        <div class="flex items-center gap-1.5 justify-end min-w-0">
                            <span class="text-xs font-bold text-slate-700 truncate text-right">{{ $g->awayTeam->nameFr }}</span>
                            <img src="{{ asset($g->awayTeam->flag_path) }}" class="w-6 h-4 object-cover rounded shadow-sm shrink-0">
                        </div>
                    </div>

                    {{-- Scores --}}
                    <div class="flex items-center justify-center gap-3">
                        <div class="text-center">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Prono</div>
                            <div class="bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-1 font-black text-indigo-700 text-sm">
                                {{ $p->home_score }} – {{ $p->away_score }}
                            </div>
                        </div>
                        <div class="text-slate-300 text-xs">→</div>
                        <div class="text-center">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Résultat</div>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1 font-black text-slate-700 text-sm">
                                {{ $g->home_score }} – {{ $g->away_score }}
                            </div>
                        </div>
                    </div>

                    {{-- Points & badges --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            @if($p->status === 'exact')
                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-[10px] font-black px-2 py-0.5 rounded-full border border-emerald-200">
                                    🎯 Score exact
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-indigo-100 text-indigo-700 text-[10px] font-black px-2 py-0.5 rounded-full border border-indigo-200">
                                    📈 Bon sens
                                </span>
                            @endif
                            @if($p->is_boosted)
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-[10px] font-black px-2 py-0.5 rounded-full border border-amber-200">
                                    🚀 Boosté
                                </span>
                            @endif
                        </div>
                        <span class="text-base font-black text-emerald-600">+{{ $p->points }} pts</span>
                    </div>
                </div>
            @else
                <div class="text-center py-3">
                    <span class="text-2xl block mb-1 opacity-40">🎯</span>
                    <p class="text-xs text-slate-500">Aucun prono gagnant pour l'instant.</p>
                </div>
            @endif
        </div>

        {{-- Meilleur prono global --}}
        <div class="p-5 flex flex-col gap-3">
            <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Meilleur prono du tournoi</span>

            @if($this->globalBest)
                @php $gp = $this->globalBest; $gg = $gp->game; $gu = $gp->user; @endphp
                <div class="flex flex-col gap-2">
                    {{-- Joueur --}}
                    <div class="flex items-center gap-2">
                        <div class="h-7 w-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-black text-xs shrink-0 overflow-hidden">
                            @if($gu->avatar_path)
                                <img src="{{ asset('storage/'.$gu->avatar_path) }}" class="w-full h-full object-cover rounded-full">
                            @else
                                {{ $gu->initials() }}
                            @endif
                        </div>
                        <span class="text-xs font-black text-slate-800">{{ $gu->name }}</span>
                        @if($gu->id === auth()->id())
                            <span class="text-[9px] font-black text-amber-600 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded-full">C'est toi !</span>
                        @endif
                    </div>

                    {{-- Match --}}
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <img src="{{ asset($gg->homeTeam->flag_path) }}" class="w-6 h-4 object-cover rounded shadow-sm shrink-0">
                            <span class="text-xs font-bold text-slate-700 truncate">{{ $gg->homeTeam->nameFr }}</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 shrink-0">vs</span>
                        <div class="flex items-center gap-1.5 justify-end min-w-0">
                            <span class="text-xs font-bold text-slate-700 truncate text-right">{{ $gg->awayTeam->nameFr }}</span>
                            <img src="{{ asset($gg->awayTeam->flag_path) }}" class="w-6 h-4 object-cover rounded shadow-sm shrink-0">
                        </div>
                    </div>

                    {{-- Scores --}}
                    <div class="flex items-center justify-center gap-3">
                        <div class="text-center">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Prono</div>
                            <div class="bg-amber-50 border border-amber-100 rounded-lg px-3 py-1 font-black text-amber-700 text-sm">
                                {{ $gp->home_score }} – {{ $gp->away_score }}
                            </div>
                        </div>
                        <div class="text-slate-300 text-xs">→</div>
                        <div class="text-center">
                            <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Résultat</div>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1 font-black text-slate-700 text-sm">
                                {{ $gg->home_score }} – {{ $gg->away_score }}
                            </div>
                        </div>
                    </div>

                    {{-- Points --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            @if($gp->status === 'exact')
                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-[10px] font-black px-2 py-0.5 rounded-full border border-emerald-200">
                                    🎯 Score exact
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-indigo-100 text-indigo-700 text-[10px] font-black px-2 py-0.5 rounded-full border border-indigo-200">
                                    📈 Bon sens
                                </span>
                            @endif
                            @if($gp->is_boosted)
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-[10px] font-black px-2 py-0.5 rounded-full border border-amber-200">
                                    🚀 Boosté
                                </span>
                            @endif
                        </div>
                        <span class="text-base font-black text-amber-600">+{{ $gp->points }} pts</span>
                    </div>
                </div>
            @else
                <div class="text-center py-3">
                    <span class="text-2xl block mb-1 opacity-40">🏆</span>
                    <p class="text-xs text-slate-500">Pas encore de pronos récompensés.</p>
                </div>
            @endif
        </div>

    </div>
</div>
