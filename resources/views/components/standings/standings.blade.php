<div class="w-full mx-auto px-4 py-8 space-y-8">

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
                class="px-6 py-2 rounded-lg transition-all text-sm {{ $tab == "poolPhase" ? "bg-white text-indigo-700 font-bold shadow-sm" : "text-slate-500 font-medium hover:text-slate-700 hover:bg-slate-200" }}" 
                wire:click="$set('tab', 'poolPhase')"
            >
                Phase de poules
            </button>
            <button 
                class="px-6 py-2 rounded-lg transition-all text-sm {{ $tab == "poolPhase" ? "text-slate-500 font-medium hover:text-slate-700 hover:bg-slate-200" : "bg-white text-indigo-700 font-bold shadow-sm" }}" 
                wire:click="$set('tab', 'knockout')"
            >
                Elimination directe
            </button>
        </div>
    </div>

    @if($tab == "poolPhase")
        <div class="w-5/6 mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            
            @foreach($this->groups() as $groupName => $teams)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-shadow hover:shadow-md">
                    
                    <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex justify-between items-center">
                        <h2 class="font-black text-slate-800 text-lg uppercase tracking-wider">
                            {{ __('api.group.' . $groupName) }}
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-white border-b border-slate-100 text-slate-400 text-xs uppercase font-bold">
                                <tr>
                                    <th scope="col" class="px-4 py-3 w-8 text-center">#</th>
                                    <th scope="col" class="px-2 py-3">Équipe</th>
                                    <th scope="col" class="px-2 py-3 text-center" title="Joués">J</th>
                                    <th scope="col" class="px-2 py-3 text-center hidden sm:table-cell" title="Gagnés">G</th>
                                    <th scope="col" class="px-2 py-3 text-center hidden sm:table-cell" title="Nuls">N</th>
                                    <th scope="col" class="px-2 py-3 text-center hidden sm:table-cell" title="Perdus">P</th>
                                    <th scope="col" class="px-2 py-3 text-center" title="Différence de buts">Diff</th>
                                    <th scope="col" class="px-4 py-3 text-center text-indigo-900">Pts</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($teams as $index => $team)
                                    @php
                                        $position = $index + 1;
                                        $isQualified = $position <= 2;
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        
                                        <td @class([
                                            'px-4 py-3 text-center font-bold',
                                            'border-l-4 border-emerald-500 text-emerald-600 bg-emerald-50/30' => $isQualified,
                                            'border-l-4 border-transparent text-slate-400' => !$isQualified,
                                        ])>
                                            {{ $position }}
                                        </td>
                                        
                                        <td class="px-2 py-3">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ asset($team->flag_path) }}" alt="{{ $team->nameFr }}" class="w-6 h-4 sm:w-7 sm:h-5 rounded-[2px] object-cover shadow-sm border border-slate-200">
                                                <span class="font-bold text-slate-800 truncate max-w-[100px] sm:max-w-[150px]">
                                                    {{ $team->nameFr }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-2 py-3 text-center font-medium text-slate-600">{{ $team->played ?? 0 }}</td>
                                        <td class="px-2 py-3 text-center text-slate-500 hidden sm:table-cell">{{ $team->won ?? 0 }}</td>
                                        <td class="px-2 py-3 text-center text-slate-500 hidden sm:table-cell">{{ $team->drawn ?? 0 }}</td>
                                        <td class="px-2 py-3 text-center text-slate-500 hidden sm:table-cell">{{ $team->lost ?? 0 }}</td>
                                        
                                        <td class="px-2 py-3 text-center font-semibold">
                                            @if(($team->goal_difference ?? 0) > 0)
                                                <span class="text-emerald-600">+{{ $team->goal_difference }}</span>
                                            @elseif(($team->goal_difference ?? 0) < 0)
                                                <span class="text-red-500">{{ $team->goal_difference }}</span>
                                            @else
                                                <span class="text-slate-400">0</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <span class="bg-indigo-50 text-indigo-700 font-black px-2.5 py-1 rounded-lg border border-indigo-100">
                                                {{ $team->points ?? 0 }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="w-1/2 mx-auto">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-shadow hover:shadow-md">
                
                <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex justify-between items-center">
                    <h2 class="font-black text-slate-800 text-lg uppercase tracking-wider">
                        Classement des 3èmes de groupe
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-white border-b border-slate-100 text-slate-400 text-xs uppercase font-bold">
                            <tr>
                                <th scope="col" class="px-4 py-3 w-8 text-center">#</th>
                                <th scope="col" class="px-2 py-3">Équipe</th>
                                <th scope="col" class="px-2 py-3 text-center" title="Joués">J</th>
                                <th scope="col" class="px-2 py-3 text-center hidden sm:table-cell" title="Gagnés">G</th>
                                <th scope="col" class="px-2 py-3 text-center hidden sm:table-cell" title="Nuls">N</th>
                                <th scope="col" class="px-2 py-3 text-center hidden sm:table-cell" title="Perdus">P</th>
                                <th scope="col" class="px-2 py-3 text-center" title="Différence de buts">Diff</th>
                                <th scope="col" class="px-4 py-3 text-center text-indigo-900">Pts</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">

                            @foreach($this->thirds() as $index => $team)
                                @php
                                    $position = $index + 1;
                                    $isQualified = $position <= 8;
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    
                                    <td @class([
                                        'px-4 py-3 text-center font-bold',
                                        'border-l-4 border-emerald-500 text-emerald-600 bg-emerald-50/30' => $isQualified,
                                        'border-l-4 border-transparent text-slate-400' => !$isQualified,
                                    ])>
                                        {{ $position }}
                                    </td>
                                    
                                    <td class="px-2 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ asset($team->flag_path) }}" alt="{{ $team->nameFr }}" class="w-6 h-4 sm:w-7 sm:h-5 rounded-[2px] object-cover shadow-sm border border-slate-200">
                                            <span class="font-bold text-slate-800 truncate max-w-[100px] sm:max-w-[150px]">
                                                {{ $team->nameFr }} ({{ $team->poolFr }})
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-2 py-3 text-center font-medium text-slate-600">{{ $team->played ?? 0 }}</td>
                                    <td class="px-2 py-3 text-center text-slate-500 hidden sm:table-cell">{{ $team->won ?? 0 }}</td>
                                    <td class="px-2 py-3 text-center text-slate-500 hidden sm:table-cell">{{ $team->drawn ?? 0 }}</td>
                                    <td class="px-2 py-3 text-center text-slate-500 hidden sm:table-cell">{{ $team->lost ?? 0 }}</td>
                                    
                                    <td class="px-2 py-3 text-center font-semibold">
                                        @if(($team->goal_difference ?? 0) > 0)
                                            <span class="text-emerald-600">+{{ $team->goal_difference }}</span>
                                        @elseif(($team->goal_difference ?? 0) < 0)
                                            <span class="text-red-500">{{ $team->goal_difference }}</span>
                                        @else
                                            <span class="text-slate-400">0</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <span class="bg-indigo-50 text-indigo-700 font-black px-2.5 py-1 rounded-lg border border-indigo-100">
                                            {{ $team->points ?? 0 }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @else 
        <div class="bg-slate-50 rounded-3xl border border-slate-200 shadow-inner overflow-x-auto p-4 sm:p-8">
            <div class="flex items-stretch gap-4 min-w-[1400px] py-10">

                <div class="flex flex-col justify-around gap-4 w-48">
                    @foreach([1,2,3,4,5,6,7,8] as $slot)
                        <x-bracket-match :match="$this->knockoutGames->get($slot)" :slot="$slot" side="left" />
                    @endforeach
                </div>

                <div class="flex flex-col justify-around gap-4 w-48">
                    @foreach([17,18,19,20] as $slot)
                        <x-bracket-match :match="$this->knockoutGames->get($slot)" :slot="$slot" side="left" />
                    @endforeach
                </div>

                <div class="flex flex-col justify-around gap-4 w-48">
                    @foreach([25,26] as $slot)
                        <x-bracket-match :match="$this->knockoutGames->get($slot)" :slot="$slot" side="left" />
                    @endforeach
                </div>

                <div class="flex flex-col justify-around gap-4 w-48">
                    <x-bracket-match :match="$this->knockoutGames->get(29)" :slot="29" side="left" />
                </div>


                <div class="flex flex-col justify-center items-center gap-12 w-64 px-4">
                    
                    <div class="w-full">
                        <h3 class="text-center font-black text-amber-500 uppercase text-xs tracking-widest mb-4">Finale</h3>
                        <div class="bg-white border-2 border-amber-300 rounded-2xl shadow-xl overflow-hidden ring-8 ring-amber-50">
                            <div class="bg-amber-100/50 text-[10px] text-center text-amber-700 font-black py-2 border-b border-amber-200 uppercase tracking-widest">
                                19/07/2026 • New York/New Jersey
                            </div>
                            @php $final = $this->knockoutGames->get(31); @endphp
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-800">{{ $final->homeTeam->nameFr ?? 'Vainqueur M101' }}</span>
                                    <span class="font-black text-xl">{{ $final->home_score ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-800">{{ $final->awayTeam->nameFr ?? 'Vainqueur M102' }}</span>
                                    <span class="font-black text-xl">{{ $final->away_score ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-4/5 opacity-75">
                        <h3 class="text-center font-bold text-slate-400 uppercase text-[10px] tracking-widest mb-2">3ème Place</h3>
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden text-xs">
                            @php $thirdPlace = $this->knockoutGames->get(32); @endphp
                            <div class="px-3 py-2 border-b border-slate-100 flex justify-between">
                                <span>{{ $thirdPlace->homeTeam->nameFr ?? 'Perdant M101' }}</span>
                                <span class="font-bold">{{ $thirdPlace->home_score ?? '-' }}</span>
                            </div>
                            <div class="px-3 py-2 flex justify-between">
                                <span>{{ $thirdPlace->awayTeam->nameFr ?? 'Perdant M102' }}</span>
                                <span class="font-bold">{{ $thirdPlace->away_score ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-col justify-around gap-4 w-48 text-right">
                    <x-bracket-match :match="$this->knockoutGames->get(30)" :slot="30" side="right" />
                </div>

                <div class="flex flex-col justify-around gap-4 w-48 text-right">
                    @foreach([27,28] as $slot)
                        <x-bracket-match :match="$this->knockoutGames->get($slot)" :slot="$slot" side="right" />
                    @endforeach
                </div>

                <div class="flex flex-col justify-around gap-4 w-48 text-right">
                    @foreach([21,22,23,24] as $slot)
                        <x-bracket-match :match="$this->knockoutGames->get($slot)" :slot="$slot" side="right" />
                    @endforeach
                </div>

                <div class="flex flex-col justify-around gap-4 w-48 text-right">
                    @foreach([9,10,11,12,13,14,15,16] as $slot)
                        <x-bracket-match :match="$this->knockoutGames->get($slot)" :slot="$slot" side="right" />
                    @endforeach
                </div>

            </div>
        </div>
    @endif
</div>