<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم الشخصية') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <!-- Total Boxes -->
                        <div class="bg-white p-6 rounded-lg shadow-xl border-2 border-blue-200">
                            <h3 class="font-semibold text-lg text-gray-800">{{ __('إجمالي العلب') }}</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $total }}</p>
                        </div>
                        
                        <!-- Validated Boxes -->
                        <div class="bg-white p-6 rounded-lg shadow-xl border-2 border-green-200">
                            <h3 class="font-semibold text-lg text-gray-800">{{ __('معتمدة') }}</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $validated }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ __('معدل الاعتماد') }}: {{ $validation_rate }}%</p>
                        </div>
                        
                        <!-- Pending Boxes -->
                        <div class="bg-white p-6 rounded-lg shadow-xl border-2 border-yellow-200">
                            <h3 class="font-semibold text-lg text-gray-800">{{ __('معلقة') }}</h3>
                            <p class="text-3xl font-bold text-yellow-600">{{ $pending }}</p>
                        </div>
                        
                        <!-- Total Files -->
                        <div class="bg-white p-6 rounded-lg shadow-xl border-2 border-purple-200">
                            <h3 class="font-semibold text-lg text-gray-800">{{ __('إجمالي الملفات') }}</h3>
                            <p class="text-3xl font-bold text-purple-600">{{ $files_count }}</p>
                        </div>
                    </div>
                    
                    <!-- Tribunal Distribution -->
                    @if($tribunal_distribution->isNotEmpty())
                    <div class="bg-white p-6 rounded-lg shadow mb-6 border border-gray-300">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">{{ __('توزيع العلب حسب المحكمة') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($tribunal_distribution as $tribunal => $count)
                            <div class="bg-gray-50 p-4 rounded-lg shadow border-gray-300">
                                <h4 class="font-medium text-gray-700">{{ $tribunal ?? __('غير محدد') }}</h4>
                                <p class="text-2xl font-bold">{{ $count }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Recent Boxes -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4">{{ __('آخر العلب') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('رقم العلبة') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('المحكمة') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('تاريخ الإنشاء') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الحالة') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recent as $box)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="{{ route('boxes.show', $box) }}" class="text-blue-600 hover:underline">
                                                {{ $box->box_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $box->tribunal->name ?? __('غير محدد') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $box->created_at->translatedFormat('Y-m-d h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($box->isValidated())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ __('معتمدة') }}
                                                @if($box->validator)
                                                <br><small class="text-xs">{{ __('بواسطة') }}: {{ $box->validator->name }}</small>
                                                @endif
                                            </span>
                                            @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ __('معلقة') }}
                                            </span>
                                            @endif
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