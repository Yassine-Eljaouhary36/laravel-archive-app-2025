<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تفاصيل المستخدم') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}" style="background-color: yellowgreen" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('التعديل') }}
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('العودة إلى القائمة') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ __('معلومات شخصية') }}</h3>
                                <dl class="mt-2 divide-y divide-gray-200">
                                    <div class="py-3 flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('الاسم') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                                    </div>
                                    <div class="py-3 flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('البريد الإلكتروني') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Account Information') }}</h3>
                                <dl class="mt-2 divide-y divide-gray-200">
                                    <div class="py-3 flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('الدور') }}</dt>
                                        <dd class="text-sm text-gray-900 capitalize">{{ $user->getRoleNames()->first() }}</dd>
                                    </div>
                                    <div class="py-3 flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('الحالة') }}</dt>
                                        <dd class="text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? __('نشيط') : __('غير نشط') }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="py-3 flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">{{ __('تم إنشاؤه في') }}</dt>
                                        <dd class="text-sm text-gray-900">{{ $user->created_at->translatedFormat('d F Y  ,  H:i') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Status Toggle Form -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                            @csrf
                            @method('PATCH')
                            <x-primary-button type="submit" class="{{ $user->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">
                                {{ $user->is_active ? __('إلغاء تنشيط المستخدم') : __('تنشيط المستخدم') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>