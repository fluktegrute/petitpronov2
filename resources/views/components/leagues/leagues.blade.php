<div class="max-w-6xl mx-auto px-4 py-8 space-y-8" x-data="{ showCreateModal: false }">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Ligues & Classements</h1>
            <p class="text-slate-500 mt-2 text-sm sm:text-base">Mesure-toi au reste du monde ou humilie tes potes.</p>
        </div>
        <a href="{{ route('leagues.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Créer une ligue
        </a>
    </div>

    @if (session('error'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 10000)"
            x-show="show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-10"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-x-10"
            class="fixed bottom-10 right-10 z-50 flex items-center w-full max-w-sm gap-3 p-4 bg-white border border-gray-100 rounded-xl shadow-2xl"
            x-cloak
        >
            <div class="flex-shrink-0 text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800">{{ session('error') }}</p>
            </div>

            <button @click="show = false" class="flex-shrink-0 text-gray-400 transition-colors hover:text-gray-600 focus:outline-none cursor-pointer touch-manipulation">
                <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center gap-4">
                <div class="bg-indigo-50 p-3 rounded-full shrink-0">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>
                <div class="flex-grow text-center sm:text-left">
                    <h3 class="font-bold text-slate-800">Rejoindre une ligue privée</h3>
                    <p class="text-xs text-slate-500">Saisis le code secret partagé par l'administrateur.</p>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex w-full sm:w-auto gap-2">
                        <input type="text" wire:model="joinCode" placeholder="Ex: P0N3Y26" class="w-full sm:w-32 bg-slate-50 border border-slate-200 text-slate-800 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 uppercase">
                        <button wire:click="joinLeague" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors cursor-pointer touch-manipulation">
                            Go
                        </button>
                    </div>
                    @error('joinCode') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <h2 class="text-xl font-black text-slate-800 mt-8 mb-4">Mes Ligues</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($this->myLeagues as $league)
                    <a href="{{ route('leagues.private', $league->invite_code) }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all group block">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-black text-lg">
                                    {{ substr($league->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 group-hover:text-indigo-700 transition-colors">{{ $league->name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $league->usersCount }} participants</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 rounded-xl p-3 flex justify-between items-center border border-slate-100">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Ma position</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-black text-slate-800">{{ $league->my_rank ?? '-' }}</span>
                                <span class="text-xs text-slate-400 font-bold">/ {{ $league->usersCount }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-white rounded-2xl border border-slate-200 border-dashed p-8 text-center">
                        <div class="mx-auto w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <span class="text-2xl">🏇</span>
                        </div>
                        <h3 class="font-bold text-slate-700 text-lg">Tu es un loup solitaire ?</h3>
                        <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">Tu n'as rejoint aucune ligue privée pour le moment. Crée la tienne ou demande un code d'invitation à tes amis !</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-6">
                
                <div class="bg-slate-900 p-5 text-center relative overflow-hidden">
                    <svg class="absolute top-0 right-0 text-white/5 w-32 h-32 transform translate-x-8 -translate-y-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    
                    <h2 class="text-lg font-black text-white relative z-10 uppercase tracking-wider">Classement Général</h2>
                    <p class="text-indigo-200 text-xs mt-1 relative z-10">Sur l'ensemble des joueurs</p>
                    
                    <div class="mt-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-3 flex justify-between items-center relative z-10">
                        <div class="text-left">
                            <p class="text-[10px] text-slate-300 uppercase tracking-widest font-bold">Ta position</p>
                            <p class="text-white font-black text-xl">{{ $myGlobalRank }} <span class="text-xs text-slate-400 font-normal">/ {{ $totalPlayers }}</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-300 uppercase tracking-widest font-bold">Points</p>
                            <p class="text-emerald-400 font-black text-xl">{{ auth()->user()->total_points ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-0">
                    <ul class="divide-y divide-slate-100">
                        @foreach($this->globalLeaderboard as $index => $user)
                            @php
                                $rank = $index + 1;
                                $isMe = $user->id === auth()->id();
                            @endphp
                            <li class="flex items-center justify-between p-4 {{ $isMe ? 'bg-indigo-50/50' : 'hover:bg-slate-50' }} transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-6 text-center font-black text-sm {{ $rank === 1 ? 'text-amber-400' : ($rank === 2 ? 'text-slate-400' : ($rank === 3 ? 'text-amber-700' : 'text-slate-300')) }}">
                                        {{ $rank }}
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center overflow-hidden shrink-0 border border-slate-300">
                                        @if($user->avatar_path)
                                            <img src="{{ asset('storage/'.$user->avatar_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-slate-500 font-bold text-xs">{{ $user->initials() }}</span>
                                        @endif
                                    </div>
                                    <span class="font-bold text-sm {{ $isMe ? 'text-indigo-700' : 'text-slate-700' }} truncate max-w-[120px]">
                                        {{ $user->name }}
                                    </span>
                                </div>
                                <div class="font-black text-sm text-slate-800">
                                    {{ $user->total_points }} <span class="text-[10px] text-slate-400 font-bold uppercase">pts</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-slate-50 p-3 text-center border-t border-slate-100">
                    <a href="{{ route('leagues.global') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest transition-colors">Voir le classement complet</a>
                </div>
            </div>
        </div>
    </div>
</div>