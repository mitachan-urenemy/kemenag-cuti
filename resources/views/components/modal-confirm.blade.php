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
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
    x-cloak
>
    {{-- Backdrop / Overlay --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="show = false"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
        aria-hidden="true"
    ></div>

    {{-- Modal Content --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4 sm:translate-y-0 sm:scale-95"
        @click.stop
        class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all"
        role="dialog"
        aria-modal="true"
    >
        <div class="p-6 sm:p-8 text-center">
            {{-- Icon Container --}}
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 ring-8 ring-red-50 mb-4">
                <x-lucide-alert-triangle class="h-8 w-8 text-red-600" stroke-width="2" />
            </div>

            {{-- Text Content --}}
            <h3 class="text-xl font-bold text-gray-900" x-text="title"></h3>

            <div class="mt-2">
                <p class="text-sm text-gray-500 leading-relaxed" x-html="message"></p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 gap-3">

            {{-- Button Confirm (Primary/Danger) --}}
            <form :action="action" method="POST" class="w-full sm:w-auto">
                @csrf
                <template x-if="['PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())">
                    <input type="hidden" name="_method" :value="method">
                </template>
                <button
                    type="submit"
                    class="inline-flex w-full justify-center items-center rounded-lg border border-transparent bg-red-600 px-4 py-2.5 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:text-sm transition-colors sm:mt-4"
                >
                    Ya, Hapus / Lanjutkan
                </button>
            </form>

            {{-- Button Cancel (Secondary) --}}
            <button
                type="button"
                @click="show = false"
                class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm transition-colors"
            >
                Batal
            </button>
        </div>
    </div>
</div>
