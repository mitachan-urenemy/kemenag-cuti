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
       class="fixed inset-y-0 left-0 z-30 w-86 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col bg-gradient-to-br from-green-primary via-green-primary to-green-secondary shadow-2xl">

    <!-- Logo Section -->
    <div class="flex items-center px-6 py-6 border-b border-white/10">
        <!-- Logo -->
        <div class="bg-white/50 backdrop-blur-sm rounded-xl p-1 mr-4">
            <img
                src="{{ asset('images/logo-kemenag.webp') }}"
                class="w-12 h-12"
                alt="Logo Kemenag"
            >
        </div>

        <!-- App Name & Description -->
        <div class="leading-tight">
            <h1 class="text-white text-md font-bold uppercase tracking-wide">
                {{ env('APP_NAME', 'CUTI KEMENAG') }}
            </h1>
            <p class="text-white/70 text-xs">
                Sistem Manajemen Surat
            </p>
        </div>
    </div>

    <!-- Date & Time Section (Compact) -->
    <div class="px-4 py-4 border-b border-white/10">
        <div x-data="{
            currentTime: '',
            currentDate: '',
            updateDateTime() {
                const now = new Date();

                // Format waktu: HH:MM
                this.currentTime = now.toLocaleTimeString('id-ID', {
                    second: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                // Format tanggal: Jumat, 25 Januari 2026
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                const dayName = days[now.getDay()];
                const date = now.getDate();
                const month = months[now.getMonth()];
                const year = now.getFullYear();

                this.currentDate = `${dayName}, ${date} ${month} ${year}`;
            }
        }"
        x-init="updateDateTime(); setInterval(() => updateDateTime(), 1000)"
        class="bg-white/5 backdrop-blur-sm rounded-lg px-3 py-2.5">

            <!-- Time & Date in one line -->
            <div class="flex items-center justify-between mb-1.5">
                <div class="flex items-center space-x-1.5">
                    <x-lucide-clock class="w-4 h-4 text-white/60" />
                    <span x-text="currentTime" class="text-lg font-bold text-white font-mono"></span>
                </div>
                <span class="text-xs text-white/50 font-medium">WIB</span>
            </div>

            <!-- Date -->
            <div class="flex items-center space-x-1.5">
                <x-lucide-calendar class="w-3.5 h-3.5 text-white/60" />
                <p x-text="currentDate" class="text-xs text-white/80"></p>
            </div>

        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('dashboard')
                     ? 'bg-white text-green-primary shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-layout-dashboard class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <x-lucide-chevron-right class="w-4 h-4 ml-auto text-green-primary" />
            @endif
        </a>

        <!-- Surat Cuti -->
        <a href="{{ route('surat-cuti') }}"
           class="group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('surat-cuti')
                     ? 'bg-white text-green-primary shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-file-text class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('surat-cuti') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Surat Cuti</span>
            @if(request()->routeIs('surat-cuti'))
                <x-lucide-chevron-right class="w-4 h-4 ml-auto text-green-primary" />
            @endif
        </a>

        <!-- Surat Tugas -->
        <a href="{{ route('surat-tugas') }}"
           class="group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('surat-tugas')
                     ? 'bg-white text-green-primary shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-briefcase class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('surat-tugas') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Surat Tugas</span>
            @if(request()->routeIs('surat-tugas'))
                <x-lucide-chevron-right class="w-4 h-4 ml-auto text-green-primary" />
            @endif
        </a>

        <!-- Riwayat Surat -->
        <a href="{{ route('riwayat-surat') }}"
           class="group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('riwayat-surat')
                     ? 'bg-white text-green-primary shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-history class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('riwayat-surat') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Riwayat Surat</span>
            @if(request()->routeIs('riwayat-surat'))
                <x-lucide-chevron-right class="w-4 h-4 ml-auto text-green-primary" />
            @endif
        </a>

        <!-- Divider -->
        <div class="py-2">
            <div class="border-t border-white/10"></div>
        </div>

        <!-- Pegawai -->
        <a href="{{ route('pegawai.index') }}"
           class="group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('pegawai.*')
                     ? 'bg-white text-green-primary shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-contact class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('pegawai.*') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Pegawai</span>
            @if(request()->routeIs('pegawai.*'))
                <x-lucide-chevron-right class="w-4 h-4 ml-auto text-green-primary" />
            @endif
        </a>

        <!-- Manajemen User -->
        <a href="{{ route('users.index') }}"
           class="group flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('users.*')
                     ? 'bg-white text-green-primary shadow-lg'
                     : 'text-white hover:bg-white/10 hover:translate-x-1' }}">
            <x-lucide-users class="h-5 w-5 flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-green-primary' : 'text-white/80' }}" />
            <span class="ml-3">Manajemen User</span>
            @if(request()->routeIs('users.*'))
                <x-lucide-chevron-right class="w-4 h-4 ml-auto text-green-primary" />
            @endif
        </a>

    </nav>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-white/10">
        <div class="text-center space-y-1">
            <p class="text-xs text-white/60 font-medium">
                {{ env('APP_NAME', 'CUTI KEMENAG') }}
            </p>
            <p class="text-xs text-white/40">
                {{ env('APP_VERSION', 'v1.0') }} © {{ date('Y') }}
            </p>
        </div>
    </div>

</aside>
