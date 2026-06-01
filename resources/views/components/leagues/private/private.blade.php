<div x-data="{ showAdminModal: false }" class="w-full mx-auto px-4 py-8 space-y-4 flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">
    <div class="w-full lg:w-2/3 space-y-6">
        <div class="text-center space-y-2 mb-20">

            <h1 class="flex flex-col w-full items-center mx-auto gap-3">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Classement de la ligue</span>
                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $league->name }}</span>
                @if($isUserAdmin)
                    <button @click="showAdminModal = true" class="cursor-pointer touch-manipulation flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm">
                        <svg class="w-4 h-4 text-amber-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Gérer la ligue
                    </button>
                @endif
            </h1>
            <p class="text-slate-500">{{ $league->description }}</p>
        </div>

        @if($this->top3 && $this->top3->count() > 0)
            <div class="flex justify-center items-end gap-2 sm:gap-6 pt-10">
                
                @if($second = $this->top3->get(1))
                    <div class="flex flex-col items-center w-24 sm:w-32 relative z-10">
                        <div class="absolute -top-10 text-amber-400 text-3xl">🥈</div>
                        <div class="flex flex-col items-center mb-3 text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-slate-200 border-4 border-slate-300 shadow-md overflow-hidden flex items-center justify-center relative z-20">
                                @if($second->avatar_path)
                                    <img src="{{ asset('storage/'.$second->avatar_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-bold text-slate-500 text-xl">{{ $second->initials() }}</span>
                                @endif
                            </div>
                            <span class="font-bold text-slate-800 text-sm sm:text-base mt-2 truncate w-full">{{ $second->name }}</span>
                            <span class="font-black text-slate-500 text-xs sm:text-sm">{{ $second->total_points }} pts</span>
                        </div>
                        <div class="w-full h-24 sm:h-32 bg-gradient-to-t from-slate-300 to-slate-200 rounded-t-xl border-t-4 border-slate-400 shadow-inner flex justify-center pt-2">
                            <span class="font-black text-slate-500 text-2xl">2</span>
                        </div>
                    </div>
                @endif

                @if($first = $this->top3->get(0))
                    <div class="flex flex-col items-center w-28 sm:w-36 relative z-20">
                        <div class="absolute -top-10 text-amber-400 text-3xl animate-bounce">👑</div>
                        <div class="flex flex-col items-center mb-3 text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-amber-100 border-4 border-amber-400 shadow-lg overflow-hidden flex items-center justify-center relative z-20 ring-4 ring-amber-400/20">
                                @if($first->avatar_path)
                                    <img src="{{ asset('storage/'.$first->avatar_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-bold text-amber-600 text-2xl">{{ $first->initials() }}</span>
                                @endif
                            </div>
                            <span class="font-black text-slate-900 text-base sm:text-lg mt-2 truncate w-full">{{ $first->name }}</span>
                            <span class="font-black text-amber-600 text-sm sm:text-base">{{ $first->total_points }} pts</span>
                        </div>
                        <div class="w-full h-32 sm:h-44 bg-gradient-to-t from-amber-300 to-amber-200 rounded-t-xl border-t-4 border-amber-400 shadow-inner flex justify-center pt-2">
                            <span class="font-black text-amber-600 text-3xl">1</span>
                        </div>
                    </div>
                @endif

                @if($third = $this->top3->get(2))
                    <div class="flex flex-col items-center w-24 sm:w-32 relative z-0">
                        <div class="absolute -top-10 text-amber-400 text-3xl">🥉</div>
                        <div class="flex flex-col items-center mb-3 text-center">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-orange-100 border-4 border-amber-700/60 shadow-md overflow-hidden flex items-center justify-center relative z-20">
                                @if($third->avatar_path)
                                    <img src="{{ asset('storage/'.$third->avatar_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="font-bold text-amber-800 text-xl">{{ $third->initials() }}</span>
                                @endif
                            </div>
                            <span class="font-bold text-slate-800 text-sm sm:text-base mt-2 truncate w-full">{{ $third->name }}</span>
                            <span class="font-black text-amber-700/80 text-xs sm:text-sm">{{ $third->total_points }} pts</span>
                        </div>
                        <div class="w-full h-16 sm:h-24 bg-gradient-to-t from-amber-700/40 to-amber-700/30 rounded-t-xl border-t-4 border-amber-700/60 shadow-inner flex justify-center pt-2">
                            <span class="font-black text-amber-900/50 text-2xl">3</span>
                        </div>
                    </div>
                @endif

            </div>
        @endif

        @if($this->next7 && $this->next7->count() > 0)
            <div class="bg-white border border-slate-200 rounded-tl-2xl rounded-tr-2xl shadow-sm overflow-hidden">
                <ul class="divide-y divide-slate-100">
                    @foreach($this->next7 as $index => $user)
                        @php 
                            $rank = $index + 4; // Puisque ce sont les joueurs de 4 à 10
                            $isUser = $user->id === auth()->id();
                        @endphp
                        <li class="flex items-center justify-between p-4 sm:px-6 hover:bg-slate-50 transition-colors {{ $isUser ? 'bg-indigo-50/50' : '' }}">
                            <div class="flex items-center gap-4">
                                <span class="w-6 text-center font-bold text-slate-400 text-sm sm:text-base">{{ $rank }}</span>
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                    @if($user->avatar_path)
                                        <img src="{{ asset('storage/'.$user->avatar_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-slate-500 font-bold text-xs">{{ $user->initials() }}</span>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm sm:text-base {{ $isUser ? 'text-indigo-700' : 'text-slate-800' }}">
                                        {{ $user->name }} {!! $isUser ? '<span class="text-indigo-500 text-xs ml-1">(Toi)</span>' : '' !!}
                                    </span>
                                    <span class="hidden sm:inline-flex text-[10px] sm:text-xs text-slate-400 items-center gap-2 mt-0.5">
                                        <span title="Scores exacts">🎯 {{ $user->exact_count }}</span>
                                        <span title="Bonnes tendances">📈 {{ $user->trend_count }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="font-black text-slate-700 text-base sm:text-lg text-right">
                                {{ $user->total_points }} <span class="text-xs text-slate-400 font-bold uppercase">pts</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!$this->top3->contains('id', auth()->id()) && !$this->next7->contains('id', auth()->id()))
            <div class="relative py-1">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-dashed border-slate-300"></div>
                </div>
            </div>

            @if($this->contextPlayers && $this->contextPlayers->count() > 0)
                <div class="bg-white border border-slate-200 rounded-bl-2xl rounded-br-2xl shadow-sm overflow-hidden">
                    <ul class="divide-y divide-slate-100">
                        @foreach($this->contextPlayers as $user)
                            @php 
                                $isUser = $user->id === auth()->id();
                            @endphp
                            <li class="flex items-center justify-between p-4 sm:px-6 transition-colors {{ $isUser ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'hover:bg-slate-50' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-8 text-center font-bold {{ $isUser ? 'text-indigo-600' : 'text-slate-400' }} text-sm sm:text-base">
                                        {{ $user->rank }}
                                    </span>
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                        @if($user->avatar_path)
                                            <img src="{{ asset('storage/'.$user->avatar_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-slate-500 font-bold text-xs">{{ $user->initials() }}</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-sm sm:text-base {{ $isUser ? 'text-indigo-800' : 'text-slate-800' }}">
                                            {{ $user->name }}
                                        </span>
                                        <span class="hidden sm:inline-flex text-[10px] sm:text-xs text-slate-400 items-center gap-2 mt-0.5">
                                            <span title="Scores exacts">🎯 {{ $user->exact_count }}</span>
                                            <span title="Bonnes tendances">📈 {{ $user->trend_count }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="font-black {{ $isUser ? 'text-indigo-700' : 'text-slate-700' }} text-base sm:text-lg text-right">
                                    {{ $user->total_points }} <span class="text-xs {{ $isUser ? 'text-indigo-400' : 'text-slate-400' }} font-bold uppercase">pts</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
    </div>
    <div class="w-full lg:w-1/3 sticky top-24">
        @livewire('leagues.private.discussion', [$league->id])
    </div>

    @if($isUserAdmin)
        <div x-show="showAdminModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showAdminModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showAdminModal" @click.outside="showAdminModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                            <h3 class="text-lg font-black text-slate-900" id="modal-title">Gérer la ligue</h3>
                            <button @click="showAdminModal = false" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer touch-manipulation">
                                <span class="sr-only">Fermer</span>
                                <svg class="h-6 w-6 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-5 space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Code d'invitation</label>
                                <div class="flex items-center gap-3">
                                    <div class="bg-indigo-50 border border-indigo-200 text-indigo-700 font-mono font-black text-xl px-4 py-3 rounded-xl w-full text-center tracking-widest">
                                        {{ $league->invite_code }}
                                    </div>
                                    <button onclick="navigator.clipboard.writeText('{{ $league->invite_code }}'); alert('Code copié !');" class="bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-xl transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">Partage ce code pour inviter de nouveaux joueurs à rejoindre la ligue.</p>
                            </div>

                            <hr class="border-slate-100">

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                                <div class="flex items-center gap-3">
                                    <textarea wire:model.live.debounce.200ms="description" rows="5" class="w-full border border-slate-200 rounded-xl px-3 py-2">
                                        {{ $description }}
                                    </textarea>
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3 flex justify-between items-end">
                                    Membres de la ligue
                                    <span class="text-xs font-normal text-slate-400 bg-slate-100 px-2 py-0.5 rounded">{{ $this->allMembers->count() }} joueurs</span>
                                </label>

                                <div class="max-h-60 overflow-y-auto border border-slate-200 rounded-xl divide-y divide-slate-100">
                                    @foreach($this->allMembers as $member)
                                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 transition-colors">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                                    @if($member->avatar_path)
                                                        <img src="{{ asset('storage/'.$member->avatar_path) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <span class="text-slate-500 font-bold text-xs">{{ $member->initials() }}</span>
                                                    @endif
                                                </div>
                                                <span class="font-bold text-sm text-slate-700">{{ $member->name }}</span>
                                                @if($member->id === auth()->id())
                                                    <span class="bg-indigo-100 text-indigo-700 text-[10px] px-2 py-0.5 rounded font-bold uppercase">Admin</span>
                                                @endif
                                            </div>

                                            @if($member->id !== auth()->id())
                                                <button
                                                    wire:click="removeUser({{ $member->id }})"
                                                    wire:confirm="Es-tu sûr de vouloir expulser {{ $member->name }} de la ligue ? Cette action est immédiate."
                                                    class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded transition-colors cursor-pointer touch-manipulation"
                                                    title="Expulser le joueur"
                                                >
                                                    <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end">
                            <button @click="showAdminModal = false" type="button" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-xl text-sm font-bold transition-colors cursor-pointer touch-manipulation">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>