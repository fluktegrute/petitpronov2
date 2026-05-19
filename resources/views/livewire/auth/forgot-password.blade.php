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
                        C'est une commotion ?
                    </h2>
                    <p class="mt-2 text-sm text-slate-500 font-medium">
                        Entre ton adresse mail pour recevoir un lien de réinitialisation
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

                        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
                            @csrf

                            <flux:input
                                name="email"
                                :label="'Adresse e-mail'"
                                type="email"
                                required
                                autofocus
                                placeholder="super@poney.com"
                            />

                            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                                Recevoir un mail de réinitialisation
                            </flux:button>
                        </form>
                    </div>
                    
                    <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 text-center">
                        <p class="text-sm text-slate-600 font-medium">
                            Ça t'est revenu ? 
                            <a href="{{ route('login') }}" class="font-black text-indigo-600 hover:text-indigo-800 transition-colors ml-1">
                                Connecte-toi !
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