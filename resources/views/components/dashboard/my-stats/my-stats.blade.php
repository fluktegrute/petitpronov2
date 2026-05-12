<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden flex flex-col transition-all hover:shadow-md">
    
    <div class="bg-indigo-600 px-6 py-8 text-center relative overflow-hidden shrink-0">
        <div class="relative z-10 flex flex-col items-center">
            <span class="text-[11px] sm:text-xs font-bold text-indigo-200 uppercase tracking-widest mb-1">
                Total des Points
            </span>
            <span class="text-5xl sm:text-6xl font-black text-white tracking-tight drop-shadow-sm">
                {{ $this->user->total_points }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-3 divide-x divide-slate-100 bg-slate-50 flex-grow">
        
        <div class="p-4 sm:p-5 flex flex-col items-center justify-center text-center hover:bg-emerald-50 transition-colors group">
            <span class="text-xl sm:text-2xl mb-1 opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all">🎯</span>
            <span class="text-2xl sm:text-3xl font-black text-slate-800 group-hover:text-emerald-700 transition-colors">
                {{ $this->user->exact_count }}
            </span>
            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 group-hover:text-emerald-600 uppercase tracking-widest mt-1">
                Exacts
            </span>
        </div>

        <div class="p-4 sm:p-5 flex flex-col items-center justify-center text-center hover:bg-blue-50 transition-colors group">
            <span class="text-xl sm:text-2xl mb-1 opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all">📈</span>
            <span class="text-2xl sm:text-3xl font-black text-slate-800 group-hover:text-blue-700 transition-colors">
                {{ $this->user->trend_count }}
            </span>
            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 group-hover:text-blue-600 uppercase tracking-widest mt-1">
                Tendances
            </span>
        </div>

        <div class="p-4 sm:p-5 flex flex-col items-center justify-center text-center hover:bg-slate-100 transition-colors group">
            <span class="text-xl sm:text-2xl mb-1 opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all">📝</span>
            <span class="text-2xl sm:text-3xl font-black text-slate-800 transition-colors">
                {{ $this->user->prono_count }}
            </span>
            <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                Joués
            </span>
        </div>

    </div>
</div>