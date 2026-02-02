<!-- Sidebar Overlay (Mobile) -->
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
     style="display: none;">
</div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col bg-gradient-to-br from-green-primary via-green-primary to-green-primary shadow-2xl">

    <!-- Logo Section -->
    <div class="flex items-center justify-center px-6 py-8 border-b border-white/10">
        <div class="text-center">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 inline-block mb-2">
                <x-lucide-file-text class="w-10 h-10 text-white" />
            </div>
            <h1 class="text-white text-xl font-bold uppercase tracking-wide leading-tight">
                {{ env('APP_NAME', 'CUTI KEMENAG') }}
            </h1>
            <p class="text-white/70 text-xs mt-1">Sistem Manajemen Surat</p>
        </div>
    </div>

    <!-- Date & Time Section -->
    <div class="px-6 py-6 border-b border-white/10">
        <div x-data="{
            currentTime: '',
            currentDate: '',
            updateDateTime() {
                const now = new Date();

                // Format waktu: HH:MM:SS
                this.currentTime = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });

                // Format tanggal: Jumat, 25 Januari 2026
                const options = {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                };
                this.currentDate = now.toLocaleDateString('id-ID', options);
            }
        }"
        x-init="updateDateTime(); setInterval(() => updateDateTime(), 1000)"
        class="text-center">
            <!-- Clock Display -->
            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-3 mb-3">
                <div class="flex items-center justify-center space-x-2 mb-1">
                    <x-lucide-clock class="w-6 h-6 text-white/70" />
                    <span x-text="currentTime" class="text-2xl font-bold text-white font-mono tracking-wider"></span>
                </div>
                <div class="text-xs text-white/50 uppercase tracking-wide">WIB</div>
            </div>
            <!-- Date Display -->
            <div class="space-y-1">
                <p x-text="currentDate" class="text-sm font-medium text-white/90 leading-tight"></p>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="group flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('dashboard')
                     ? 'bg-white text-green-700 shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-layout-dashboard class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <span class="ml-auto">
                    <x-lucide-chevron-right class="w-4 h-4 text-green-primary" />
                </span>
            @endif
        </a>

        <!-- Surat Cuti -->
        <a href="{{ route('surat-cuti') }}"
           class="group flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('surat-cuti')
                     ? 'bg-white text-green-700 shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-file-text class="h-5 w-5 {{ request()->routeIs('surat-cuti') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Surat Cuti</span>
            @if(request()->routeIs('surat-cuti'))
                <span class="ml-auto">
                    <x-lucide-chevron-right class="w-4 h-4 text-green-primary" />
                </span>
            @endif
        </a>

        <!-- Surat Tugas -->
        <a href="{{ route('surat-tugas') }}"
           class="group flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('surat-tugas')
                     ? 'bg-white text-green-700 shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-briefcase class="h-5 w-5 {{ request()->routeIs('surat-tugas') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Surat Tugas</span>
            @if(request()->routeIs('surat-tugas'))
                <span class="ml-auto">
                    <x-lucide-chevron-right class="w-4 h-4 text-green-primary" />
                </span>
            @endif
        </a>

        <!-- Divider -->
        <div class="py-2">
            <div class="border-t border-white/10"></div>
        </div>

        <!-- Manajemen User -->
        <a href="{{ route('manajemen-user') }}"
           class="group flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('manajemen-user')
                     ? 'bg-white text-green-700 shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-users class="h-5 w-5 {{ request()->routeIs('manajemen-user') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Manajemen User</span>
            @if(request()->routeIs('manajemen-user'))
                <span class="ml-auto">
                    <x-lucide-chevron-right class="w-4 h-4 text-green-primary" />
                </span>
            @endif
        </a>

        <!-- Pegawai -->
        <a href="{{ route('pegawai') }}"
           class="group flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('pegawai')
                     ? 'bg-white text-green-700 shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-contact class="h-5 w-5 {{ request()->routeIs('pegawai') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Pegawai</span>
            @if(request()->routeIs('pegawai'))
                <span class="ml-auto">
                    <x-lucide-chevron-right class="w-4 h-4 text-green-primary" />
                </span>
            @endif
        </a>

        <!-- Riwayat Surat -->
        <a href="{{ route('pegawai') }}"
           class="group flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('pegawai')
                     ? 'bg-white text-green-700 shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-history class="h-5 w-5 {{ request()->routeIs('pegawai') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Riwayat Surat</span>
            @if(request()->routeIs('pegawai'))
                <span class="ml-auto">
                    <x-lucide-chevron-right class="w-4 h-4 text-green-primary" />
                </span>
            @endif
        </a>

    </nav>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-white/10">
        <div class="text-center">
            <p class="text-xs text-white/60">
                {{ env('APP_NAME', 'CUTI KEMENAG') }}
            </p>
            <p class="text-xs text-white/40 mt-1">
                {{ env('APP_VERSION', 'v1.0 ©') }} / {{ date('Y') }}
            </p>
        </div>
    </div>

</aside>
