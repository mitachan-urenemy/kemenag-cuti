<x-guest-layout>
    <div class="flex flex-col items-center justify-center mb-6">
        <a href="/" class="transition-transform hover:scale-105">
            <img src="{{ asset('images/logo-kemenag.webp') }}" alt="Kemenag Logo" class="w-24 h-24 drop-shadow-md">
        </a>
        <h2 class="mt-4 text-2xl font-bold text-gray-800">Selamat Datang</h2>
        <p class="mt-1 text-sm text-gray-600">Silakan login untuk melanjutkan</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Username -->
        <x-forms.text
            title="{{ __('Username') }}"
            name="username"
            type="text"
            :value="old('username')"
            required
            autofocus
            autocomplete="username"
        />

        <!-- Password -->
        <x-forms.password
            title="{{ __('Password') }}"
            name="password"
            required
            autocomplete="current-password"
        />

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <x-forms.checkbox
                title="{{ __('Ingat saya') }}"
                name="remember"
                :checked="old('remember')"
            />

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-700 hover:underline transition">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 text-base font-semibold">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center border-t border-gray-300 pt-4">
        <p class="text-xs text-gray-500">
            © {{ date('Y') }} Kementerian Agama. All rights reserved.
        </p>
    </div>
</x-guest-layout>
