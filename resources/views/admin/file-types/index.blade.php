<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة أنواع الملفات') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Add New Button -->
        <div class="mb-4">
            <a href="{{ route('admin.file-types.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ __('إضافة نوع جديد') }}
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.file-types.index') }}" class="bg-white mb-2 p-6 rounded-xl shadow space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">اسم النوع</label>
                    <input type="text" name="name" placeholder="اسم النوع"
                        value="{{ request('name') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">حالة التفعيل</label>
                    <select name="active"
                        class="block mt-1 w-full rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">{{ __('اختر الحالة') }}</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>{{ __('مفعّل') }}</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>{{ __('غير مفعّل') }}</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow transition text-sm">
                        🔍 بحث
                    </button>
                </div>
            </div>
        </form>

        <!-- List and Toggle Form -->
        <form method="POST" action="{{ route('admin.file-types.toggleActive') }}" id="toggleForm">
            @csrf
            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="min-w-full divide-y divide-gray-200 text-right">
                    <thead class="bg-sky-200">
                        <tr>
                            <th class="px-6 py-3">
                                <input type="checkbox" id="select-all" class="cursor-pointer">
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('ID') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('اسم النوع') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الحالة') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الإجراءات') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($fileTypes as $fileType)
                            <tr>
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="ids[]" value="{{ $fileType->id }}" class="cursor-pointer">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $fileType->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $fileType->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($fileType->active)
                                        <span class="text-green-600 font-semibold">{{ __('مفعّل') }}</span>
                                    @else
                                        <span class="text-red-600 font-semibold">{{ __('غير مفعّل') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.file-types.edit', $fileType) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-2">{{ __('تعديل') }}</a>
                                    <button type="button" onclick="confirmDelete('{{ route('admin.file-types.destroy', $fileType) }}')"
                                       class="text-red-600 hover:text-red-900">
                                       {{ __('حذف') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <button type="submit" 
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    {{ __('تغيير حالة التفعيل للمحدد') }}
                </button>
                {{ $fileTypes->withQueryString()->links() }}
            </div>
        </form>
    </div>



    @push('scripts')
    <script>
        // ... (keep your existing select-all and confirmDelete functions) ...

       // Select All Checkbox
        document.getElementById('select-all').addEventListener('click', function(e){
            const checkboxes = document.querySelectorAll('input[name="ids[]"]');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        });

        // Delete Confirmation
        function confirmDelete(url) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن هذا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، احذفه!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    
                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Toggle Active Form Submission
        document.getElementById('toggleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('input[name="ids[]"]:checked');
            
            if (checkedBoxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تحذير',
                    text: 'يجب تحديد عنصر واحد على الأقل',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            Swal.fire({
                title: 'جاري التحديث',
                text: 'يرجى الانتظار...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit the form normally to allow Laravel to handle the redirect
            this.submit();
        });

        // Display success/error messages from session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif
    </script>
    @endpush
</x-app-layout>