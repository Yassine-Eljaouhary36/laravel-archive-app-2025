<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة العلب') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- نموذج البحث -->
                <form method="GET" action="{{ route('boxes.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Box Number -->
                        <div>
                            <x-input-label for="box_number" :value="__('رقم العلبة')" />
                            <x-text-input type="text" name="box_number" id="box_number" 
                                value="{{ request('box_number') }}"
                                class="mt-1 block w-full"/>
                        </div>
                        <a href="{{route('admin.boxes.import.form')}}">test</a>
                        <!-- Year of Judgment - Multi-select -->
                        <div>
                            <x-input-label for="year_of_judgment" :value="__('سنة الحكم')" />
                            <select name="year_of_judgment[]" id="year_of_judgment" multiple
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">جميع السنوات</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" 
                                        @if(request()->has('year_of_judgment') && in_array($year, (array)request('year_of_judgment')))
                                            selected
                                        @endif>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- File Type -->
                        <div>
                            <x-input-label for="file_type" :value="__('المصلحة')" />
                            <select name="file_type" id="file_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">كل الأنواع</option>
                                <option value="الرئاسة" {{ request('file_type') == 'الرئاسة' ? 'selected' : '' }}>الرئاسة</option>
                                <option value="النيابة العامة" {{ request('file_type') == 'النيابة العامة' ? 'selected' : '' }}>النيابة العامة</option>
                            </select>
                        </div>
                        
                        <!-- Type -->
                        <div>
                            <x-input-label for="type" :value="__('نوع الملفات')" />
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">كل الأنواع</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->name }}" {{ request('type') == $type->name ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Tribunal -->
                        <div>
                            <x-input-label for="tribunal_id" :value="__('المحكمة')" />
                            <select name="tribunal_id" id="tribunal_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">كل المحاكم</option>
                                @foreach($tribunals as $tribunal)
                                    <option value="{{ $tribunal->id }}" {{ request('tribunal_id') == $tribunal->id ? 'selected' : '' }}>
                                        {{ $tribunal->tribunal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="validated" :value="__('حالة التحقق')" />
                            <select name="validated" id="validated" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">الكل</option>
                                <option value="1" {{ request('validated') === '1' ? 'selected' : '' }}>تم التحقق</option>
                                <option value="0" {{ request('validated') === '0' ? 'selected' : '' }}>لم يتم التحقق</option>
                            </select>
                        </div>
                        
                        <!-- Search and Reset Buttons -->
                        <div class="flex items-end space-x-2">
                            <button type="submit" style="width: 80px;  justify-content: center; " class="px-5 py-2 bg-green-500 text-white rounded hover:bg-blue-600 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>
                            </button>

                            <!-- زر إعادة التعيين -->
                            <a href="{{ route('boxes.index') }}" style="margin-right: 20px;" class="px-5 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 4 23 10 17 10" />
                                    <path d="M20.49 15a9 9 0 11-2.13-9.36L23 10" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </form>

                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-medium">جميع العلب ({{ $boxes->total() }})</h3>
                    @if(auth()->user()->hasAnyRole(['admin', 'user']))
                        <a href="{{ route('boxes.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            إنشاء علبة جديدة
                            <x-heroicon-o-plus class="ml-2 mr-2 h-5 w-5 inline"/>
                        </a>
                    @endif
                    @if (auth()->user()->hasAnyRole(['admin']))
                        <a href="{{ route('boxes.exportBoxes', request()->query()) }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                            تصدير إلى Excel
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </a>
                    @endif
                </div>


                    <div class="overflow-x-auto">
                        <table class="w-full table-auto divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('رقم العلبة')}}</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('رقم قاعدة الحفظ')}}</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('المصلحة')}}</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('نوع الملفات')}}</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('المحكمة')}}</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('سنة الحكم')}}</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('عدد الملفات')}}</th>
                                    @if(auth()->user()->hasRole(['admin', 'controller']))
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('المستخدم')}}</th>
                                    @endif
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{__('تم التحقق ')}}</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($boxes as $box)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $box->box_number }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ $box->savingBase->number ?? $box->saving_base_number }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $box->file_type }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $box->type }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $box->tribunal->tribunal ?? '' }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $box->year_of_judgment }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $box->files_count }}</td>
                                    @if(auth()->user()->hasRole(['admin', 'controller']))
                                        <td>{{ $box->user->name ?? 'System' }}</td>
                                    @endif
                                    <td  class="text-center">
                                        @if($box->isValidated())
                                            <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-green-100">
                                                <svg class="h-2 w-2 text-green-600" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-red-100">
                                                <svg class="h-2 w-2 text-red-600" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap" style="display: flex">
                                        <a href="{{ route('boxes.show', $box->id) }}" class="text-blue-500 hover:text-blue-700 mr-2" title="عرض">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @can('update', $box)
                                        <!-- Edit Icon -->
                                        <a style="margin-right: 20px" href="{{ route('boxes.edit', $box->id) }}" class="text-yellow-500 hover:text-yellow-700" title="تعديل">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $boxes->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'نجاح',
                        text: '{{ session('success') }}',
                        confirmButtonText: 'حسناً'
                    });
                });
            </script>
        @endif
    @endpush

    @push('syles')
        <style>
            /* Style for multi-select elements */
            select[multiple] {
                height: 20px;
                background-image: none; /* Remove default arrow */
                padding: 0.5rem;
            }

            select[multiple] option {
                padding: 0.25rem 0.5rem;
                border-bottom: 1px solid #e5e7eb; /* subtle separator */
            }

            select[multiple] option:hover {
                background-color: #f3f4f6;
            }

            select[multiple] option:checked {
                background-color: #3b82f6; /* blue-500 */
                color: white;
            }
        </style>
    @endpush
</x-app-layout>