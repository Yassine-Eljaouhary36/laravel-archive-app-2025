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
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                    <div>
                                        <x-input-label for="tribunal_id" :value="__('المحكمة')" />
                                        <select id="tribunal_id" name="tribunal_id" class="block mt-1 w-full rounded-lg" required>
                                            <option value="">{{ __('اختر المحكمة') }}</option>
                                            @foreach($tribunaux as $tribunal)
                                                <option value="{{ $tribunal->id }}" {{ old('tribunal_id') == $tribunal->id ? 'selected' : '' }}>
                                                    {{ $tribunal->tribunal }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('tribunal_id')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="saving_base_number" :value="__('رقم قاعدة الحفظ')" />
                                        <div class="relative mt-1">
                                            <input type="text" id="search_input" class="block w-full rounded-lg border-gray-300" placeholder="{{ __('ابحث هنا...') }}" autocomplete="off">
                                            <select id="saving_base_number" name="saving_base_number" class="hidden" required>
                                                <option value="">{{ __('اختر رقم قاعدة الحفظ') }}</option>
                                                @foreach($savingBases as $base)
                                                    <option value="{{ $base->number }}" {{ old('saving_base_number') == $base->number ? 'selected' : '' }}>
                                                        {{ $base->number }} @if($base->description) {{ $base->description }}@endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                            <div id="dropdown_options" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto"></div>
                                        </div>
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

                                    <div>
                                        <x-input-label for="type" :value="__(' نوع الملفات')" />
                                        <select id="type" name="type" class="block mt-1 w-full rounded-lg" required>
                                            <option value="">{{ __(' نوع الملفات') }}</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->name }}" {{ old('type') == $type->name ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
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
                                        {{ __('إضافة ملف جديد') }} <x-heroicon-o-plus class="ml-2 mr-2 h-5 w-5 inline"/>
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
                                            
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
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

                                        <!-- Remark -->
                                        <div>
                                            <x-input-label for="remark" :value="__('ملاحظات (25 حرف كحد أقصى)')" />
                                            <x-text-input id="remark" class="block mt-1 w-full" type="text" name="remark" maxlength="25" />
                                            <p id="remark-counter" class="text-xs text-gray-500 mt-1">0/25</p>
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
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ملاحظات') }}</th>
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

            @if ($errors->any())
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'حدثت أخطاء في التحقق',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonText: 'حسناً'
                    });
                });
            @endif


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
                document.getElementById('remark').value = ''; // Add this line
            }

            function saveFile() {
                // Get all input values
                const fileData = {
                    file_number: document.getElementById('file_number').value.trim(),
                    symbol: document.getElementById('symbol').value.trim(),
                    year_of_opening: document.getElementById('year_of_opening').value.trim(),
                    judgment_number: document.getElementById('judgment_number').value.trim(),
                    judgment_date: document.getElementById('judgment_date').value.trim(),
                    remark: document.getElementById('remark').value.trim(), // Add this line
                };

                // Get year of judgment value
                const savingBaseNumber = document.getElementById('saving_base_number').value.trim();
                const typeOfFile = document.getElementById('file_type').value.trim();
                const yearOfJudgment = document.getElementById('year_of_judgment').value.trim();
                const typeFile = document.getElementById('type').value.trim();
                const tribunalId = document.getElementById('tribunal_id').value.trim();

                // Add remark validation
                if (fileData.remark && fileData.remark.length > 25) {
                    errorMessages.push('ملاحظات يجب ألا تتجاوز 25 حرفاً');
                    isValid = false;
                }

                // Validate required fields
                let isValid = true;
                const errorMessages = [];

                if (!tribunalId) {
                    errorMessages.push('المحكمة');
                    isValid = false;
                }
                if (!savingBaseNumber) {
                    errorMessages.push('رقم قاعدة الحفظ');
                    isValid = false;
                }
                if (!typeOfFile) {
                    errorMessages.push('المصلحة');
                    isValid = false;
                }
                if (!yearOfJudgment) {
                    errorMessages.push('سنة الحكم');
                    isValid = false;
                }
                if (!typeFile) {
                    errorMessages.push('نوع الملفات');
                    isValid = false;
                }

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

                // Validate year of opening is before or equal to year of judgment
                if (fileData.year_of_opening && yearOfJudgment) {
                    if (parseInt(fileData.year_of_opening) > parseInt(yearOfJudgment)) {
                        errorMessages.push('سنة فتح الملف يجب أن تكون قبل أو تساوي سنة الحكم');
                        isValid = false;
                    }
                }

                if (!isValid) {
                    // Show error messages using SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        html: 'الرجاء تصحيح الأخطاء التالية:<br><br>' + errorMessages.join('<br>'),
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                if (editingIndex !== null) {
                    // Update existing file
                    files[editingIndex] = fileData;
                    
                    // Show success message for editing
                    Swal.fire({
                        icon: 'success',
                        title: 'تم التعديل بنجاح',
                        text: 'تم تحديث بيانات الملف بنجاح',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // Add new file
                    files.push(fileData);
                    
                    // Show success message for adding
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الإضافة بنجاح',
                        text: 'تم إضافة الملف الجديد بنجاح',
                        showConfirmButton: false,
                        timer: 1500
                    });
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
                document.getElementById('remark').value = file.remark || ''; // Add this line
                
                document.getElementById('fileFormContainer').classList.remove('hidden');
                document.getElementById('fileFormTitle').textContent = 'تعديل الملف';
                editingIndex = index;
            }

            function removeFile(index) {
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من التراجع عن هذا الإجراء!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، احذفه!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        files.splice(index, 1);
                        updateFilesTable();
                        updateHiddenInputs();
                        Swal.fire(
                            'تم الحذف!',
                            'تم إزالة الملف بنجاح.',
                            'success'
                        );
                    }
                });
            }

            function updateFilesTable() {
                const tbody = document.getElementById('filesTableBody');
                tbody.innerHTML = '';

                if (files.length === 0) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${file.remark || ''}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button type="button" onclick="editFile(${index})" class="text-indigo-600 hover:text-indigo-900 ml-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-900">
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



            document.addEventListener('DOMContentLoaded', function() {
                updateFilesTable();
                const searchInput = document.getElementById('search_input');
                const originalSelect = document.getElementById('saving_base_number');
                const dropdownOptions = document.getElementById('dropdown_options');
                
                // Create dropdown options from select element
                function populateDropdown(filter = '') {
                    dropdownOptions.innerHTML = '';
                    const options = Array.from(originalSelect.options);
                    const filteredOptions = options.filter(option => 
                        option.text.toLowerCase().includes(filter.toLowerCase())
                    );
                    
                    if (filteredOptions.length === 0) {
                        const noResults = document.createElement('div');
                        noResults.className = 'p-2 text-gray-500';
                        noResults.textContent = 'لا توجد نتائج';
                        dropdownOptions.appendChild(noResults);
                        return;
                    }
                    
                    filteredOptions.forEach(option => {
                        if (option.value === '') return; // Skip the placeholder option
                        
                        const optionElement = document.createElement('div');
                        optionElement.className = 'p-2 hover:bg-gray-100 cursor-pointer text-right';
                        optionElement.textContent = option.text;
                        optionElement.dataset.value = option.value;
                        
                        optionElement.addEventListener('click', () => {
                            originalSelect.value = option.value;
                            searchInput.value = option.text;
                            dropdownOptions.classList.add('hidden');
                        });
                        
                        dropdownOptions.appendChild(optionElement);
                    });
                }
                
                // Toggle dropdown on input focus
                searchInput.addEventListener('focus', () => {
                    populateDropdown();
                    dropdownOptions.classList.remove('hidden');
                });
                
                // Filter options on input
                searchInput.addEventListener('input', (e) => {
                    populateDropdown(e.target.value);
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.relative')) {
                        dropdownOptions.classList.add('hidden');
                    }
                });
                
                // Initialize with selected value if exists
                if (originalSelect.value) {
                    const selectedOption = originalSelect.options[originalSelect.selectedIndex];
                    if (selectedOption) {
                        searchInput.value = selectedOption.text;
                    }
                }


                searchInput.addEventListener('keydown', (e) => {
                    const options = dropdownOptions.querySelectorAll('div[data-value]');
                    const currentFocus = document.querySelector('.bg-gray-200');
                    let index = Array.from(options).indexOf(currentFocus);
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        index = (index + 1) % options.length;
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        index = (index - 1 + options.length) % options.length;
                    } else if (e.key === 'Enter' && currentFocus) {
                        e.preventDefault();
                        currentFocus.click();
                        return;
                    }
                    
                    options.forEach(opt => opt.classList.remove('bg-gray-200'));
                    if (options[index]) {
                        options[index].classList.add('bg-gray-200');
                        options[index].scrollIntoView({ block: 'nearest' });
                    }
                });

                // Add this to your DOMContentLoaded event listener
                document.getElementById('remark').addEventListener('input', function() {
                    const counter = document.getElementById('remark-counter');
                    const length = this.value.length;
                    counter.textContent = `${length}/25`;
                    if (length > 25) {
                        counter.classList.add('text-red-500');
                    } else {
                        counter.classList.remove('text-red-500');
                    }
                });
            });
        </script>

    @endpush
</x-app-layout>