<div class="max-w-2xl mx-auto px-4 py-8 space-y-6">
    <div>
        <a href="{{ route('leagues') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour aux ligues
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Créer une Ligue</h1>
        <p class="text-slate-500 mt-1">Prépare le terrain pour affronter tes amis ou tes collègues.</p>
    </div>

    <form wire:submit="save" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 sm:p-8 space-y-8">

            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nom de la ligue <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <input type="text" id="name" wire:model="name" placeholder="Ex: Les collègues de la compta" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all">
                </div>
                @error('name') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Description <span class="text-slate-400 font-normal">(Optionnel)</span></label>
                <textarea id="description" wire:model="description" rows="3" placeholder="Petit mot de bienvenue ou règles spécifiques pour les perdants (ex: le dernier paie sa tournée)..." class="w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all"></textarea>
                @error('description') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex gap-4 items-start">
                <div class="bg-white p-2 rounded-full shadow-sm shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-900">Ligue Privée</h4>
                    <p class="text-xs text-indigo-700 mt-1 leading-relaxed">
                        Ta ligue sera sécurisée. Une fois créée, tu recevras un code d'invitation unique (ex: PONEY26) à transmettre à tes amis pour qu'ils puissent la rejoindre.
                    </p>
                </div>
            </div>

        </div>

        <div class="bg-slate-50 px-6 py-5 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('leagues') }}" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-200 rounded-xl transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Créer la ligue</span>
                <span wire:loading wire:target="save">Création en cours...</span>
                
                <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                
                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>
    </form>
</div>