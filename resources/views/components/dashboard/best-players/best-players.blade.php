<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full transition-all hover:shadow-md">
    
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
        <h3 class="font-black text-slate-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            Général
        </h3>
        <a href="{{ route('leagues.global') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-widest">
            Voir tout
        </a>
    </div>

    <div class="bg-slate-800 p-5 relative overflow-hidden shrink-0 border-b border-slate-200">
        <svg class="absolute top-0 right-0 text-slate-700 w-24 h-24 transform translate-x-4 -translate-y-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
        
        <div class="relative z-10 flex justify-between items-end">
            <div>
                <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Ma position</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl sm:text-4xl font-black text-white">{{ $userRank }}</span>
                    <span class="text-sm font-medium text-slate-500">/ {{ $totalPlayers }}</span>
                </div>
            </div>
            <div class="text-right">
                <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Points</span>
                <span class="text-2xl font-black text-emerald-400">{{ auth()->user()->total_points }}</span>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto bg-white">
        <div class="px-4 py-2 bg-slate-50 border-b border-slate-100">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Le Podium</span>
        </div>
        <ul class="divide-y divide-slate-50">
            @forelse($this->top3 as $index => $player)
                @php 
                    $rank = $index + 1;
                    $isUser = $player->id === auth()->id();
                @endphp
                <li class="flex items-center justify-between p-3 sm:px-5 hover:bg-slate-50 transition-colors {{ $isUser ? 'bg-indigo-50/30' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-6 text-center font-black text-lg {{ $rank === 1 ? 'text-amber-400 drop-shadow-sm' : ($rank === 2 ? 'text-slate-400' : 'text-amber-700/70') }}">
                            {{ $rank }}
                        </div>
                        
                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                            @if($player->avatar_path)
                                <img src="{{ asset('storage/'.$player->avatar_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-slate-500 font-bold text-xs">{{ $player->initials() }}</span>
                            @endif
                        </div>
                        
                        <span class="font-bold text-sm truncate max-w-[100px] sm:max-w-[140px] {{ $isUser ? 'text-indigo-700' : 'text-slate-700' }}">
                            {{ $player->name }}
                        </span>
                    </div>
                    
                    <div class="font-black text-slate-800 text-sm">
                        {{ $player->total_points }} <span class="text-[10px] text-slate-400 font-bold uppercase hidden sm:inline">pts</span>
                    </div>
                </li>
            @empty
                <div class="p-4 text-center text-slate-500 text-sm">
                    Aucun joueur classé.
                </div>
            @endforelse
        </ul>
    </div>
</div>