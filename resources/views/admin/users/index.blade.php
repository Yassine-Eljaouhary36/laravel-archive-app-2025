<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <x-heroicon-s-user class="ml-2 h-7 w-7 inline"/>
                {{ __('إدارة المستخدمين') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-heroicon-c-plus-circle class="ml-2 h-5 w-5 inline" />
                {{ __('إنشاء مستخدم جديد') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Role Filter -->
                            <div>
                                <x-input-label for="role" :value="__('الدور')" />
                                <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">{{ __('كل الأدوار') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                            {{ __($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Date Range -->
                            <div>
                                <x-input-label for="date_from" :value="__('من تاريخ')" />
                                <x-text-input type="date" name="date_from" id="date_from" 
                                            value="{{ request('date_from') }}" class="mt-1 block w-full"/>
                            </div>
                            
                            <div>
                                <x-input-label for="date_to" :value="__('إلى تاريخ')" />
                                <x-text-input type="date" name="date_to" id="date_to" 
                                            value="{{ request('date_to') }}" class="mt-1 block w-full"/>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="px-5 py-2 bg-green-500 text-white rounded hover:bg-blue-600 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    {{ __('تصفية') }}
                                </button>
                                
                                <a href="{{ route('admin.users.index') }}" style="margin-right: 20px;" class="px-5 py-2 rounded border-4 border-dashed border-gray-300 hover:border-orange-500 hover:text-orange-500 flex items-center gap-2">
                                    <x-heroicon-o-arrow-path class="h-5 w-5" />
                                </a>

                            </div>
                        </div>
                    </form>

                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <!-- Update the table headers -->
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الاسم') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('البريد الإلكتروني') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الدور') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الحالة') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('عدد العلب المعالجة') }}
                                        @if(request('date_from') && request('date_to'))
                                            <br><span class="text-xs text-gray-400">({{ request('date_from') }} إلى {{ request('date_to') }})</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('عدد الملفات المعالجة') }}
                                        @if(request('date_from') && request('date_to'))
                                            <br><span class="text-xs text-gray-400">({{ request('date_from') }} إلى {{ request('date_to') }})</span>
                                        @endif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>

                            <!-- Update the table body -->
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ __($user->getRoleNames()->first()) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? __('نشط') : __('غير نشط') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $user->boxes_validated_count ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $user->valid_files_count ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                            style="margin: 0 10px"
                                            class="text-blue-600 hover:text-blue-900">
                                                <x-heroicon-o-eye class="h-5 w-5 inline" />
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                            style="margin: 0 10px"
                                            class="text-indigo-600 hover:text-indigo-900">
                                                <x-heroicon-o-pencil class="h-5 w-5 inline" />
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            {{ __('لا يوجد مستخدمون') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($users->hasPages())
                        <div class="mt-4 px-6 py-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>

                <!-- Add before the users table -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 my-8 p-4">
                    <!-- Metric Cards -->
                    <div class="bg-white p-6 rounded-lg shadow border-2 border-dashed border-gray-300">
                        <div class="flex items-center">
                            <x-heroicon-o-users class="h-8 w-8 text-blue-500"/>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ __('إجمالي المستخدمين') }}</p>
                                <p class="text-2xl font-semibold">{{ $metrics['total_users'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow border-2 border-dashed border-gray-300">
                        <div class="flex items-center">
                            <x-heroicon-o-user-circle class="h-8 w-8 text-green-500"/>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ __('المستخدمين النشطين') }}</p>
                                <p class="text-2xl font-semibold">{{ $metrics['active_users'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow border-2 border-dashed border-gray-300">
                        <div class="flex items-center">
                            <x-heroicon-o-archive-box class="h-8 w-8 text-purple-500"/>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ __('العلب المعالجة') }}</p>
                                <p class="text-2xl font-semibold">{{ $metrics['total_validated_boxes'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow border-2 border-dashed border-gray-300">
                        <div class="flex items-center">
                            <x-heroicon-o-document-text class="h-8 w-8 text-orange-500"/>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">{{ __('الملفات المعالجة') }}</p>
                                <p class="text-2xl font-semibold">{{ $metrics['total_validated_files'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="bg-white p-6 rounded-lg shadow mb-8">
                    <h3 class="text-lg font-medium mb-4">{{ __('أداء المستخدمين') }}</h3>
                    <canvas id="performanceChart" height="100"></canvas>
                </div>

            </div>
        </div>
    </div>

    <!-- Existing users table goes here... -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prepare data for chart
            const users = @json($users->items());
            const labels = users.map(user => user.name);
            const filesData = users.map(user => user.valid_files_count);
            const boxesData = users.map(user => user.boxes_validated_count);

            // Create chart
            const ctx = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'الملفات المعالجة',
                            data: filesData,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'العلب المعالجة',
                            data: boxesData,
                            backgroundColor: 'rgba(139, 92, 246, 0.7)',
                            borderColor: 'rgba(139, 92, 246, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: false,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            rtl: true
                        },
                        tooltip: {
                            rtl: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>