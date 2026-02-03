<form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
    @csrf
    @method('put')

    <x-content-card
        icon="key"
        title="{{ __('Perbarui Kata Sandi') }}"
        subtitle="{{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}"
    >
        <div class="space-y-4">
            <x-forms.password
                title="{{ __('Password Saat Ini') }}"
                name="current_password"
                autocomplete="current-password"
            />

            <x-forms.password
                title="{{ __('Password Baru') }}"
                name="password"
                autocomplete="new-password"
            />

            <x-forms.password
                title="{{ __('Konfirmasi Password Baru') }}"
                name="password_confirmation"
                autocomplete="new-password"
            />
        </div>

        <x-slot name="footer">
            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Simpan') }}</x-primary-button>
            </div>
        </x-slot>
    </x-content-card>
</form>
