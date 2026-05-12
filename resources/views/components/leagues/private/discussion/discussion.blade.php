<div wire:poll.10s class="bg-white rounded-3xl border border-slate-200 shadow-sm flex flex-col h-[80vh] overflow-hidden">
    
    <div class="bg-indigo-900 px-5 py-4 border-b border-indigo-800 flex justify-between items-center shrink-0">
        <h3 class="font-black text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Le Vestiaire
        </h3>
        <span class="bg-indigo-800 text-indigo-200 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-widest">
            {{ $this->messages->count() }} messages
        </span>
    </div>

    <div class="flex-1 overflow-y-auto p-4 flex flex-col-reverse bg-slate-50/50 gap-4">
        @forelse($this->messages as $msg)
            @php $isUser = $msg->user_id === auth()->id(); @endphp
            
            <div class="flex gap-3 {{ $isUser ? 'flex-row-reverse' : '' }}">
                <div class="shrink-0 w-8 h-8 rounded-full bg-slate-200 border border-slate-300 flex items-center justify-center overflow-hidden">
                    @if($msg->user->avatar_path)
                        <img src="{{ asset('storage/' . $msg->user->avatar_path) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xs font-bold text-slate-500">{{ $msg->user->initials() }}</span>
                    @endif
                </div>

                <div class="flex flex-col {{ $isUser ? 'items-end' : 'items-start' }} max-w-[85%]">
                    <div class="flex items-baseline gap-2 mb-1">
                        <span class="text-xs font-bold text-slate-700">{{ $isUser ? 'Moi' : $msg->user->name }}</span>
                        <span class="text-[10px] text-slate-400">{{ $msg->created_at->locale('fr')->diffForHumans() }}</span>
                    </div>
                    
                    <div class="prose prose-sm max-w-none {{ $isUser ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-none' : 'bg-white text-slate-800 rounded-2xl rounded-tl-none border border-slate-200' }} px-4 py-2 shadow-sm [&_img]:rounded-lg [&_img]:max-w-full [&_a]:underline {{ $isUser ? '[&_a]:text-indigo-200' : '[&_a]:text-indigo-600' }}">
                        {!! $msg->content !!}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-slate-400 space-y-2 opacity-50">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z"></path>
                </svg>
                <p class="text-sm font-bold">Soyez le premier à chambrer !</p>
            </div>
        @endforelse
    </div>

    <div class="p-3 bg-white border-t border-slate-200 shrink-0" 
         x-data="{
            value: @entangle('body'),
            init() {
                window.addEventListener('message-sent', () => {
                    this.$refs.trix.editor.setSelectedRange([0, 65535]);
                    this.$refs.trix.editor.deleteInDirection('forward');
                });
            }
         }"
         @trix-change="value = $event.target.value"
         @trix-file-accept="$event.preventDefault()"> <form wire:submit="sendMessage" class="relative">
            <input id="trix-input" type="hidden">
            
            <style>
                /* Masque le bouton d'upload */
                trix-toolbar .trix-button-group--file-tools { 
                    display: none !important; 
                }

                /* Autorise le retour à la ligne et réduit l'espacement global */
                trix-toolbar .trix-button-row { 
                    flex-wrap: wrap !important; 
                    gap: 4px !important;
                    justify-content: flex-start !important;
                }
                
                /* Retire les marges entre les groupes */
                trix-toolbar .trix-button-group { 
                    margin: 0 0 4px 0 !important; 
                }

                /* Compacte les boutons */
                trix-toolbar .trix-button {
                    min-width: 28px !important;
                    height: 28px !important;
                    padding: 0 4px !important;
                }
            </style>

            <div wire:ignore class="rounded-xl overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition-all [&_trix-toolbar]:border-b-0 [&_trix-toolbar]:bg-slate-50 [&_trix-toolbar]:px-2 [&_trix-toolbar]:pt-2 [&_trix-editor]:min-h-[80px] [&_trix-editor]:max-h-[150px] [&_trix-editor]:overflow-y-auto [&_trix-editor]:bg-white [&_trix-editor]:border-none">
                
                <trix-editor x-ref="trix" input="trix-input" class="prose prose-sm focus:outline-none" placeholder="Participe au débat !"></trix-editor>
            
            </div>

            <div class="flex justify-between items-center mt-2">
                @error('body') <span class="text-xs text-red-500 font-bold ml-1">{{ $message }}</span> @enderror
                
                <button type="submit" class="ml-auto bg-indigo-600 hover:bg-indigo-700 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors shadow-sm" title="Envoyer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 ml-0.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>