<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تفاصيل الصندوق') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Box Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium">معلومات الصندوق</h3>
                        
                        @can('validate', $box)
                            <form action="{{ route('boxes.validate', $box) }}" method="POST">
                                @csrf
                                @if(!$box->isValidated())
                                    <button type="submit" name="validated" value="1" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                        <x-heroicon-s-check-circle class="w-6 h-6" />
                                        تم التحقق من الصندوق
                                    </button>

                                @endif
                            </form>
                        @endcan
                        @if($box->isValidated() && auth()->user()->hasRole(['admin', 'controller']))
                            <a href="{{ route('boxes.export', $box) }}" 
                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 ml-4">
                                <i class="fas fa-file-excel mr-2"></i> تصدير إلى Excel
                            </a>
                        @endif
                    </div>

                    <!-- Validation Warning -->
                    @if($box->isValidated())
                        <div class="mb-4 p-3 bg-yellow-300 text-black-800 rounded-lg">
                            <i class="fas fa-lock mr-2"></i> هذا الصندوق تم التحقق منه ولا يمكن تعديله
                        </div>
                        <button type="button" class="cursor-not-allowed" disabled>
                            <span class="mr-5">تم التحقق بواسطة :</span>
                            <span class="px-4 py-2 bg-green-200 text-black-800 rounded-lg">{{ $box->validator->name ?? 'مستخدم غير معروف' }} </span> 
                            <span class="mr-5"> في :</span> 
                            <span class="px-4 py-2 bg-green-200 text-black-800 rounded-lg">{{ \Carbon\Carbon::parse($box->validated_at)->format('Y-m-d H:i') }}</span>
                        </button>
                    @endif
                    

                    <div class="overflow-x-auto bg-white p-6 rounded-lg shadow border mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Column -->
                            <div>
                                <table class="w-full text-right table-fixed border-collapse">
                                    <tbody>
                                        <tr class="border-b">
                                            <th class="py-2 px-4 font-semibold text-gray-900">المحكمة</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->tribunal->tribunal ?? 'غير محددة' }}</td>
                                        </tr>
                                        <tr class="border-b">
                                            <th class="py-2 px-4 font-semibold text-gray-900">رقم قاعدة الحفظ</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->saving_base_number }}</td>
                                        </tr>
                                        <tr class="border-b bg-gray-50">
                                            <th class="py-2 px-4 font-semibold text-gray-900">رقم الصندوق</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->box_number }}</td>
                                        </tr>
                                        <tr class="border-b">
                                            <th class="py-2 px-4 font-semibold text-gray-900">المالك</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->user->name ?? 'غير معروف' }}</td>
                                        </tr>
                                        <tr class="border-b bg-gray-50">
                                            <th class="py-2 px-4 font-semibold text-gray-900">تاريخ الإنشاء</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Second Column -->
                            <div>
                                <table class="w-full text-right table-fixed border-collapse">
                                    <tbody>
                                        <tr class="border-b">
                                            <th class="py-2 px-4 font-semibold text-gray-900">المصلحة</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->file_type }}</td>
                                        </tr>
                                        <tr class="border-b bg-gray-50">
                                            <th class="py-2 px-4 font-semibold text-gray-900">نوع الملفات</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->type }}</td>
                                        </tr>
                                        <tr class="border-b bg-gray-50">
                                            <th class="py-2 px-4 font-semibold text-gray-900">سنة الحكم</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->year_of_judgment }}</td>
                                        </tr>
                                        <tr class="border-b">
                                            <th class="py-2 px-4 font-semibold text-gray-900">عدد الملفات</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->total_files }}</td>
                                        </tr>
                                        <tr class="border-b bg-gray-50">
                                            <th class="py-2 px-4 font-semibold text-gray-900">تاريخ التعديل</th>
                                            <td class="py-2 px-4 text-gray-700">{{ $box->updated_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <!-- Action Buttons -->
                    <div class="mt-6 flex space-x-3 space-x-reverse">
                        <a href="{{ route('boxes.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            العودة إلى القائمة
                        </a>
                        
                        @can('update', $box)
                            <a href="{{ route('boxes.edit', $box) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 {{ $box->isValidated() ? 'opacity-50 cursor-not-allowed' : '' }}"
                               @if($box->isValidated()) onclick="return false;" @endif>
                                تعديل
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            
            <!-- Files Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">الملفات في هذا الصندوق ({{ $files->total() }})</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الملف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرمز</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سنة الفتح</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الحكم</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الحكم</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($files as $index => $file)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + $files->firstItem() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $file->file_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $file->symbol }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $file->year_of_opening }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $file->judgment_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $file->judgment_date ? \Carbon\Carbon::parse($file->judgment_date)->format('Y-m-d') : 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $files->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>