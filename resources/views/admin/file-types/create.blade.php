<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إضافة نوع ملف جديد') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('admin.file-types.store') }}" id="createForm">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            {{ __('اسم النوع') }}
                        </label>
                        <input type="text" name="name" id="name" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="active" checked
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-gray-600">{{ __('مفعّل') }}</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="saving_bases_search">
                            {{ __('بحث عن قواعد الحفظ') }}
                        </label>
                        <input type="text" id="saving_bases_search" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="ابحث بقاعدة الحفظ...">
                    </div>

                    <div class="mb-4 max-h-60 overflow-y-auto border rounded p-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ __('قواعد الحفظ المرتبطة') }} <span class="text-red-500">*</span>
                        </label>
                        <div id="saving_bases_container">
                            @foreach($savingBases as $savingBase)
                                <div class="flex items-center mb-2 saving-base-item">
                                    <input type="checkbox" name="saving_bases[]" value="{{ $savingBase->id }}" 
                                        id="saving_base_{{ $savingBase->id }}"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <label for="saving_base_{{ $savingBase->id }}" class="mr-2 text-sm text-gray-600">
                                        {{ $savingBase->number }} - {{ $savingBase->description }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('saving_bases')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('حفظ') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('createForm').addEventListener('submit', function(e) {
            // Validate at least one saving base is selected
            const checkedBoxes = document.querySelectorAll('input[name="saving_bases[]"]:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يجب اختيار قاعدة الحفظ واحدة على الأقل',
                });
                return;
            }

            Swal.fire({
                title: 'جاري الحفظ',
                text: 'يرجى الانتظار...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        // Search functionality
        document.getElementById('saving_bases_search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.saving-base-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
    @endpush
</x-app-layout>