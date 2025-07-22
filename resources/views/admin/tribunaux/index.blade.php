<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة المحاكم') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.tribunaux.index') }}" class="bg-white mb-2 p-6 rounded-xl shadow space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">اسم المحكمة</label>
                    <input type="text" name="tribunal" placeholder="اسم المحكمة"
                        value="{{ request('tribunal') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">الدائرة القضائية</label>
                    <input type="text" name="circonscription_judiciaire" placeholder="الدائرة القضائية"
                        value="{{ request('circonscription_judiciaire') }}"
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
        <form method="POST" action="{{ route('admin.tribunaux.toggleActive') }}">
            @csrf
            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="min-w-full divide-y divide-gray-200 text-right">
                    <thead class="bg-sky-200">
                        <tr>
                            <th class="px-6 py-3">
                                <input type="checkbox" id="select-all" class="cursor-pointer">
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('المحكمة') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الدائرة القضائية') }}
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('الحالة') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($tribunaux as $tribunal)
                            <tr>
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="ids[]" value="{{ $tribunal->id }}" class="cursor-pointer">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tribunal->tribunal }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tribunal->circonscription_judiciaire }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($tribunal->active)
                                        <span class="text-green-600 font-semibold">{{ __('مفعّل') }}</span>
                                    @else
                                        <span class="text-red-600 font-semibold">{{ __('غير مفعّل') }}</span>
                                    @endif
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
                {{ $tribunaux->withQueryString()->links() }}
            </div>
        </form>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('click', function(e){
            const checkboxes = document.querySelectorAll('input[name="ids[]"]');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        });
    </script>
</x-app-layout>
