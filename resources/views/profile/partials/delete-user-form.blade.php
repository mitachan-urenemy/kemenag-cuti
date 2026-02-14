<x-content-card
    icon="trash-2"
    title="{{ __('Hapus Akun') }}"
    subtitle="{{ __('Setelah akun Anda dihapus, seluruh data dan sumber daya yang terkait akan dihapus secara permanen. Sebelum menghapus akun, harap unduh terlebih dahulu data atau informasi apa pun yang ingin Anda simpan.') }}"
>
    <div x-data="{ show: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
        <x-danger-button @click.prevent="show = true">
            {{ __('Hapus Akun') }}
        </x-danger-button>

        <div
            x-show="show"
            x-on:keydown.escape.window="show = false"
            style="display: none;"
            class="fixed inset-0 z-[99] flex items-center justify-center p-4"
            x-cloak
        >
            <!-- Overlay -->
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

            <!-- Modal Panel -->
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
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <!-- Decorative top border -->
                    <div class="h-1.5 bg-gradient-to-r from-red-500 via-rose-500 to-pink-500"></div>

                    <div class="p-6 space-y-6">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-red-400 rounded-full animate-ping opacity-25"></div>
                                    <div class="relative w-14 h-14 flex items-center justify-center bg-gradient-to-br from-red-100 to-rose-100 rounded-full shadow-lg">
                                        <x-lucide-shield-alert class="w-7 h-7 text-red-600" />
                                    </div>
                                </div>
                            </div>
                            <!-- Title and Message -->
                            <div class="flex-1 pt-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('Anda Yakin?') }}</h3>
                                <p class="text-base text-gray-600 leading-relaxed">{{ __('Anda tidak dapat mengembalikan akun ini setelah dihapus.') }}</p>
                            </div>
                            <button type="button" @click="show = false" class="flex-shrink-0 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                                <x-lucide-x class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Password Input -->
                        <div class="mt-6">
                            <x-forms.password
                                :srOnlyTitle="true"
                                title="{{ __('Password') }}"
                                id="password_delete"
                                name="password"
                                placeholder="{{ __('Password') }}"
                                required
                            />
                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex justify-end gap-3">
                            <button
                                type="button"
                                @click="show = false"
                                class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-xl transition-all duration-200 hover:shadow-md active:scale-95"
                            >
                                Batal
                            </button>
                            <x-danger-button type="submit" class="ms-3">
                                {{ __('Hapus Akun') }}
                            </x-danger-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-content-card>
