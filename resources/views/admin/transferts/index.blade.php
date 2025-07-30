<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة التحويلات') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('admin.transferts.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ __('إنشاء تحويل جديد') }}
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 text-right">
                <thead class="bg-sky-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('رقم التحويل') }}
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('المحكمة') }}
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('تاريخ التحويل') }}
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('عدد العلب') }}
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('الإجراءات') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($transferts as $transfert)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transfert->transfert_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transfert->tribunal->tribunal }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transfert->transfert_date->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transfert->boxes->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" style="display: flex">
                                <a href="{{ route('admin.transferts.show', $transfert) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.transferts.edit', $transfert) }}" 
                                class="text-amber-500 hover:text-amber-900 mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transferts->links() }}
        </div>
    </div>
</x-app-layout>