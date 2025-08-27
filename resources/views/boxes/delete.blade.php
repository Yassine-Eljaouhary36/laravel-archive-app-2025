<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form id="delete-form" method="POST" action="{{ route('boxes.destroyMany') }}">
                    @csrf
                    @method('DELETE')

                    <h3 class="text-lg font-medium text-gray-900 mb-4">اختر الصناديق للحذف</h3>

                    <!-- Scrollable wrapper -->
                    <div class="border rounded  overflow-y-auto block" style="max-height: 400px">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="py-3 px-6 text-right">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th class="py-3 px-6 text-right">رقم الصندوق</th>
                                    <th class="py-3 px-6 text-right">عدد الملفات</th>
                                    <th class="py-3 px-6 text-right">{{__('المستخدم')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($boxes as $box)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-4 px-6 text-right">
                                        <input type="checkbox" name="boxes[]" value="{{ $box->id }}" 
                                               data-box-number="{{ $box->box_number }}"
                                               class="box-checkbox">
                                    </td>
                                    <td class="py-4 px-6 text-right">{{ $box->box_number }}</td>
                                    <td class="py-4 px-6 text-right">{{ $box->total_files }}</td>
                                    <td>{{ $box->user->name ?? 'System' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" id="open-modal"
                        class="mt-6 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded">
                        حذف الصناديق المحددة
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Modal -->
    <div id="password-modal" class="max-w-7xl mx-auto hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-lg font-medium text-gray-900 mb-4">تأكيد الحذف</h2>
            <p class="mb-2 text-gray-600">سيتم حذف الصناديق التالية:</p>
            <ul id="selected-boxes" style="max-height: 300px" class="list-disc list-inside text-red-600 mb-4 max-h-32 overflow-y-auto"></ul>

            <label class="block mb-2 text-sm">أدخل كلمة المرور لتأكيد الحذف</label>
            <input type="password" name="password" form="delete-form" required
                   class="border rounded w-full p-2.5 mb-4">

            <div class="flex justify-end space-x-2">
                <button type="button" id="cancel-modal"
                    class="px-4 py-2 bg-gray-300 rounded">إلغاء</button>
                <button type="submit" form="delete-form"
                    class="px-4 py-2 bg-red-600 text-white rounded">تأكيد الحذف</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Select all
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.box-checkbox').forEach(cb => cb.checked = this.checked);
        });

        const modal = document.getElementById('password-modal');
        const selectedList = document.getElementById('selected-boxes');

        document.getElementById('open-modal').addEventListener('click', () => {
            const checked = document.querySelectorAll('.box-checkbox:checked');
            if (checked.length === 0) {
                alert('اختر صندوقًا واحدًا على الأقل');
                return;
            }

            // Fill list with selected box numbers
            selectedList.innerHTML = '';
            checked.forEach(cb => {
                const li = document.createElement('li');
                li.textContent = "صندوق رقم " + cb.dataset.boxNumber;
                selectedList.appendChild(li);
            });

            modal.classList.remove('hidden');
        });

        document.getElementById('cancel-modal').addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    </script>
    @endpush
</x-app-layout>
