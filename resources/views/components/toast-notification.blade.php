{{-- resources/views/components/toast-notification.blade.php --}}
<div
    x-data="{
        notifications: [],
        add(detail) {
            const id = Date.now() + Math.random();
            const autoClose = detail.autoClose !== undefined ? detail.autoClose : true;

            this.notifications.push({
                id: id,
                type: detail.type || 'info',
                title: detail.title || '',
                message: detail.message || '',
                autoClose: autoClose
            });

            if (autoClose) { // Only set timeout if autoClose is true
                setTimeout(() => this.remove(id), 5000);
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
    class="fixed top-6 right-6 z-[100] w-full max-w-sm space-y-3"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="transform opacity-0 translate-x-full"
            x-transition:enter-end="transform opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform opacity-100"
            x-transition:leave-end="transform opacity-0 -translate-y-full"
            class="relative w-full rounded-xl shadow-lg overflow-hidden"
            :class="{
                'bg-green-500 text-white': notification.type === 'success',
                'bg-red-500 text-white': notification.type === 'danger',
                'bg-yellow-400 text-white': notification.type === 'warning',
                'bg-gray-800 text-white': notification.type === 'info',
            }"
        >
            <div class="p-4">
                <div class="flex items-start gap-3">
                    <!-- Icon -->
                    <div class="flex-shrink-0 pt-0.5">
                        <template x-if="notification.type === 'success'"><x-lucide-check-circle class="w-6 h-6" /></template>
                        <template x-if="notification.type === 'danger'"><x-lucide-shield-alert class="w-6 h-6" /></template>
                        <template x-if="notification.type === 'warning'"><x-lucide-alert-triangle class="w-6 h-6" /></template>
                        <template x-if="notification.type === 'info'"><x-lucide-info class="w-6 h-6" /></template>
                    </div>
                    <!-- Content -->
                    <div class="flex-1">
                        <p class="text-base font-semibold" x-text="notification.title"></p>
                        <p class="mt-1 text-sm opacity-90" x-text="notification.message"></p>
                    </div>
                    <!-- Close Button -->
                    <div class="flex-shrink-0">
                        <button @click.stop="remove(notification.id)" class="p-1 opacity-70 hover:opacity-100 rounded-full hover:bg-white/20 transition-colors">
                            <x-lucide-x class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Progress bar -->
            <template x-if="notification.autoClose === true">
                <div class="absolute bottom-0 left-0 h-1 w-full bg-black/20">
                    <div class="h-1 bg-white/70 animate-toast-progress"></div>
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
    .animate-toast-progress {
        animation: toast-progress 5s linear forwards;
    }
</style>
