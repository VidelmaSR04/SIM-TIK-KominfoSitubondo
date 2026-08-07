<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    {{-- TAMBAHKAN LINK REGISTER SERVER DI SINI --}}
    <div class="mt-8 space-y-3 border-t border-gray-200 pt-6">
        {{-- Info bahwa ini untuk daftar akun user --}}
        <div class="text-center">
            <p class="text-sm text-gray-600">
                <span class="material-symbols-outlined inline-block text-sm align-middle">person_add</span>
                Daftar akun untuk akses sistem
            </p>
        </div>

        {{-- Pemisah --}}
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-2 bg-white text-gray-500">atau</span>
            </div>
        </div>

        {{-- Link ke Register Server --}}
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Ingin mendaftarkan server baru?
                <a href="{{ route('register.server') }}" class="font-medium text-green-600 hover:text-green-500 inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">server</span>
                    Daftarkan Server
                </a>
            </p>
        </div>
    </div>
    {{-- ============================================= --}}
</x-guest-layout>
