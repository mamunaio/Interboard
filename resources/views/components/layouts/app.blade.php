<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Interboard ISP') }} - Billing & Router Management</title>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- CSS Framework and Custom Variables -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full text-gray-900 dark:text-gray-100 antialiased" x-data="{ mobileMenuOpen: false }">

    <!-- Desktop Sidebar (Fixed Left Layout) -->
    <aside class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 lg:z-50 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 shadow-sm">
        
        <!-- Branding Brand logo header -->
        <div class="h-20 flex items-center px-8 border-b border-gray-100 dark:border-gray-800 gap-3">
            <span class="relative flex h-5 w-5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-5 w-5 bg-indigo-600 flex items-center justify-center">
                    <svg class="h-2.5 w-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
            </span>
            <span class="text-lg font-black tracking-wider text-gray-900 dark:text-white uppercase font-mono">Interboard</span>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            
            <!-- Dashboard Link -->
            <a href="{{ route('dashboard') }}" 
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <!-- Customers Link -->
            <a href="{{ route('customers.index') }}" 
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition duration-150 {{ request()->routeIs('customers.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Customers
            </a>

            <!-- Router Manager Link -->
            <a href="{{ route('routers.index') }}" 
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition duration-150 {{ request()->routeIs('routers.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                </svg>
                Routers Manager
            </a>

        </nav>
    </aside>

    <!-- Mobile Navigation Drawer Panel (Hidden on Desktop) -->
    <div x-show="mobileMenuOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-cloak>
        
        <!-- Backdrop Backdrop filter overlay -->
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" x-show="mobileMenuOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"></div>

        <div class="fixed inset-0 flex">
            <!-- Sidebar drawer wrapper -->
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 transform transition ease-in-out duration-300"
                x-show="mobileMenuOpen"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full">
                
                <!-- Close Button -->
                <div class="absolute top-0 right-0 -mr-12 pt-4">
                    <button type="button" 
                        @click="mobileMenuOpen = false"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Drawer Branding Header -->
                <div class="h-20 flex items-center px-6 border-b border-gray-100 dark:border-gray-800 gap-3">
                    <span class="relative inline-flex rounded-full h-5 w-5 bg-indigo-600 flex items-center justify-center">
                        <svg class="h-2.5 w-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <span class="text-lg font-black tracking-wider text-gray-900 dark:text-white uppercase font-mono">Interboard</span>
                </div>

                <!-- Navigation menu inside mobile drawer -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto" @click="mobileMenuOpen = false">
                    <a href="{{ route('dashboard') }}" 
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20' : 'text-gray-600 hover:bg-gray-50' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('customers.index') }}" 
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('customers.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20' : 'text-gray-600 hover:bg-gray-50' }}">
                        Customers
                    </a>
                    <a href="{{ route('routers.index') }}" 
                        class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('routers.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20' : 'text-gray-600 hover:bg-gray-50' }}">
                        Routers Manager
                    </a>
                </nav>
            </div>
            
            <div class="flex-shrink-0 w-14" aria-hidden="true"></div>
        </div>
    </div>

    <!-- Main Outer Container -->
    <div class="lg:pl-72 flex flex-col min-h-screen">
        
        <!-- Top Navigation Bar -->
        <header class="h-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between px-6 sm:px-8 z-10 sticky top-0">
            
            <!-- Mobile Menu Toggle Button (Show on mobile) -->
            <button type="button" 
                @click="mobileMenuOpen = true"
                class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="hidden sm:block text-xs font-semibold uppercase tracking-wider text-gray-400">
                Operator Portal
            </div>

            <!-- User Info & Logout actions -->
            <div class="flex items-center gap-4 sm:gap-6 ml-auto">
                <div class="text-right">
                    <span class="block text-sm font-bold text-gray-900 dark:text-white">
                        {{ auth()->check() ? auth()->user()->name : 'ISP Operator' }}
                    </span>
                    <span class="block text-[10px] font-bold text-emerald-500 dark:text-emerald-400 uppercase tracking-widest font-mono">
                        ● Authorized
                    </span>
                </div>
                
                <div class="h-8 w-px bg-gray-100 dark:bg-gray-800"></div>

                <!-- Integrated Livewire Logout Action -->
                <livewire:auth.logout />
            </div>

        </header>

        <!-- Main Content Area -->
        <main class="flex-1 py-8">
            {{ $slot }}
        </main>
        
    </div>

    @livewireScripts
</body>
</html>
