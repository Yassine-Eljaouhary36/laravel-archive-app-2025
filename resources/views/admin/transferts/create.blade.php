<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إنشاء تحويل جديد') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('admin.transferts.store') }}" id="transfertForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('المحكمة') }}
                            </label>
                            <select name="tribunal_id" id="tribunal_id" required
                                class="w-full rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">{{ __('اختر المحكمة') }}</option>
                                @foreach($tribunaux as $tribunal)
                                    <option value="{{ $tribunal->id }}">{{ $tribunal->tribunal }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('تاريخ التحويل') }}
                            </label>
                            <input type="date" name="transfert_date" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ __('الملاحظات') }}
                        </label>
                        <textarea name="notes" rows="3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('العلب المتاحة') }}</h3>
                        <div id="boxesContainer" class="mt-2 space-y-2 hidden">
                            <div class="flex items-center justify-between">
                                <button type="button" id="selectAll" class="text-blue-600 hover:text-blue-900 text-sm">
                                    {{ __('تحديد الكل') }}
                                </button>
                                <span id="boxesCount" class="text-sm text-gray-500"></span>
                            </div>
                            <div id="boxesList" class="max-h-60 overflow-y-auto border rounded-lg p-2"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('حفظ التحويل') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('tribunal_id').addEventListener('change', function() {
            const tribunalId = this.value;
            const container = document.getElementById('boxesContainer');
            const list = document.getElementById('boxesList');
            const count = document.getElementById('boxesCount');
            
            // Hide container and clear previous results
            container.classList.add('hidden');
            list.innerHTML = '';
            
            if (!tribunalId) return;

            // Show loading indicator
            list.innerHTML = `
                <div class="flex justify-center items-center p-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <span class="mr-2 text-sm text-gray-500">جاري تحميل العلب...</span>
                </div>
            `;
            container.classList.remove('hidden');

            fetch(`/admin/transferts/get-boxes?tribunal_id=${tribunalId}`)
                .then(response => response.json())
                .then(boxes => {
                    const container = document.getElementById('boxesContainer');
                    const list = document.getElementById('boxesList');
                    const count = document.getElementById('boxesCount');
                    
                    list.innerHTML = '';
                    
                    if (boxes.length === 0) {
                        list.innerHTML = '<p class="text-gray-500 text-sm">لا توجد علب متاحة لهذه المحكمة</p>';
                        container.classList.remove('hidden');
                        count.textContent = '0 علبة';
                        return;
                    }
                    
                    boxes.forEach(box => {
                        const div = document.createElement('div');
                        div.className = 'flex items-center p-2 hover:bg-gray-50 rounded';
                        div.innerHTML = `
                            <input type="checkbox" name="box_ids[]" value="${box.id}" 
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm space-x-2 rtl:space-x-reverse">
                                <span class="font-semibold text-gray-700">📦​ رقم العلبة :</span>
                                <span class="text-gray-900">${box.box_number}</span>
                                <span class="font-semibold text-gray-700"> | النوع :</span>
                                <span class="text-gray-900">${box.type}</span>
                                <span class="font-semibold text-gray-700"> | عدد الملفات :</span>
                                <span class="text-gray-900">${box.total_files}</span>
                                <span class="font-semibold text-gray-700"> | سنة الحكم :</span>
                                <span class="text-gray-900">${box.year_of_judgment}</span>
                            </span>

                        `;
                        list.appendChild(div);
                    });
                    
                    count.textContent = `${boxes.length} علبة`;
                    container.classList.remove('hidden');
                });
        });

        document.getElementById('selectAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="box_ids[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
        });

        document.getElementById('transfertForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('input[name="box_ids[]"]:checked');
            
            if (checkedBoxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تحذير',
                    text: 'يجب تحديد علبة واحدة على الأقل',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            Swal.fire({
                title: 'جاري إنشاء التحويل',
                text: 'يرجى الانتظار...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            this.submit();
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