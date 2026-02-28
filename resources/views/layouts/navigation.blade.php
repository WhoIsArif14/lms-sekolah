<nav x-data="{ open: false }" class="bg-white">
    <div class="md:flex">
        <aside class="hidden md:flex md:flex-col md:w-64 md:h-screen md:sticky md:top-0 bg-white border-r">
            <div class="px-4 py-5 flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="h-8 w-auto text-gray-800" />
                </a>
                <div class="font-bold text-lg">{{ config('app.name', 'LMS') }}</div>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                <x-side-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-side-nav-link>

                <x-side-nav-link :href="route('jadwal.index')" :active="request()->routeIs('jadwal.*')">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Jadwal Online') }}
                    </div>
                </x-side-nav-link>

                <hr class="my-2 border-gray-100">

                @if (Auth::user()->role === 'admin')
                    <x-side-nav-link :href="route('admin.users.index', ['tab' => 'guru'])" :active="request()->routeIs('admin.users.*')">
                        {{ __('Kelola Pengguna') }}
                    </x-side-nav-link>

                    <x-side-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                        {{ __('Monitoring Kursus') }}
                    </x-side-nav-link>
                @endif

                @if (Auth::user()->role === 'guru')
                    <x-side-nav-link :href="route('guru.courses.index')" :active="request()->routeIs('guru.courses.*')">
                        {{ __('Mata Pelajaran Saya') }}
                    </x-side-nav-link>
                    <x-side-nav-link :href="route('guru.courses.index')" :active="request()->routeIs('guru.courses.*')">
                        {{ __('Tugas') }}
                    </x-side-nav-link>
                @endif

                @if (Auth::user()->role === 'siswa')
                    <x-side-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                        {{ __('Mata Pelajaran') }}
                    </x-side-nav-link>
                    <x-side-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                        {{ __('Tugas') }}
                    </x-side-nav-link>
                @endif
            </nav>

            <div class="border-t px-4 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs uppercase font-bold text-indigo-600">{{ Auth::user()->role }}</div>
                    </div>
                </div>

                <div class="mt-3">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button
                                class="w-full text-left px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Akun Saya') }}
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil Saya') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Keluar Sistem') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </aside>

        <div class="md:hidden flex items-center justify-between px-4 py-3 border-b w-full">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <x-application-logo class="h-8 w-auto text-gray-800" />
            </a>

            <button @click="open = !open"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:bg-gray-100">
                <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-show="open" class="md:hidden px-2 pt-2 pb-3 space-y-1 border-b w-full">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('jadwal.index')" :active="request()->routeIs('jadwal.index')">
                {{ __('Jadwal Online') }}
            </x-responsive-nav-link>

            @if (Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Kelola Pengguna') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                    {{ __('Monitoring Kursus') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->role === 'guru')
                <x-responsive-nav-link :href="route('guru.courses.index')" :active="request()->routeIs('guru.courses.*')">
                    {{ __('Mata Pelajaran Saya') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('guru.courses.index')" :active="request()->routeIs('guru.courses.*')">
                    {{ __('Tugas') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->role === 'siswa')
                <x-responsive-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                    {{ __('Kursus Saya') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')">
                    {{ __('Tugas') }}
                </x-responsive-nav-link>
            @endif

            <div class="pt-4 pb-1 border-t">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-bold text-xs text-indigo-600 uppercase">{{ Auth::user()->role }}</div>
                </div>

                <div class="mt-3 space-y-1 px-2">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Keluar') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>