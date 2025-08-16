<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('استيراد صناديق من ملفات Excel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.boxes.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Excel Files Upload -->
                        <div>
                            <label for="excels" class="block text-sm font-medium text-gray-700">
                                ملفات Excel (كل ملف يمثل صندوق)
                            </label>
                            <input type="file" name="excels[]" id="excels" multiple 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required>
                            <p class="mt-2 text-sm text-gray-500">
                                كل ملف Excel سيكون صندوقاً جديداً، والملفات من السطر 11 إلى الأخير تمثل الملفات داخل الصندوق
                            </p>
                        </div>
                        
                        <!-- Tribunal Selection -->
                        <div>
                            <label for="tribunal_id" class="block text-sm font-medium text-gray-700">
                                المحكمة
                            </label>
                            <select name="tribunal_id" id="tribunal_id" 
                                    class="cursor-not-allowed  mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                    required
                                    readonly
                                    tabindex="-1"
                                    aria-readonly="true">
                                <option value="314" selected>المحكمة الابتدائية  المدنية</option>
                            </select>
                        </div>
                        
                        <!-- Saving Base Selection -->
                        <div>
                            <label for="saving_base_id" class="block text-sm font-medium text-gray-700">
                                قاعدة الحفظ
                            </label>
                            <select name="saving_base_id" id="saving_base_id" 
                                    class="cursor-not-allowed mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                    required
                                        readonly
                                        tabindex="-1"
                                        aria-readonly="true">
                                <option value="530" selected>ملفات التنفيذ على شركات التأمين</option>
                            </select>
                        </div>
                        
                        <!-- File Type -->
                        <div>
                            <label for="file_type" class="block text-sm font-medium text-gray-700">
                                نوع الملف
                            </label>
                            <input type="text" name="file_type" id="file_type" 
                                   class="cursor-not-allowed mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required  value="الرئاسة"    
                                        readonly
                                        tabindex="-1"
                                        aria-readonly="true">
                        </div>
                        
                        <!-- Box Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">
                                نوع الصندوق
                            </label>
                            <input type="text" name="type" id="type" 
                                   class="cursor-not-allowed mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required value="ملفات التنفيذ على شركات التأمين" 
                                        readonly
                                        tabindex="-1"
                                        aria-readonly="true">
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('boxes.index') }}" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                رجوع
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                استيراد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>