{{-- resources/views/components/modal-confirm.blade.php --}}
@props([
    'title' => 'Konfirmasi Tindakan',
    'action' => '',
    'method' => 'POST'
])
<div
    x-data="{
        show: false,
        title: '',
        message: '',
        action: '',
        method: 'POST'
    }"
    x-on:open-confirm-modal.window="
        show = true;
        title = $event.detail.title;
        message = $event.detail.message;
        action = $event.detail.action;
        method = $event.detail.method || 'POST';
    "
    x-show="show"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-[99] flex items-center justify-center p-4"
    x-cloak
>
    <!-- Overlay dengan blur -->
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="show = false"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
    ></div>

    <!-- Modal dengan animasi scale dan bounce -->
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        @click.stop
        class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
    >
        <!-- Decorative top border -->
        <div class="h-1.5 bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500"></div>

        <div class="p-6">
            <div class="flex items-start gap-4">
                <!-- Animated Icon -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        <!-- Pulse background -->
                        <div class="absolute inset-0 bg-yellow-400 rounded-full animate-ping opacity-25"></div>
                        <!-- Icon container -->
                        <div class="relative w-14 h-14 flex items-center justify-center bg-gradient-to-br from-yellow-100 to-orange-100 rounded-full shadow-lg">
                            <x-lucide-alert-triangle class="w-7 h-7 text-yellow-600" />
                        </div>
                    </div>
                </div>

                <div class="flex-1 pt-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2" x-text="title"></h3>
                    <p class="text-lg text-gray-600 leading-relaxed" x-html="message"></p>
                </div>

                <button
                    @click="show = false"
                    class="flex-shrink-0 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-200"
                >
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
        </div>

        <!-- Action buttons dengan gradient -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="show = false"
                    class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-xl transition-all duration-200 hover:shadow-md hover:scale-105 active:scale-95"
                >
                    Batal
                </button>
                <form :action="action" method="POST" class="inline-flex">
                    @csrf
                    <template x-if="['PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())">
                        <input type="hidden" name="_method" :value="method">
                    </template>
                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl transition-all duration-200 shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:scale-105 active:scale-95"
                    >
                        <span class="flex items-center gap-2">
                            <x-lucide-check class="w-4 h-4" />
                            Konfirmasi
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
