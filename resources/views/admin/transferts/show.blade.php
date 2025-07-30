<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تفاصيل التحويل') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('معلومات التحويل') }}</h3>
                        <div class="mt-2 space-y-2">
                            <p><span class="font-semibold">رقم التحويل:</span> {{ $transfert->transfert_number }}</p>
                            <p><span class="font-semibold">المحكمة:</span> {{ $transfert->tribunal->tribunal }}</p>
                            <p><span class="font-semibold">تاريخ التحويل:</span> {{ $transfert->transfert_date->format('Y-m-d') }}</p>
                            <p><span class="font-semibold">عدد العلب:</span> {{ $transfert->boxes->count() }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('الملاحظات') }}</h3>
                        <p class="mt-2 text-gray-600">{{ $transfert->notes ?? 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>

                <h3 class="text-lg font-medium text-gray-900">{{ __('العلب المحولة') }}</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-right">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('رقم العلبة') }}</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('نوع الملفات')}}</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('سنة الحكم')}}</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('عدد الملفات')}}</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('تاريخ الإنشاء') }}</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transfert->boxes as $box)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $box->box_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $box->file_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $box->year_of_judgment }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $box->total_files }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $box->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('boxes.show', $box->id) }}" class="text-blue-500 hover:text-blue-700 mr-2" title="عرض">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>