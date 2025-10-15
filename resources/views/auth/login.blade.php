<x-guest-layout>
    <style>
        /* Estilos para el formulario de login */
        .sm\:rounded-lg {
            border-radius: 0.75rem;
            border: none !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3) !important;
        }
        
        /* Ajustar espacio del logo */
        .min-h-screen {
            padding-top: 2rem !important;
        }
        
        /* Mejorar apariencia de inputs */
        input {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-color: rgba(71, 85, 105, 0.5) !important;
            color: white !important;
        }
        
        /* Cambiar color de texto de etiquetas */
        label {
            color: rgb(209, 213, 219) !important;
        }
    </style>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h1 class="mt-2 text-2xl font-bold text-white keep-visible">{{ config('app.name') }}</h1>
        <p class="text-sm text-gray-400">Sistema de Gestión de Dispositivos</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
