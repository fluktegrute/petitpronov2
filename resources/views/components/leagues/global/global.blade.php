<div class="max-w-4xl mx-auto px-4 py-8 space-y-4">
    
    <div class="text-center space-y-2">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Classement Général</h1>
        <p class="text-slate-500">Alors, bien placé ?</p>
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