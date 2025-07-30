<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل التحويل رقم') }} {{ $transfert->transfert_number }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('admin.transferts.update', $transfert) }}" id="editTransfertForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('المحكمة') }}:
                            </label>
                            <p class="text-gray-800">{{ $transfert->tribunal->tribunal }}</p>
                            <small class="text-gray-500">لا يمكن تغيير المحكمة بعد إنشاء التحويل</small>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                {{ __('تاريخ التحويل') }} *
                            </label>
                            <input type="date" name="transfert_date" value="{{ old('transfert_date', $transfert->transfert_date->format('Y-m-d')) }}" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ __('الملاحظات') }}
                        </label>
                        <textarea name="notes" rows="3"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('notes', $transfert->notes) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('العلب المتاحة') }}</h3>
                        <div class="flex items-center justify-between mb-2">
                            <button type="button" id="selectAllBoxes" class="text-blue-600 hover:text-blue-900 text-sm">
                                {{ __('تحديد الكل') }}
                            </button>
                            <span class="text-sm text-gray-500">
                                {{ __('تم تحديد') }} <span id="selectedCount">{{ count($currentBoxes) }}</span> {{ __('من') }} <span id="totalCount">{{ $availableBoxes->count() }}</span>
                            </span>
                        </div>
                        
                        <div class="max-h-96 overflow-y-auto border rounded-lg p-2 bg-gray-50">
                            @foreach($availableBoxes as $box)
                                <div class="flex items-center p-2 hover:bg-gray-100 rounded">
                                    <input type="checkbox" name="box_ids[]" value="{{ $box->id }}" 
                                        {{ in_array($box->id, $currentBoxes) ? 'checked' : '' }}
                                        class="box-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="mr-2 text-sm">
                                        <span class="mr-2 text-sm space-x-2 rtl:space-x-reverse">
                                            <span class="font-semibold text-gray-700">📦​ رقم العلبة :</span>
                                            <span class="text-gray-900">{{ $box->box_number }}</span>
                                            <span class="font-semibold text-gray-700"> | النوع :</span>
                                            <span class="text-gray-900">{{ $box->type }}</span>
                                            <span class="font-semibold text-gray-700"> | عدد الملفات :</span>
                                            <span class="text-gray-900">{{ $box->total_files }}</span>
                                            <span class="font-semibold text-gray-700"> | سنة الحكم :</span>
                                            <span class="text-gray-900">{{$box->year_of_judgment}}</span>
                                        </span>
                                        @if($box->transfert_id && $box->transfert_id != $transfert->id)
                                            <span class="text-red-500 text-xs">(محولة في تحويل آخر)</span>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.transferts.show', $transfert) }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-4">
                            {{ __('إلغاء') }}
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('حفظ التعديلات') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Select/Deselect all boxes
        document.getElementById('selectAllBoxes').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.box-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
            updateSelectedCount();
        });

        // Update selected count
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.box-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = selected;
        }

        // Listen to checkbox changes
        document.querySelectorAll('.box-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        // Form submission with loading
        document.getElementById('editTransfertForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('.box-checkbox:checked');
            
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
                title: 'جاري تحديث التحويل',
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