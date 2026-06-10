<div class="max-w-4xl mx-auto px-4 py-8 space-y-10">
    
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900">Mon Profil</h1>
    </div>

    @if (session('status'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)"
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
            <div class="flex-shrink-0 text-emerald-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800">{{ session('status') }}</p>
            </div>

            <button @click="show = false" class="flex-shrink-0 text-gray-400 transition-colors hover:text-gray-600 focus:outline-none cursor-pointer touch-manipulation">
                <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-10">
        
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-900">Identité & Stratégie</h2>
                <p class="text-sm text-slate-500">Mets à jour tes informations et choisis ton favori pour le titre !</p>
            </div>
            
            <form wire:submit="updateProfile" class="p-6 sm:p-8 space-y-6">
                
                <div class="flex items-center gap-6 pb-6 border-b border-slate-100">
                    <div class="relative h-20 w-20 sm:h-24 sm:w-24 rounded-full overflow-hidden bg-indigo-50 border-4 border-white shadow-md shrink-0 flex items-center justify-center">
                        @if ($photo && !$errors->has('photo'))
                            <img src="{{ $photo->temporaryUrl() }}" class="object-cover h-full w-full">
                        @elseif (auth()->user()->avatar_path)
                            <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" class="object-cover h-full w-full">
                        @else
                            <span class="font-bold text-indigo-300 text-3xl">{{ auth()->user()->initials() }}</span>
                        @endif

                        <div wire:loading wire:target="photo" class="absolute inset-0 bg-white/70 flex items-center justify-center">
                            <span class="animate-spin h-6 w-6 border-2 border-indigo-600 border-t-transparent rounded-full"></span>
                        </div>
                    </div>

                    <div>
                        <label for="photo-upload" class="cursor-pointer bg-white px-4 py-2 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all shadow-sm inline-block">
                            Changer la photo
                        </label>
                        <input id="photo-upload" type="file" wire:model="photo" class="hidden" accept="image/jpeg, image/png, image/webp">
                        <p class="text-xs text-slate-400 mt-2 font-medium">JPG, PNG ou WEBP. Max 2 Mo.</p>
                        @error('photo') <span class="text-red-500 text-xs mt-1 font-bold block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pseudo</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-all px-2 py-1">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse Email</label>
                        <input type="email" wire:model="email" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-all px-2 py-1">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                @if(!now()->isAfter($firstGame->kickoff_at))
                    <div class="pt-4 border-t border-slate-50">
                        <label class="block text-sm font-bold text-slate-900 mb-2">🏆 Choisis ton Poney (le vainqueur final)</label>
                        <select wire:model="winner_team_id" class="w-full rounded-xl border-slate-200 bg-indigo-50/30 focus:border-indigo-500 focus:ring-indigo-500 transition-all px-2 py-1">
                            <option value="">-- Sélectionne une nation --</option>
                            @foreach($this->teams() as $team)
                                <option value="{{ $team->id }}">{{ $team->nameFr }}</option>
                            @endforeach
                        </select>
                        <p class="px-4 py-2 border border-red-600 text-red-600 bg-red-500/10 rounded-lg mt-2 text-xs italic bold">Attention : une fois le tournoi démarré, ce choix ne pourra plus être modifié !</p>
                        @error('winner_team_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                @else
                    <div class="pt-4 border-t border-slate-50">
                        <p class="block text-sm font-bold text-slate-900 mb-2">
                            Trop tard pour choisir ou modifier ton prono sur le vainqueur final...
                        </p>
                        @if(auth()->user()->winner_team_id)
                            Tu as choisi comme Poney : <span class="font-bold italic">{{ auth()->user()->winnerTeam->nameFr }}</span>
                        @else
                            <span class="font-bold italic">Dommage, tu n'as pas choisi de Poney :(</span>
                        @endif
                    </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-200 transition-all flex items-center gap-2">
                        <span wire:loading wire:target="updateProfile" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </section>

        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-900 text-red-600">Sécurité du compte</h2>
                <p class="text-sm text-slate-500">Modifie ton mot de passe pour sécuriser tes pronos.</p>
            </div>

            <form wire:submit="updatePassword" class="p-6 sm:p-8 space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mot de passe actuel</label>
                    <input type="password" wire:model="current_password" class="w-full max-w-md rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-all px-2 py-1">
                    @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                    <div x-data="{ 
                        password: '', 
                        score: 0,
                        
                        // Calcule de l'entropie
                        calculateScore() {
                            let s = 0;
                            let R = 0;
                            if (this.password.length === 0) { this.score = 0; return; }
                            if (/[a-z]/.test(this.password)) R += 26; // 26 lettre minuscules
                            if (/[A-Z]/.test(this.password)) R += 26; // 26 lettre majuscules
                            if (/[0-9]/.test(this.password)) R += 10; // 10 chiffres
                            if (/[^A-Za-z0-9]/.test(this.password)) R += 32; // 32 caractères spéciaux
                            
                            if (this.password.length < 11) 
                                s = 1;
                            else
                                s = this.password.length * Math.log2(R);
                            
                            this.score = s;
                        },

                        get feedbackText() {
                            if (this.password.length === 0) return 'Un mot de passe sécurisé sera un plus !';
                            if (this.score < 64) return 'Aussi fragile qu\'un poney nain qui tente une Panenka avec ses sabots... (' + Math.round(this.score) + ' bits)';
                            if (this.score < 80) return 'C\'est niveau foot du dimanche : le poney court vite, mais il a oublié le ballon. (' + Math.round(this.score) + ' bits)';
                            if (this.score < 100) return 'Solide ! Ce destrier tacle proprement et a clairement le niveau Ligue des Champions. (' + Math.round(this.score) + ' bits)';
                            if (this.score < 128) return 'Un véritable mur ! Même Mbappé monté sur un pur-sang ne passerait pas cette défense. (' + Math.round(this.score) + ' bits)';
                            return 'Incassable. C\'est un Pégase cyborg qui arrête les penalties de Messi d\'un simple regard ! (' + Math.round(this.score) + ' bits)';
                        },

                        get feedbackClass() {
                            if (this.password.length === 0) return 'border-slate-300 text-slate-500 bg-slate-50';
                            if (this.score < 64) return 'border-red-600 text-red-600 bg-red-500/10';
                            if (this.score < 80) return 'border-orange-600 text-orange-600 bg-orange-500/10';
                            if (this.score < 100) return 'border-yellow-600 text-yellow-600 bg-yellow-500/10';
                            if (this.score < 128) return 'border-green-600 text-green-600 bg-green-500/10';
                            return 'border-emerald-600 text-emerald-600 bg-emerald-500/10';
                        }
                    }">
                    
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nouveau mot de passe</label>
                    
                    <input type="password" 
                        wire:model="new_password" 
                        x-model="password" 
                        @input="calculateScore()"
                        class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-all px-2 py-1">
                    
                    @error('new_password') 
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                    @enderror

                    <p :class="feedbackClass" 
                    x-text="feedbackText" 
                    class="text-center px-4 py-2 border rounded-lg mt-3 text-xs italic font-bold transition-colors duration-300">
                    </p>
                </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Confirmer le mot de passe</label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 transition-all px-2 py-1">
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-slate-900 hover:bg-black text-white px-8 py-2.5 rounded-xl font-bold shadow-md shadow-slate-200 transition-all px-2 py-1">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </section>

        {{-- Notifications --}}
        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-900">Notifications par e-mail</h2>
                <p class="text-sm text-slate-500">Reçois des e-mails automatiques pour ne rien rater.</p>
            </div>

            <form wire:submit="updateNotifications" class="p-6 sm:p-8 space-y-5">

                <label class="flex items-start gap-4 cursor-pointer group">
                    <div class="relative mt-0.5 shrink-0">
                        <input type="checkbox" wire:model="notify_match_reminder" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 rounded-full peer-checked:bg-indigo-600 transition-colors duration-200 peer-focus:ring-2 peer-focus:ring-indigo-400 peer-focus:ring-offset-1"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 group-hover:text-indigo-700 transition-colors">
                            Rappel avant les matchs
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Reçois un e-mail ~12h avant chaque match pour lequel tu n'as pas encore posé de prono.
                        </p>
                    </div>
                </label>

                <div class="border-t border-slate-100"></div>

                <label class="flex items-start gap-4 cursor-pointer group">
                    <div class="relative mt-0.5 shrink-0">
                        <input type="checkbox" wire:model="notify_daily_recap" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 rounded-full peer-checked:bg-indigo-600 transition-colors duration-200 peer-focus:ring-2 peer-focus:ring-indigo-400 peer-focus:ring-offset-1"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 group-hover:text-indigo-700 transition-colors">
                            Récapitulatif quotidien
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Reçois chaque soir un e-mail avec les résultats du jour, tes points gagnés et ton classement.
                        </p>
                    </div>
                </label>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-200 transition-all flex items-center gap-2">
                        <span wire:loading wire:target="updateNotifications" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        Enregistrer
                    </button>
                </div>
            </form>
        </section>

    </div>
</div>