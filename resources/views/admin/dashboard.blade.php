<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('لوحة تحكم المسؤول') }}
            </h2>
            <div class="text-sm text-gray-500">
                آخر تحديث: {{ now()->translatedFormat('l، j F Y - h:i A') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Boxes -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl shadow border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">{{ __('إجمالي العلب') }}</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $boxStats['total'] }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <x-heroicon-o-archive-box class="h-6 w-6 text-blue-600"/>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-blue-600">
                        <x-heroicon-o-arrow-trending-up class="h-4 w-4 mr-1"/>
                        <span>{{ $boxStats['this_week'] }} هذا الأسبوع</span>
                    </div>
                </div>

                <!-- Validated Boxes -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl shadow border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">{{ __('العلب المعتمدة') }}</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $boxStats['validated'] }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-green-600"/>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-green-600">
                        <span>معدل الاعتماد: {{ $boxStats['validation_rate'] }}%</span>
                    </div>
                </div>

                <!-- Pending Boxes -->
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-6 rounded-xl shadow border-l-4 border-yellow-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">{{ __('العلب المعلقة') }}</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $boxStats['pending'] }}</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <x-heroicon-o-clock class="h-6 w-6 text-yellow-600"/>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-yellow-600">
                        <span>متوسط وقت المعالجة: {{ round($performanceStats['avg_validation_time'], 1) }} ساعة</span>
                    </div>
                </div>

                <!-- Users -->
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl shadow border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">{{ __('المستخدمين') }}</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $userStats['total'] }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <x-heroicon-o-users class="h-6 w-6 text-purple-600"/>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-purple-600">
                        <span>{{ $userStats['active'] }} نشط | {{ $userStats['new_this_month'] }} جديد هذا الشهر</span>
                    </div>
                </div>
                
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Performance Section -->
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('أداء الموظفين') }}</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-x divide-gray-200">
                            <!-- Top Performers -->
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-medium text-gray-700">{{ __('أفضل الموظفين') }}</h4>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">العلب المعتمدة</span>
                                </div>
                                <div class="space-y-4">
                                    @foreach($performanceStats['top_performers'] as $user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                <p class="text-sm font-bold text-blue-600">{{ $user->boxes_count }}</p>
                                            </div>
                                            <div class="mt-1">
                                                <div class="h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-blue-500 rounded-full" 
                                                         style="width: {{ min(100, ($user->boxes_count / max(1, $performanceStats['top_performers']->first()->boxes_count)) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    {{ $performanceStats['top_performers']->links() }}
                                </div>
                            </div>

                            <!-- Slow Performers -->
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-medium text-gray-700">{{ __('أقل الموظفين إنتاجية') }}</h4>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">العلب المعلقة</span>
                                </div>
                                <div class="space-y-4">
                                    @foreach($performanceStats['slow_performers'] as $user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <span class="text-yellow-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                <p class="text-sm font-bold text-yellow-600">{{ $user->boxes_count }}</p>
                                            </div>
                                            <div class="mt-1">
                                                <div class="h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-yellow-500 rounded-full" 
                                                         style="width: {{ min(100, ($user->boxes_count / max(1, $performanceStats['slow_performers']->first()->boxes_count)) * 100 )}}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    {{ $performanceStats['slow_performers']->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('النشاط الأخير') }}</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($recentActivity as $activity)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-{{ $activity->isValidated() ? 'green' : 'yellow' }}-100 flex items-center justify-center">
                                            <x-heroicon-o-archive-box class="h-5 w-5 text-{{ $activity->isValidated() ? 'green' : 'yellow' }}-500"/>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">
                                                علبة رقم {{ $activity->box_number }}
                                            </p>
                                            <span class="text-xs px-2 py-1 rounded-full bg-{{ $activity->isValidated() ? 'green' : 'yellow' }}-100 text-{{ $activity->isValidated() ? 'green' : 'yellow' }}-800">
                                                {{ $activity->isValidated() ? 'معتمدة' : 'معلقة' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $activity->user->name }} • 
                                            {{ $activity->created_at->diffForHumans() }}
                                            @if($activity->isValidated())
                                            • تم الاعتماد بواسطة {{ $activity->validator->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">

                    <!-- File Statistics Card -->
                    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 p-6 rounded-xl shadow border-l-4 border-indigo-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-gray-700">{{ __('الملفات') }}</h3>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $fileStats['total'] }}</p>
                            </div>
                            <div class="bg-indigo-100 p-3 rounded-lg">
                                <x-heroicon-o-document-text class="h-6 w-6 text-indigo-600"/>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                            <div class="text-indigo-600">
                                <x-heroicon-o-arrow-trending-up class="h-4 w-4 inline mr-1"/>
                                <span>{{ $fileStats['today'] }} اليوم</span>
                            </div>
                            <div class="text-indigo-600">
                                <x-heroicon-o-calendar class="h-4 w-4 inline mr-1"/>
                                <span>{{ $fileStats['this_week'] }} هذا الأسبوع</span>
                            </div>
                            <div class="col-span-2 text-indigo-600 mt-2">
                                <x-heroicon-o-cube class="h-4 w-4 inline mr-1"/>
                                <span>متوسط {{ round($fileStats['avg_per_box'], 1) }} ملف لكل علبة</span>
                            </div>
                            @if($fileStats['largest_box'])
                            <div class="col-span-2 text-indigo-600 mt-2">
                                <x-heroicon-o-trophy class="h-4 w-4 inline mr-1"/>
                                <span>أكبر علبة: {{ $fileStats['largest_box']->files_count }} ملف ({{ $fileStats['largest_box']->box_number }})</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Tribunal Statistics -->
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('توزيع العلب حسب المحكمة') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($tribunalStats as $tribunal)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-700">{{ $tribunal->tribunal }}</span>
                                        <span class="font-bold text-indigo-600">{{ $tribunal->boxes_count }} ({{ round(($tribunal->boxes_count / $boxStats['total']) * 100, 1) }}%)</span>
                                    </div>
                                    <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 rounded-full" 
                                             style="width: {{ ($tribunal->boxes_count / $boxStats['total']) * 100 }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                {{ $tribunalStats->links() }}
                            </div>
                        </div>
                    </div>

                    <!-- Validation Status Chart -->
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('حالة الاعتماد') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="h-64">
                                <canvas id="validationChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('validationChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['معتمدة', 'معلقة'],
                    datasets: [{
                        data: [{{ $boxStats['validated'] }}, {{ $boxStats['pending'] }}],
                        backgroundColor: [
                            '#10B981',
                            '#F59E0B'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            rtl: true
                        },
                        tooltip: {
                            rtl: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>