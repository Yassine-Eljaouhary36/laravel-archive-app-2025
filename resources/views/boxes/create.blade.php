<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ __('إنشاء صندوق جديد مع الملفات') }}
        </h2>
    </x-slot>

            <!-- Page Content -->
    <main>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form id="boxForm" action="{{ route('boxes.store') }}" method="POST">
                            @csrf
                                        
                            <!-- Box Information Section -->
                            <div class="mb-6 p-4 border rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('معلومات الصندوق') }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Saving Base Number -->
                                    <div>
                                        <x-input-label for="saving_base_number" :value="__('رقم قاعدة الحفظ')" />
                                        <x-text-input id="saving_base_number" class="block mt-1 w-full" type="text" name="saving_base_number" :value="old('saving_base_number')" required />
                                        <x-input-error :messages="$errors->get('saving_base_number')" class="mt-2" />
                                    </div>
                                                
                                    <!-- File Type -->
                                    <div>
                                        <x-input-label for="file_type" :value="__('المصلحة')" />
                                        <select id="file_type" name="file_type" class="block mt-1 w-full rounded-lg" required>
                                            <option value="">{{ __('حدد المصلحة') }}</option>
                                            <option value="الرئاسة" {{ old('file_type') == 'الرئاسة' ? 'selected' : '' }}>الرئاسة</option>
                                            <option value="النيابة العامة" {{ old('file_type') == 'النيابة العامة' ? 'selected' : '' }}>النيابة العامة</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('file_type')" class="mt-2" />
                                    </div>

                                                
                                    <!-- Year of Judgment -->
                                    <div>
                                        <x-input-label for="year_of_judgment" :value="__('سنة الحكم')" />
                                        <x-text-input id="year_of_judgment" class="block mt-1 w-full" type="number" name="year_of_judgment" :value="old('year_of_judgment')" required />
                                        <x-input-error :messages="$errors->get('year_of_judgment')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                                        
                                    <!-- Files Section -->
                            <div class="mb-6 p-4 border rounded-lg">

                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ __('الملفات') }}</h3>
                                    <x-primary-button style="background-color: rgb(92, 92, 245)" type="button" id="addFileBtn" onclick="showFileForm()">
                                        {{ __('إضافة ملف جديد') }}
                                    </x-primary-button>
                                </div>


                                <!-- File Form (Initially hidden) -->
                                <div id="fileFormContainer" class="hidden mb-6 p-4 border rounded-lg bg-gray-50">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-md font-medium text-gray-900" id="fileFormTitle">{{ __('إضافة ملف جديد') }}</h4>
                                        <button type="button" onclick="hideFileForm()" class="text-gray-500 hover:text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                            
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- File Number -->
                                        <div>
                                            <x-input-label for="file_number" :value="__('رقم الملف')" />
                                            <x-text-input id="file_number" class="block mt-1 w-full" type="text" name="file_number" />
                                        </div>
                                                
                                        <!-- Symbol -->
                                        <div>
                                            <x-input-label for="symbol" :value="__('رمز الملف')" />
                                            <x-text-input id="symbol" class="block mt-1 w-full" type="text" name="symbol" />
                                        </div>
                                                
                                        <!-- Year of Opening -->
                                        <div>
                                            <x-input-label for="year_of_opening" :value="__('سنة فتح الملف')" />
                                            <x-text-input id="year_of_opening" class="block mt-1 w-full" type="number" name="year_of_opening" />
                                        </div>
                                    </div>
                                            
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                        <!-- Judgment Number -->
                                        <div>
                                            <x-input-label for="judgment_number" :value="__('رقم الحكم')" />
                                            <x-text-input id="judgment_number" class="block mt-1 w-full" type="text" name="judgment_number" />
                                        </div>
                                                
                                        <!-- Judgment Date -->
                                        <div>
                                            <x-input-label for="judgment_date" :value="__('تاريخ الحكم')" />
                                            <x-text-input id="judgment_date" class="block mt-1 w-full" type="date" name="judgment_date" />
                                        </div>
                                    </div>
                                            
                                    <div class="flex items-center justify-end mt-6">
                                        <x-secondary-button type="button" onclick="hideFileForm()" class="ml-3">
                                            {{ __('إلغاء') }}
                                        </x-secondary-button>
                                                
                                        <x-primary-button type="button" onclick="saveFile()" class="ml-3">
                                            {{ __('حفظ الملف') }}
                                        </x-primary-button>
                                    </div>
                                </div>

                                <!-- Files Table -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('رقم الملف') }}</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('رمز الملف') }}</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('سنة فتح الملف') }}</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('رقم الحكم') }}</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('تاريخ الحكم') }}</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="filesTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Files will be added here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                        
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('boxes.index') }}" class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('إلغاء') }} 
                                </a>
                                            
                                <x-primary-button class="mr-3" style="background-color: rgb(100, 191, 100)">
                                    {{ __('حفظ الصندوق مع الملفات') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    

    @push('scripts')

        <script>
            let files = [];
            let editingIndex = null;

            function showFileForm() {
                document.getElementById('fileFormContainer').classList.remove('hidden');
                document.getElementById('fileFormTitle').textContent = 'إضافة ملف جديد';
                editingIndex = null;
                resetFileForm();
            }

            function hideFileForm() {
                document.getElementById('fileFormContainer').classList.add('hidden');
            }

            function resetFileForm() {
                document.getElementById('file_number').value = '';
                document.getElementById('symbol').value = '';
                document.getElementById('year_of_opening').value = '';
                document.getElementById('judgment_number').value = '';
                document.getElementById('judgment_date').value = '';
            }

            function saveFile() {
                // Get all input values
                const fileData = {
                    file_number: document.getElementById('file_number').value.trim(),
                    symbol: document.getElementById('symbol').value.trim(),
                    year_of_opening: document.getElementById('year_of_opening').value.trim(),
                    judgment_number: document.getElementById('judgment_number').value.trim(),
                    judgment_date: document.getElementById('judgment_date').value.trim(),
                };

                // Validate required fields
                let isValid = true;
                const errorMessages = [];

                if (!fileData.file_number) {
                    errorMessages.push('رقم الملف مطلوب');
                    isValid = false;
                }

                if (!fileData.symbol) {
                    errorMessages.push('رمز الملف مطلوب');
                    isValid = false;
                }

                if (!fileData.year_of_opening) {
                    errorMessages.push('سنة فتح الملف مطلوبة');
                    isValid = false;
                }

                if (!fileData.judgment_date) {
                    errorMessages.push('تاريخ الحكم مطلوب');
                    isValid = false;
                }

                if (!isValid) {
                    // Show error messages
                    alert('الرجاء تصحيح الأخطاء التالية:\n\n' + errorMessages.join('\n'));
                    return;
                }

                if (editingIndex !== null) {
                    // Update existing file
                    files[editingIndex] = fileData;
                } else {
                    // Add new file
                    files.push(fileData);
                }

                updateFilesTable();
                hideFileForm();
                updateHiddenInputs();
            }

            function editFile(index) {
                const file = files[index];
                document.getElementById('file_number').value = file.file_number;
                document.getElementById('symbol').value = file.symbol;
                document.getElementById('year_of_opening').value = file.year_of_opening;
                document.getElementById('judgment_number').value = file.judgment_number;
                document.getElementById('judgment_date').value = file.judgment_date;
                
                document.getElementById('fileFormContainer').classList.remove('hidden');
                document.getElementById('fileFormTitle').textContent = 'تعديل الملف';
                editingIndex = index;
            }

            function removeFile(index) {
                if (confirm('هل أنت متأكد أنك تريد إزالة هذا الملف؟')) {
                    files.splice(index, 1);
                    updateFilesTable();
                    updateHiddenInputs();
                }
            }

            function updateFilesTable() {
                const tbody = document.getElementById('filesTableBody');
                tbody.innerHTML = '';

                if (files.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            {{ __('لم تتم إضافة أي ملفات بعد') }}
                        </td>
                    `;
                    tbody.appendChild(tr);
                    return;
                }

                files.forEach((file, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${file.file_number}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${file.symbol}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${file.year_of_opening}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${file.judgment_number}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${file.judgment_date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editFile(${index})" class="text-indigo-600 hover:text-indigo-900 ml-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button onclick="removeFile(${index})" class="text-red-600 hover:text-red-900">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            function updateHiddenInputs() {
                // Remove any existing hidden inputs
                document.querySelectorAll('[name^="files["]').forEach(el => el.remove());
                
                // Add new hidden inputs for each file
                files.forEach((file, index) => {
                    for (const [key, value] of Object.entries(file)) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `files[${index}][${key}]`;
                        input.value = value;
                        document.getElementById('boxForm').appendChild(input);
                    }
                });
            }

            // Initialize with empty table
            document.addEventListener('DOMContentLoaded', function() {
                updateFilesTable();
            });
        </script>

    @endpush
</x-app-layout>