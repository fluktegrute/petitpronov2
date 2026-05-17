<div class="w-full max-w-7xl mx-auto px-4 py-8 space-y-8">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Les gradins
            </h1>
            <p class="text-slate-500 mt-1 font-medium flex items-center gap-3">
                <span class="w-8"></span>
                <span>Statistiques globales</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Joueurs Inscrits</p>
                <p class="text-3xl font-black text-slate-900">{{ $this->globalStats['players'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Ligues Actives</p>
                <p class="text-3xl font-black text-slate-900">{{ $this->globalStats['leagues'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Pronostics Enregistrés</p>
                <p class="text-3xl font-black text-slate-900">{{ $this->globalStats['pronos_total'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
            <h3 class="font-black text-slate-800">Qualité globale des pronostics</h3>
        </div>
        <div class="p-6 sm:p-8 space-y-6">
            
            <div class="grid grid-cols-3 divide-x divide-slate-100 text-center">
                <div class="px-4">
                    <span class="block text-2xl sm:text-3xl font-black text-emerald-500">{{ $this->globalStats['pronos_exacts'] }}</span>
                    <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Scores Exacts 🎯</span>
                </div>
                <div class="px-4">
                    <span class="block text-2xl sm:text-3xl font-black text-blue-500">{{ $this->globalStats['pronos_trends'] }}</span>
                    <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Bonnes Tendances 📈</span>
                </div>
                <div class="px-4">
                    <span class="block text-2xl sm:text-3xl font-black text-red-400">{{ $this->globalStats['pronos_lost'] }}</span>
                    <span class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Perdus / En attente ❌</span>
                </div>
            </div>

            @php
                $total = $this->globalStats['pronos_total'] > 0 ? $this->globalStats['pronos_total'] : 1;
                $pctExact = ($this->globalStats['pronos_exacts'] / $total) * 100;
                $pctTrend = ($this->globalStats['pronos_trends'] / $total) * 100;
                $pctLost = ($this->globalStats['pronos_lost'] / $total) * 100;
            @endphp
            <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden flex shadow-inner">
                <div style="width: {{ $pctExact }}%" class="bg-emerald-500 transition-all duration-500" title="Exacts: {{ round($pctExact) }}%"></div>
                <div style="width: {{ $pctTrend }}%" class="bg-blue-500 transition-all duration-500" title="Tendances: {{ round($pctTrend) }}%"></div>
                <div style="width: {{ $pctLost }}%" class="bg-red-400 transition-all duration-500" title="Perdus: {{ round($pctLost) }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-black text-slate-800">Détail des ligues</h3>
            <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-1 rounded-md">{{ $this->leaguesList->count() }} ligues</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white border-b border-slate-100 text-xs uppercase tracking-widest text-slate-400 font-black">
                    <tr>
                        <th scope="col" class="px-6 py-4">Nom de la ligue</th>
                        <th scope="col" class="px-6 py-4 text-center">Nombre de Joueurs</th>
                        <th scope="col" class="px-6 py-4">Créée le</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($this->leaguesList as $league)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800">{{ $league->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center bg-slate-800 text-white font-black text-xs h-6 px-3 rounded-full shadow-sm">
                                    {{ $league->users_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs font-medium">
                                {{ $league->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500 font-medium">
                                Aucune ligue n'a encore été créée sur l'application.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>