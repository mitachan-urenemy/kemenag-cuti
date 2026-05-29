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
        method: 'POST',
        type: 'danger',
        confirmText: '',
        formId: ''
    }"
    x-on:open-confirm-modal.window="
        show = true;
        title = $event.detail.title;
        message = $event.detail.message;
        action = $event.detail.action || '';
        method = $event.detail.method || 'POST';
        type = $event.detail.type || 'danger';
        confirmText = $event.detail.confirmText || '';
        formId = $event.detail.formId || '';
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
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full mb-4"
                 :class="{
                     'bg-red-100 ring-8 ring-red-50 text-red-600': type === 'danger' || type === 'delete',
                     'bg-green-100 ring-8 ring-green-50 text-green-600': type === 'success' || type === 'confirm',
                     'bg-yellow-100 ring-8 ring-yellow-50 text-yellow-600': type === 'warning',
                     'bg-blue-100 ring-8 ring-blue-50 text-blue-600': type === 'info'
                 }">
                <template x-if="type === 'danger' || type === 'delete'">
                    <x-lucide-alert-triangle class="h-8 w-8 text-red-600" stroke-width="2" />
                </template>
                <template x-if="type === 'success' || type === 'confirm'">
                    <x-lucide-check class="h-8 w-8 text-green-600" stroke-width="2" />
                </template>
                <template x-if="type === 'warning'">
                    <x-lucide-alert-circle class="h-8 w-8 text-yellow-600" stroke-width="2" />
                </template>
                <template x-if="type === 'info'">
                    <x-lucide-info class="h-8 w-8 text-blue-600" stroke-width="2" />
                </template>
            </div>

            {{-- Text Content --}}
            <h3 class="text-xl font-bold text-gray-900" x-text="title"></h3>

            <div class="mt-2">
                <p class="text-sm text-gray-500 leading-relaxed" x-html="message"></p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row justify-end items-center gap-3 sm:px-8">

            {{-- Button Cancel (Secondary) --}}
            <button
                type="button"
                @click="show = false"
                class="inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto sm:text-sm transition-colors"
            >
                Batal
            </button>

            {{-- Button Confirm (Primary/Danger) --}}
            <template x-if="action">
                <form :action="action" method="POST" class="w-full sm:w-auto m-0 p-0">
                    @csrf
                    <template x-if="['PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())">
                        <input type="hidden" name="_method" :value="method">
                    </template>
                    <button
                        type="submit"
                        class="inline-flex w-full justify-center items-center rounded-lg border border-transparent px-4 py-2.5 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:text-sm transition-colors"
                        :class="{
                            'bg-red-600 hover:bg-red-700 focus:ring-red-500': type === 'danger' || type === 'delete',
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500': type === 'success' || type === 'confirm',
                            'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500': type === 'warning',
                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500': type === 'info'
                        }"
                        x-text="confirmText || (['delete', 'danger'].includes(type) ? 'Ya, Hapus' : 'Ya, Lanjutkan')"
                    >
                    </button>
                </form>
            </template>
            <template x-if="!action && formId">
                <button
                    type="button"
                    @click="document.getElementById(formId).submit(); show = false;"
                    class="inline-flex w-full sm:w-auto justify-center items-center rounded-lg border border-transparent px-4 py-2.5 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:text-sm transition-colors"
                    :class="{
                        'bg-red-600 hover:bg-red-700 focus:ring-red-500': type === 'danger' || type === 'delete',
                        'bg-green-600 hover:bg-green-700 focus:ring-green-500': type === 'success' || type === 'confirm',
                        'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500': type === 'warning',
                        'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500': type === 'info'
                    }"
                    x-text="confirmText || 'Ya, Lanjutkan'"
                >
                </button>
            </template>
        </div>
    </div>
</div>
