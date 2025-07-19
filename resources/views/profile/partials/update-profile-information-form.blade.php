<section class="bg-white p-6 rounded-xl shadow-md">
    <header class="mb-6 text-right">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __('معلومات الحساب') }}
        </h2>
        <p class="text-sm text-gray-500">
            {{ __('هذه معلومات حسابك الشخصية.') }}
        </p>
    </header>

    <div class="space-y-5 text-right">
        <div>
            <label class="block text-sm font-medium text-gray-600">
                {{ __('الاسم الكامل') }}
            </label>
            <div class="mt-1 text-base text-gray-900 border p-2 rounded-md bg-gray-50">
                {{ $user->name }}
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600">
                {{ __('البريد الإلكتروني') }}
            </label>
            <div class="mt-1 text-base text-gray-900 border p-2 rounded-md bg-gray-50">
                {{ $user->email }}
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-red-600">
                    {{ __('بريدك الإلكتروني غير مؤكد.') }}
                </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600">
                {{ __('الدور') }}
            </label>
            <div class="mt-1 text-base text-gray-900 border p-2 rounded-md bg-gray-50">
                {{ $user->getRoleNames()->first() ?? '—' }}
            </div>
        </div>
    </div>
</section>
