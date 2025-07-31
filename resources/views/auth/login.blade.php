<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ show: false }">
            <x-input-label for="password" :value="__('كلمة المرور')" />

            <div class="relative">
                <input
                    :type="show ? 'text' : 'password'"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />

                <div class="absolute inset-y-0 end-0 flex items-center px-3 text-xl text-gray-500 cursor-pointer transition-colors duration-200 hover:text-indigo-600"
                    @mouseenter="show = true"
                    @mouseleave="show = false">
                    <span x-text="show ? '🙈' : '👁️'"></span>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>


        <!-- Unbelievable Security Reminder -->
        <div
            x-data="{ show: true, pulse: true }"
            x-init="setInterval(() => pulse = !pulse, 1000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 scale-90 translate-y-3"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative mt-6 bg-yellow-100 border border-yellow-400 text-yellow-800 text-sm rounded-xl px-6 py-4 shadow-lg animate-fade-in-down overflow-hidden"
        >
            <div class="flex items-center space-x-3">
                <div class="text-2xl animate-bounce-slow">🧠🔒</div>
                <div>
                    <p class="font-semibold">نصيحة ذكية</p>
                    <p class="mt-1" :class="pulse ? 'text-yellow-800' : 'text-yellow-600'">
                        لا تشارك بيانات تسجيل الدخول الخاصة بك حتى مع أقرب الناس إليك!
                    </p>
                </div>
            </div>

            <button @click="show = false" class="absolute top-2 end-3 text-yellow-600 hover:text-yellow-800 text-lg leading-none">
                &times;
            </button>

            <div class="absolute -bottom-1 -end-2 text-3xl animate-wiggle p-2">🚫</div>
        </div>





        <div class="flex items-center justify-center mt-4">

            <x-primary-button class="ms-3">
                {{ __('تسجيل الدخول') }}
            </x-primary-button>
        </div>
    </form>

    @push('styles')
        <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }

        @keyframes wiggle {
            0%, 100% { transform: rotate(-15deg); }
            50% { transform: rotate(15deg); }
        }
        .animate-wiggle {
            animation: wiggle 1.2s infinite;
        }
        </style>
    @endpush
</x-guest-layout>
