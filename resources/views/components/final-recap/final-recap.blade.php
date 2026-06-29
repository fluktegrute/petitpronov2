<div>
@if($this->shouldShow)
<div class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/75 backdrop-blur-sm"
     x-data
     x-init="$el.scrollTop = 0">

    <div class="relative bg-white w-full sm:max-w-5/6 sm:rounded-3xl shadow-2xl max-h-screen sm:max-h-[92vh] overflow-y-auto rounded-t-3xl"
         x-data x-init="$nextTick(() => $el.scrollTop = 0)">

        <div class="bg-gradient-to-br from-amber-400 via-orange-400 to-orange-500 px-6 pt-8 pb-6 text-center sm:rounded-t-3xl rounded-t-3xl">
            <div class="text-5xl mb-3 animate-bounce">🏆</div>
            <h2 class="text-2xl font-black text-white tracking-tight">Merci d'avoir joué !</h2>
            <p class="text-amber-100 text-sm mt-1">La Coupe du Monde 2026 est terminée</p>

            @if($this->finalGame)
                <div class="mt-4 inline-flex flex-col items-center gap-1 bg-white/25 rounded-2xl px-5 py-3">
                    <span class="text-white/70 text-xs font-bold uppercase tracking-widest">Finale</span>
                    <div class="flex items-center gap-3">
                        <span class="text-white font-bold text-sm">{{ $this->finalGame->homeTeam->nameFr }}</span>
                        <span class="text-white font-black text-2xl">{{ $this->finalGame->home_score }}&nbsp;–&nbsp;{{ $this->finalGame->away_score }}</span>
                        <span class="text-white font-bold text-sm">{{ $this->finalGame->awayTeam->nameFr }}</span>
                    </div>
                    @if($this->championTeam)
                        <div class="flex items-center gap-2 mt-1">
                            <img src="{{ asset($this->championTeam->flag_path) }}" class="w-5 h-3.5 rounded-[2px] object-cover shadow">
                            <span class="text-amber-100 text-xs font-semibold">Champion : {{ $this->championTeam->nameFr }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="sm:grid sm:grid-cols-2 sm:divide-x sm:divide-slate-100">

            <div class="px-5 py-6 space-y-3 text-sm text-slate-600 leading-relaxed border-b border-slate-100 sm:border-b-0">
                <p>L'arbitre a sifflé la fin du match, le trophée a été soulevé, et il est temps de fermer les portes du vestiaire.</p>
                <p>Quand j'ai codé Ponybet dans mon coin, l'objectif était simple : fuir les sites de paris sportifs toxiques, garder nos économies et données perso bien au chaud, et retrouver le plaisir brut du foot entre potes. 0% prise de tête, 100% plaisir.</p>
                <p>Vous avez été presque 200 joueurs à rejoindre l'arène, à créer vos ligues, à lâcher vos meilleurs pronostics et à enflammer les chats privés avec un chambrage de très haut niveau.</p>
                <ul class="space-y-2">
                    <li class="flex gap-2">
                        <span class="shrink-0">👑</span>
                        <span><strong class="text-slate-800">À nos grands gagnants :</strong> Félicitations. Vous avez eu un flair incroyable (ou une chance insolente). Profitez bien de votre statut, vous avez officiellement le droit d'être insupportables jusqu'à la prochaine compétition.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="shrink-0">📉</span>
                        <span><strong class="text-slate-800">À tous les autres :</strong> Ceux dont le favori a pris la porte en huitièmes, ceux qui ont gâché leurs boosts sur des matchs improbables, et ceux qui ont persévéré à pronostiquer des 0-0 ennuyeux… Merci pour votre participation. On ne vous juge pas (enfin, juste un peu).</span>
                    </li>
                </ul>
                <p>Un immense <strong class="text-slate-800">MERCI</strong> à toi d'avoir donné vie à ce projet perso. Voir la base de données s'animer et les scores exploser grâce à toi a été la meilleure des récompenses.</p>
                <p>Profite bien de l'été, repose-toi, et garde tes talents de devins au chaud… on se reverra peut-être pour la prochaine compétition ! ⚽👋</p>
                <p class="text-right text-slate-400 italic text-xs pt-1 font-bold">Fluktegrute</p>
            </div>

            <div class="px-5 py-6 space-y-5">

                <section>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Tes performances</h3>
                    <div class="bg-indigo-600 rounded-2xl p-4 text-center mb-2">
                        <p class="text-[11px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Total des points</p>
                        <p class="text-5xl font-black text-white">{{ auth()->user()->total_points }}</p>
                        <p class="text-indigo-200 text-sm mt-2">
                            @php $rank = $this->userRank; @endphp
                            <span class="font-black text-white">{{ $rank }}{{ $rank === 1 ? 'er' : 'ème' }}</span>
                            sur {{ $this->globalStats->players }} joueurs
                        </p>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-emerald-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-emerald-700">{{ auth()->user()->exact_count }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Exacts</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-blue-700">{{ auth()->user()->trend_count }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Tendances</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-slate-700">{{ auth()->user()->prono_count }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Joués</p>
                        </div>
                    </div>
                </section>

                @if($this->bestPrediction)
                <section>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Ton meilleur prono</h3>
                    <div class="bg-slate-50 rounded-2xl p-4 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <p class="font-bold text-slate-800 text-sm truncate">
                                {{ $this->bestPrediction->game->homeTeam->nameFr }}
                                <span class="text-slate-400 font-normal">vs</span>
                                {{ $this->bestPrediction->game->awayTeam->nameFr }}
                            </p>
                            <p class="text-slate-500 text-xs mt-1">
                                Résultat : <span class="font-semibold text-slate-700">{{ $this->bestPrediction->game->home_score }}–{{ $this->bestPrediction->game->away_score }}</span>
                                &nbsp;·&nbsp;
                                Prono : <span class="font-semibold text-slate-700">{{ $this->bestPrediction->home_score }}–{{ $this->bestPrediction->away_score }}</span>
                            </p>
                            <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-widest">
                                {{ $this->bestPrediction->status === 'exact' ? 'Score exact' : 'Bonne tendance' }}
                                @if($this->bestPrediction->is_boosted) · Boosté @endif
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-2xl font-black text-indigo-700">+{{ $this->bestPrediction->points }}</p>
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest">pts</p>
                        </div>
                    </div>
                </section>
                @endif

                <section>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Ton poney</h3>
                    @if(auth()->user()->winnerTeam)
                        <div class="bg-slate-50 rounded-2xl p-4 flex items-center gap-3">
                            <img src="{{ asset(auth()->user()->winnerTeam->flag_path) }}"
                                 alt="{{ auth()->user()->winnerTeam->nameFr }}"
                                 class="w-10 h-7 rounded-[3px] object-cover shadow-sm border border-slate-200 shrink-0">
                            <div>
                                <p class="font-bold text-slate-800">{{ auth()->user()->winnerTeam->nameFr }}</p>
                                @if($this->pickedWinner)
                                    <p class="text-sm text-emerald-600 font-semibold">Bien joué, tu avais vu juste !</p>
                                @else
                                    <p class="text-sm text-slate-400">Pas cette fois… mais chapeau !</p>
                                @endif
                            </div>
                            @if($this->pickedWinner)
                                <span class="ml-auto text-2xl">🎉</span>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic bg-slate-50 rounded-xl px-4 py-3">Tu n'avais pas choisi de poney.</p>
                    @endif
                </section>

                <section>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">L'appli en chiffres</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-black text-slate-800">{{ $this->globalStats->players }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Joueurs</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-black text-slate-800">{{ $this->globalStats->leagues }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Ligues</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-black text-slate-800">{{ $this->globalStats->evaluated }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Pronos</p>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-3 text-center col-span-2">
                            <p class="text-xl font-black text-emerald-700">
                                {{ $this->globalStats->exact }}
                                <span class="text-sm font-semibold text-slate-400">({{ $this->globalStats->exactPercent }}%)</span>
                            </p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Scores exacts</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-3 text-center">
                            <p class="text-xl font-black text-blue-700">{{ $this->globalStats->goodPercent }}%</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Bons pronos</p>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        <div class="px-5 pb-8 pt-2 border-t border-slate-100">
            <button
                wire:click="dismiss"
                class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold py-3.5 rounded-xl transition-colors text-sm tracking-wide cursor-pointer">
                Fermer
            </button>
        </div>
    </div>
</div>
@endif
</div>