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
<aside :class="sidebarOpen ? 'translate-x-0 w-86' : '-translate-x-full lg:translate-x-0 lg:w-24 w-86'"
       class="fixed inset-y-0 left-0 z-20 transform transition-all duration-300 ease-in-out lg:static flex flex-col bg-gradient-to-br from-green-primary via-green-primary to-green-secondary shadow-2xl overflow-hidden">

    <div :class="sidebarOpen ? 'w-86' : 'w-86 lg:w-24'" class="flex flex-col h-full shrink-0 transition-all duration-300">
        <!-- Logo Section -->
        <div class="flex items-center px-6 py-6 border-b border-white/10 shrink-0 h-[88px] box-border transition-all duration-300"
             :class="sidebarOpen ? 'justify-start' : 'justify-center lg:px-0'">
            <!-- Logo -->
            <div class="bg-white/50 backdrop-blur-sm rounded-xl p-1 transition-all duration-300"
                 :class="sidebarOpen ? 'mr-4' : 'mr-0'">
                <img
                    src="{{ asset('images/logo-kemenag.webp') }}"
                    class="w-12 h-12"
                    alt="Logo Kemenag"
                >
            </div>

            <!-- App Name & Description -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-out duration-300 delay-100"
                 x-transition:enter-start="opacity-0 translate-x-[-10px]"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="leading-tight whitespace-nowrap overflow-hidden">
                <h1 class="text-white text-md font-bold uppercase tracking-wide">
                    {{ env('APP_NAME', 'CUTI KEMENAG') }}
                </h1>
                <p class="text-white/70 text-xs">
                    Sistem Manajemen Surat
                </p>
            </div>
        </div>

        <!-- Date & Time Section (Compact) -->
        <div class="transition-all duration-300 overflow-hidden"
             :class="sidebarOpen ? 'px-4 py-4 border-b border-white/10 opacity-100 max-h-40' : 'h-0 opacity-0 border-none'">
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
        <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="relative flex items-center px-3.5 py-3 rounded-xl transition-all duration-300 group
                      {{ request()->routeIs('dashboard') ? 'bg-white text-green-primary shadow-lg font-semibold' : 'text-white/90 hover:bg-white/10 hover:translate-x-1' }}"
               :class="sidebarOpen ? 'justify-start' : 'justify-center px-0'">
                <x-lucide-layout-dashboard class="w-5 h-5 flex-shrink-0 transition-colors duration-300 {{ request()->routeIs('dashboard') ? 'text-green-primary' : 'text-white/80 group-hover:text-white' }}" />
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:enter-start="opacity-0 -translate-x-2"
                      x-transition:enter-end="opacity-100 translate-x-0"
                      class="ml-3 text-sm tracking-wide truncate">
                    Dashboard
                </span>
            </a>

            <!-- Surat Tugas -->
            <a href="{{ route('surat-tugas.index') }}"
               class="relative flex items-center px-3.5 py-3 rounded-xl transition-all duration-300 group
                      {{ request()->routeIs('surat-tugas.*') ? 'bg-white text-green-primary shadow-lg font-semibold' : 'text-white/90 hover:bg-white/10 hover:translate-x-1' }}"
               :class="sidebarOpen ? 'justify-start' : 'justify-center px-0'">
                <x-lucide-briefcase class="w-5 h-5 flex-shrink-0 transition-colors duration-300 {{ request()->routeIs('surat-tugas.*') ? 'text-green-primary' : 'text-white/80 group-hover:text-white' }}" />
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:enter-start="opacity-0 -translate-x-2"
                      x-transition:enter-end="opacity-100 translate-x-0"
                      class="ml-3 text-sm tracking-wide truncate">
                    Surat Tugas
                </span>
            </a>

            <!-- Surat Cuti -->
            <a href="{{ route('surat-cuti.index') }}"
               class="relative flex items-center px-3.5 py-3 rounded-xl transition-all duration-300 group
                      {{ request()->routeIs('surat-cuti.*') ? 'bg-white text-green-primary shadow-lg font-semibold' : 'text-white/90 hover:bg-white/10 hover:translate-x-1' }}"
               :class="sidebarOpen ? 'justify-start' : 'justify-center px-0'">
                <x-lucide-file-text class="w-5 h-5 flex-shrink-0 transition-colors duration-300 {{ request()->routeIs('surat-cuti.*') ? 'text-green-primary' : 'text-white/80 group-hover:text-white' }}" />
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:enter-start="opacity-0 -translate-x-2"
                      x-transition:enter-end="opacity-100 translate-x-0"
                      class="ml-3 text-sm tracking-wide truncate">
                    Surat Cuti
                </span>
            </a>

            <!-- Riwayat Surat -->
            <a href="{{ route('riwayat-surat') }}"
               class="relative flex items-center px-3.5 py-3 rounded-xl transition-all duration-300 group
                      {{ request()->routeIs('riwayat-surat') ? 'bg-white text-green-primary shadow-lg font-semibold' : 'text-white/90 hover:bg-white/10 hover:translate-x-1' }}"
               :class="sidebarOpen ? 'justify-start' : 'justify-center px-0'">
                <x-lucide-history class="w-5 h-5 flex-shrink-0 transition-colors duration-300 {{ request()->routeIs('riwayat-surat') ? 'text-green-primary' : 'text-white/80 group-hover:text-white' }}" />
                <span x-show="sidebarOpen"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:enter-start="opacity-0 -translate-x-2"
                      x-transition:enter-end="opacity-100 translate-x-0"
                      class="ml-3 text-sm tracking-wide truncate">
                    Riwayat Surat
                </span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-white/10 shrink-0 h-[64px] box-border transition-all duration-300 overflow-hidden"
             :class="sidebarOpen ? 'opacity-100' : 'h-0 opacity-0 overflow-hidden py-0 border-none'">
            <div class="text-center space-y-1">
                <p class="text-xs text-white/60 font-medium whitespace-nowrap">
                    {{ env('APP_NAME', 'CUTI KEMENAG') }}
                </p>
                <p class="text-xs text-white/40 whitespace-nowrap">
                    {{ env('APP_VERSION', 'v1.0') }} © {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>

</aside>
