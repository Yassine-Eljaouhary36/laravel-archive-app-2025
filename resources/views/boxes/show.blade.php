<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
       {{ __('تفاصيل الصندوق') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium mb-4">معلومات الصندوق</h3>
                @can('validate', $box)
                    <form action="{{ route('boxes.validate', $box) }}" method="POST">
                        @csrf
                        @if($box->isValidated())
                            <button type="submit" name="validated" value="0" class="btn btn-success" disabled>
                                Validated by {{ $box->validator->name }} on {{ $box->validated_at->format('Y-m-d') }}
                            </button>
                        @else
                            <button type="submit" name="validated" value="1" class="btn btn-warning">
                                Validate Box
                            </button>
                        @endif
                    </form>
                @endcan
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><strong>رقم قاعدة الحفظ:</strong> {{ $box->saving_base_number }}</p>
                        <p><strong>رقم الصندوق:</strong> {{ $box->box_number }}</p>
                    </div>
                    <div>
                        <p><strong>نوع الملف:</strong> {{ $box->file_type }}</p>
                        <p><strong>سنة الحكم:</strong> {{ $box->year_of_judgment }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium mb-4">الملفات في هذا الصندوق ({{ $box->files->count() }})</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الملف</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرمز</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سنة الفتح</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الحكم</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الحكم</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($box->files as $file)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $file->file_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $file->symbol }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $file->year_of_opening }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $file->judgment_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                {{ $file->judgment_date ? \Carbon\Carbon::parse($file->judgment_date)->format('Y-m-d') : '' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $files->links() }}
                </div>

            </div>
        </div>
        
        <div class="mt-6">
            <a href="{{ route('boxes.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                العودة إلى القائمة
            </a>
        </div>
    </div>
</div>
</x-app-layout>
