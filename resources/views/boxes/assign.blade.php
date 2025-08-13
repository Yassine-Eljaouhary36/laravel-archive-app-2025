<!-- resources/views/boxes/assign.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعيين صناديق لمستخدم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('boxes.assign-user') }}">
                        @csrf

                        <div class="mb-6">
                            <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900">
                                اختر المستخدم
                            </label>
                            <select name="user_id" id="user_id" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">-- اختر مستخدم --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">الصناديق المتاحة</h3>
                            
                            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">
                                                <input type="checkbox" id="select-all" class="w-4 h-4">
                                                <label for="select-all" class="sr-only">اختر الكل</label>
                                            </th>
                                            <th scope="col" class="py-3 px-6">رقم الصندوق</th>
                                            <th scope="col" class="py-3 px-6">{{__('عدد الملفات')}}</th>
                                            <th scope="col" class="py-3 px-6">{{__('المستخدم')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($boxes as $box)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="py-4 px-6">
                                                <input type="checkbox" name="boxes[]" value="{{ $box->id }}" 
                                                       class="box-checkbox w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500">
                                            </td>
                                            <td class="py-4 px-6">{{ $box->box_number }}</td>
                                            <td class="py-4 px-6">{{ $box->files_count }}</td>
                                            <td>{{ $box->user->name ?? 'System' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                            تعيين الصناديق المحددة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.box-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
    @endpush
</x-app-layout>