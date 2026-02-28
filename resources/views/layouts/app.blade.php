<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

        @include('layouts.navigation')

        <div class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden">

            <header class="bg-white shadow-sm py-4 px-6 flex justify-between items-center sm:justify-end">
                <button @click="sidebarOpen = true" class="sm:hidden text-gray-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-red-500 font-bold hover:underline">Logout</button>
                    </form>
                </div>
            </header>

            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
