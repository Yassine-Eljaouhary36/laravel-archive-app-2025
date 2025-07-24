<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة تحكم المراقب') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="font-semibold text-lg text-gray-800">{{ __('العلب المعلقة') }}</h3>
                            <p class="text-3xl font-bold">{{ $pendingValidation }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="font-semibold text-lg text-green-600">{{ __('إجمالي المعتمد') }}</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $totalValidated }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="font-semibold text-lg text-blue-600">{{ __('معدل الاعتماد') }}</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ number_format($validationRate, 2) }}%</p>
                        </div>
                    </div>

                    <!-- Recently Validated Boxes -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">{{ __('آخر العلب المعتمدة') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('رقم العلبة') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('تاريخ الاعتماد') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الحالة') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentlyValidated as $box)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $box->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($box->validated_at)
                                                {{ \Carbon\Carbon::parse($box->validated_at)->format('Y-m-d H:i') }}
                                            @else
                                                {{ __('لم يتم الاعتماد بعد') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ __('معتمدة') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>