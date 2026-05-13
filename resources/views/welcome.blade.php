<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.1.19/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.1.19/dist/trix.umd.min.js"></script>

    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col font-sans antialiased text-slate-900 selection:bg-indigo-500 selection:text-white">
    <main class="flex-grow">
        <div class="min-h-screen bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white">
            <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <a href="/" class="flex-shrink-0 flex items-center gap-3">
                                <img class="h-10 w-auto" src="{{ asset('storage/images/logo.png') }}" alt="Logo">
                                <span class="font-extrabold text-xl tracking-tight text-indigo-950 sm:block">{{ config('app.name') }}</span>
                            </a>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-black shadow-md shadow-indigo-200 transition-all uppercase tracking-widest">
                                S'inscrire
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <section class="py-16 sm:py-24 bg-white border-b border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    
                    <div class="space-y-6 text-center md:text-left">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Défie tes amis.<br>
                            Sans pression.<br>
                            <span class="text-indigo-600">Juste pour la gloire.</span>
                        </h1>
                        <p class="text-lg text-slate-600 font-medium max-w-xl mx-auto md:mx-0">
                            {{ config('app.name') }} simplifie les pronostics. Entre amis, collègues ou famille, crée ta ligue en 2 clics et vibrez ensemble à chaque match.
                        </p>
                        <div class="pt-4 flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl text-base font-black shadow-lg shadow-indigo-200 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                                Créer une Ligue
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </a>
                            <a href="#how" class="bg-slate-100 hover:bg-slate-200 text-slate-800 px-8 py-4 rounded-2xl text-base font-bold transition-all flex items-center justify-center gap-2">
                                Comment ça marche ?
                            </a>
                        </div>
                    </div>

                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-indigo-100/70 border-4 border-white transform md:rotate-2">
                        <img src="{{ asset('storage/images/friends.png') }}" class="w-full h-auto">
                        <div class="absolute inset-0 bg-gradient-to-t from-indigo-950/20 to-transparent"></div>
                    </div>
                </div>
            </section>

            <section class="py-20 sm:py-28 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
                    <div class="text-center max-w-2xl mx-auto space-y-3">
                        <div>
                            <span class="text-xs font-black text-indigo-500 uppercase tracking-widest bg-indigo-100 px-3 py-1 rounded-full border border-indigo-200">La Philosophie</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Le plaisir du jeu, rien que le jeu</h2>
                        <p class="text-slate-600 font-medium">{{ config('app.name') }} est une arène ludique où la seule monnaie est le respect de vos proches (et le droit de les chambrer).</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8">
                        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 transition-all hover:shadow-lg hover:border-indigo-100 hover:-translate-y-1">
                            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center border border-amber-200 shadow-inner">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900">100% Plaisir</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">Le but est de vibrer ensemble devant les matchs. Redécouvre la joie simple du sport entre proches.</p>
                        </div>

                        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 transition-all hover:shadow-lg hover:border-indigo-100 hover:-translate-y-1">
                            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-200 shadow-inner">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900">Chambrage Garanti</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">Grâce au chat intégré dans chaque ligue privée, félicite les gagnants et charrie les perdants sans modération.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="how" class="py-20 sm:py-28 bg-white border-y border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
                    <div class="text-center max-w-xl mx-auto space-y-3">
                        <div>
                            <span class="text-xs font-black text-emerald-500 uppercase tracking-widest bg-emerald-100 px-3 py-1 rounded-full border border-emerald-200">Simplicité d'abord</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Le terrain est prêt en 3 étapes</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center relative">
                        <div class="absolute top-12 left-1/3 right-1/3 h-0.5 border-t-2 border-dashed border-slate-200 hidden md:block" aria-hidden="true"></div>
                        
                        <div class="relative z-10 flex flex-col items-center p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center font-black text-2xl text-indigo-600 border-4 border-slate-100 shadow-inner mb-6">1</div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Inscris-toi</h4>
                            <p class="text-sm text-slate-500">Crée ton profil de joueur en 30 secondes chrono.</p>
                        </div>

                        <div class="relative z-10 flex flex-col items-center p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center font-black text-2xl text-indigo-600 border-4 border-slate-100 shadow-inner mb-6">2</div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Crée ta Ligue</h4>
                            <p class="text-sm text-slate-500">et invite tes proches.</p>
                        </div>

                        <div class="relative z-10 flex flex-col items-center p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center font-black text-2xl text-indigo-600 border-4 border-slate-100 shadow-inner mb-6">3</div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Lâche tes Pronos</h4>
                            <p class="text-sm text-slate-500">Mise sur les scores et cumule les points.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-24 sm:py-32 bg-slate-950 relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.15)_0,transparent_70%)]"></div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">
                    
                    <div class="space-y-6 text-center md:text-left">
                        <h2 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">
                            Prêt à devenir<br>le <span class="text-amber-400">Pony d'Or</span> ?
                        </h2>
                        <p class="text-lg text-slate-300 font-medium max-w-xl mx-auto md:mx-0">
                            N'attends pas le coup d'envoi. Rejoins la communauté {{ config('app.name') }} dès aujourd'hui et prépare tes meilleurs pronos pour la gloire.
                        </p>
                        <div class="pt-4 flex justify-center md:justify-start">
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl text-base font-black shadow-lg shadow-indigo-500/50 transition-all uppercase tracking-widest flex items-center gap-2">
                                Créer mon compte
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </a>
                        </div>
                    </div>

                    <div class="flex justify-center md:justify-end">
                        <div class="relative w-full max-w-sm rounded-3xl overflow-hidden border-4 border-slate-700 shadow-2xl shadow-indigo-950 transform md:-rotate-2">
                            <img src="{{ asset('storage/images/winner.png') }}" alt="Pony avec un trophée et une couronne dans un stade" class="w-full h-auto">
                            <div class="absolute inset-0 bg-gradient-to-t from-indigo-950/30 to-transparent"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <footer class="b-0 bg-white border-t border-slate-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} {{ config('app.name') }} - Play for the fun !
            </p>
        </div>
    </footer>
    @livewireScripts
</body>
</html>