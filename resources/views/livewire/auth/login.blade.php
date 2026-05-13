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
    <main class="flex-grow py-10">
        <div class="min-h-[80vh] flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md space-y-8">
                
                <div class="text-center">
                    <img class="mx-auto h-16 w-auto drop-shadow-sm" src="{{ asset('storage/images/logo.png') }}" alt="{{ config('app.name') }} Logo">
                    <h2 class="mt-6 text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Bon retour dans l'arène !
                    </h2>
                    <p class="mt-2 text-sm text-slate-500 font-medium">
                        Prêt à lâcher tes meilleurs pronostics ?
                    </p>
                </div>
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden transition-all hover:shadow-2xl hover:shadow-slate-200/60">
                    <div class="p-6 sm:p-8">
                        
                        @if (session('status'))
                            <div class="mb-6 font-bold text-sm text-emerald-700 bg-emerald-50 p-4 rounded-xl border border-emerald-200 flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                            @csrf

                            <div>
                                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Adresse Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                    </div>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="ton@email.com" class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Mot de passe</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center">
                                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 bg-slate-50 cursor-pointer transition-all">
                                    <label for="remember_me" class="ml-2 block text-sm font-bold text-slate-600 cursor-pointer select-none">
                                        Rester connecté
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <div class="text-sm">
                                        <a href="{{ route('password.request') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                            Mot de passe oublié ?
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-md shadow-indigo-200/50 text-sm font-black text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all uppercase tracking-widest">
                                    Se connecter
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 text-center">
                        <p class="text-sm text-slate-600 font-medium">
                            Pas encore de compte ? 
                            <a href="{{ route('register') }}" class="font-black text-indigo-600 hover:text-indigo-800 transition-colors ml-1">
                                Rejoins la compétition !
                            </a>
                        </p>
                    </div>
                </div>
            </div>
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