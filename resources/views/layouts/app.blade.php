<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PonyBet</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.1.19/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.1.19/dist/trix.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col font-sans antialiased text-slate-900 selection:bg-indigo-500 selection:text-white">

    <nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex">
                    <a href="{{ route('dashboard') }}" class="flex-shrink-0 flex items-center gap-3">
                        <img class="h-10 w-auto" src="{{ asset('storage/images/logo.png') }}" alt="Logo">
                        <span class="font-extrabold text-xl tracking-tight text-indigo-950 hidden sm:block">PonyBet</span>
                    </a>

                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="{{ route('matches') }}" @class([
                            'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors',
                            'border-indigo-500 text-slate-900' => request()->routeIs('matches'),
                            'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' => !request()->routeIs('matches'),
                        ])>
                            Matches
                        </a>
                        <a href="{{ route('standings') }}" @class([
                            'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors',
                            'border-indigo-500 text-slate-900' => request()->routeIs('standings'),
                            'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' => !request()->routeIs('standings'),
                        ])>
                            Classement
                        </a>
                        <a href="{{ route('leagues') }}" @class([
                            'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors',
                            'border-indigo-500 text-slate-900' => request()->routeIs('leagues'),
                            'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' => !request()->routeIs('leagues'),
                        ])>
                            Mes ligues
                        </a>
                    </div>
                </div>

                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    
                    @auth
                        <div class="relative ml-3">
                            <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" type="button" class="flex items-center max-w-xs bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Ouvrir le menu utilisateur</span>
                                <div class="h-9 w-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                                    @if(auth()->user()->avatar_path)
                                        <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ auth()->user()->initials() }}
                                    @endif
                                </div>
                                <span class="ml-2 text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>
                                <svg class="ml-1 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="userMenuOpen" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                 class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-xl bg-white py-1 shadow-lg ring-1 ring-slate-700 ring-opacity-5 focus:outline-none" 
                                 style="display: none;">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs font-medium text-slate-500">Boosts restants: <span class="font-bold text-indigo-600">{{ auth()->user()->boosts_remaining ?? 5 }}</span></p>
                                </div>
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Mon profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Déconnexion</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>

                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center rounded-md p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Ouvrir le menu principal</span>
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileMenuOpen" style="display: none;" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" style="display: none;" class="sm:hidden border-t border-slate-200">
            <div class="space-y-1 pb-3 pt-2">
                <a href="{{ route('matches') }}" class="block border-l-4 border-indigo-500 bg-indigo-50 py-2 pl-3 pr-4 text-base font-medium text-indigo-700">Matches</a>
                <a href="{{ route('standings') }}" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-slate-500 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-700">Classements</a>
                <a href="{{ route('leagues') }}" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-slate-500 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-700">Mes Ligues</a>
            </div>
            @auth
                <div class="border-t border-slate-200 pb-3 pt-4">
                    <div class="flex items-center px-4">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-lg">
                            @if(auth()->user()->avatar_path)
                                <img src="{{ asset('storage/'.auth()->user()->avatar_path) }}" class="w-full h-full object-cover">
                            @else
                                {{ auth()->user()->initials() }}
                            @endif
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-slate-800">{{ auth()->user()->name }}</div>
                            <div class="text-sm font-medium text-slate-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-base font-medium text-slate-500 hover:bg-slate-100 hover:text-slate-800">Mon Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:bg-red-50 hover:text-red-800">Déconnexion</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <main class="flex-grow py-10">
        {{ $slot }}
    </main>

    <footer class="b-0 bg-white border-t border-slate-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} PonyBet - Play for the fun !
            </p>
        </div>
    </footer>
    @livewireScripts
</body>
</html>