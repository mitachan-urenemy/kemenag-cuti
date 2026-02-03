{{-- resources/views/components/toast-notification.blade.php --}}
<div
    x-data="{
        notifications: [],
        add(detail) {
            const id = Date.now() + Math.random();
            // Default 5 detik, jika tidak diatur
            const duration = detail.duration || 5000;
            const autoClose = detail.autoClose !== undefined ? detail.autoClose : true;

            this.notifications.push({
                id: id,
                type: detail.type || 'info',
                title: detail.title || '',
                message: detail.message || '',
                autoClose: autoClose,
                duration: duration
            });

            if (autoClose) {
                setTimeout(() => this.remove(id), duration);
            }
        },
        remove(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications.splice(index, 1);
            }
        }
    }"
    x-on:open-toast.window="add($event.detail)"
    {{--
        Container:
        Mobile: Full width minus margin, posisi top-right (tapi full width jadi ketengah), gap kecil.
        Desktop: Width fixed, posisi top-right fixed.
    --}}
    class="fixed top-4 right-4 sm:top-6 sm:right-6 z-[100] w-[calc(100%-2rem)] sm:w-auto sm:max-w-sm space-y-3 sm:space-y-4 pointer-events-none"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-full scale-95"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95 translate-x-4"

            {{-- Card Styling: Putih dengan border kiri berwarna --}}
            class="relative pointer-events-auto w-full bg-white rounded-lg shadow-xl border-l-4 overflow-hidden ring-1 ring-black/5"
            :class="{
                'border-green-500': notification.type === 'success',
                'border-red-500': notification.type === 'danger' || notification.type === 'error',
                'border-yellow-500': notification.type === 'warning',
                'border-blue-500': notification.type === 'info',
            }"
        >
            <div class="p-3 sm:p-4">
                <div class="flex items-start gap-3">
                    <!-- Icon -->
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="rounded-full p-1"
                            :class="{
                                'bg-green-100 text-green-600': notification.type === 'success',
                                'bg-red-100 text-red-600': notification.type === 'danger' || notification.type === 'error',
                                'bg-yellow-100 text-yellow-600': notification.type === 'warning',
                                'bg-blue-100 text-blue-600': notification.type === 'info',
                            }"
                        >
                            <template x-if="notification.type === 'success'">
                                <x-lucide-check class="w-4 h-4 sm:w-5 sm:h-5" />
                            </template>
                            <template x-if="notification.type === 'danger' || notification.type === 'error'">
                                <x-lucide-alert-circle class="w-4 h-4 sm:w-5 sm:h-5" />
                            </template>
                            <template x-if="notification.type === 'warning'">
                                <x-lucide-alert-triangle class="w-4 h-4 sm:w-5 sm:h-5" />
                            </template>
                            <template x-if="notification.type === 'info'">
                                <x-lucide-info class="w-4 h-4 sm:w-5 sm:h-5" />
                            </template>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm sm:text-base font-semibold text-gray-900 leading-tight" x-text="notification.title"></p>
                        <p class="mt-1 text-xs sm:text-sm text-gray-500 leading-snug" x-text="notification.message"></p>
                    </div>

                    <!-- Close Button -->
                    <div class="flex-shrink-0">
                        <button @click.stop="remove(notification.id)" class="text-gray-400 hover:text-gray-600 p-1 rounded-md hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-200">
                            <x-lucide-x class="w-4 h-4 sm:w-5 sm:h-5" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <template x-if="notification.autoClose === true">
                <div class="absolute bottom-0 left-0 h-1 w-full bg-gray-100">
                    <div
                        class="h-full"
                        :class="{
                            'bg-green-500': notification.type === 'success',
                            'bg-red-500': notification.type === 'danger' || notification.type === 'error',
                            'bg-yellow-500': notification.type === 'warning',
                            'bg-blue-500': notification.type === 'info',
                        }"
                        :style="`animation: toast-progress ${notification.duration}ms linear forwards;`"
                    ></div>
                </div>
            </template>
        </div>
    </template>
</div>

<style>
    @keyframes toast-progress {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>
