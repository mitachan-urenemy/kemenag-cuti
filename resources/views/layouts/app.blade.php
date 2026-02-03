<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo-kemenag.webp') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-kemenag.webp') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div x-data="{
        sidebarOpen: false,
        getGreeting() {
            const hour = new Date().getHours();
            if (hour >= 5 && hour < 11) return 'Selamat Pagi';
            if (hour >= 11 && hour < 15) return 'Selamat Siang';
            if (hour >= 15 && hour < 18) return 'Selamat Sore';
            return 'Selamat Malam';
        },
        getGreetingIcon() {
            const hour = new Date().getHours();
            if (hour >= 5 && hour < 11) return '☀️';
            if (hour >= 11 && hour < 15) return '🌤️';
            if (hour >= 15 && hour < 18) return '🌅';
            return '🌙';
        }
    }" class="flex h-screen bg-gray-50">

        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Header -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">

                    <!-- Left Section: Menu Button + Greeting -->
                    <div class="flex items-center space-x-4">
                        <!-- Mobile Menu Button -->
                        <button @click.stop="sidebarOpen = !sidebarOpen"
                                class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 lg:hidden transition-colors">
                            <x-lucide-menu class="w-6 h-6" />
                        </button>

                        <!-- Greeting Section -->
                        <div class="flex flex-col">
                            <div class="flex items-center space-x-2">
                                <span x-text="getGreetingIcon()" class="text-xl"></span>
                                <h1 class="text-xl font-bold text-gray-800" x-text="getGreeting()"></h1>
                            </div>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Assalamualaikum,
                                <span class="font-medium text-gray-700">{{ ucfirst(Auth::user()->username) ?? 'Admin' }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right Section: User Profile -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                                class="flex items-center space-x-3 focus:outline-none group">
                            <!-- Avatar -->
                            <div class="relative">
                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-200 group-hover:ring-green-primary transition-all"
                                     src="{{ Auth::user()->image_path ? asset('storage/' . Auth::user()->image_path) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email ?? 'example@example.com'))) . '?d=mp' }}"
                                     alt="Avatar">
                                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
                            </div>

                            <!-- User Info (Hidden on mobile) -->
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-gray-700 group-hover:text-green-primary transition-colors">
                                    {{ Auth::user()->username ?? 'Admin' }}
                                </p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
                            </div>

                            <!-- Dropdown Arrow -->
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-600 transition-transform"
                                 :class="{ 'rotate-180': dropdownOpen }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen"
                             @click.away="dropdownOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50"
                             style="display: none;">

                            <!-- User Info in Dropdown -->
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->username ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? '-' }}</p>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-green-primary transition-colors {{ request()->routeIs('profile.edit') ? 'bg-indigo-50 text-green-primary' : '' }}">
                                    <x-lucide-user class="mr-3 h-5 w-5" />
                                    Profil Saya
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <x-lucide-log-out class="mr-3 h-5 w-5" />
                                        Keluar
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                {{ $slot }}
            </main>

        </div>
    </div>
    <x-toast-notification />
    <x-modal-confirm />

    @if (session('notification'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.dispatchEvent(new CustomEvent('open-toast', {
                    detail: {
                        type: '{{ session('notification.type') }}',
                        title: '{{ session('notification.title') }}',
                        message: '{{ session('notification.message') }}',
                        autoClose: {{ data_get(session('notification'), 'autoClose', true) ? 'true' : 'false' }},
                        duration: {{ data_get(session('notification'), 'duration', 5000) }}
                    }
                }));
            });
        </script>
    @endif
    @stack('scripts')
</body>
</html>
