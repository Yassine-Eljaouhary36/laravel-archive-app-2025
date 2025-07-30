<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('لوحة تحكم المراقب') }}
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
                <!-- Pending Validation -->
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-6 rounded-xl shadow border-l-4 border-yellow-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">{{ __('العلب قيد المعالجة') }}</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $pending_validation }}</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <x-heroicon-o-clock class="h-6 w-6 text-yellow-600"/>
                        </div>
                    </div>
                  
                </div>

                <!-- Total Validated -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl shadow border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">{{ __('إجمالي المعالجة') }}</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $total_validated }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-green-600"/>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-green-600">
                        <span>{{ $today_validated }} تم معالجتها اليوم</span>
                    </div>
                </div>

                <!-- Validation Rate -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl shadow border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">{{ __('معدل المعالجة') }}</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($validation_rate, 2) }}%</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <x-heroicon-o-chart-bar class="h-6 w-6 text-blue-600"/>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-blue-600">
                        <span>متوسط وقت المعالجة: {{ round($avg_validation_time, 1) }} ساعة</span>
                    </div>
                </div>

                <!-- Files Statistics -->
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl shadow border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-700">{{ __('الملفات') }}</h3>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $files_stats['total'] }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <x-heroicon-o-document-text class="h-6 w-6 text-purple-600"/>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-purple-600">
                        <span>{{ $files_stats['validated'] }} ملف معالج</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recent Validations -->
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('آخر العلب المعالجة') }}</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach($recently_validated as $box)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <x-heroicon-o-archive-box class="h-5 w-5 text-green-500"/>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">
                                                علبة رقم {{ $box->box_number }}
                                            </p>
                                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                                                {{ __('المعالجة') }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $box->user->name }} • 
                                            المحكمة: {{ $box->tribunal->tribunal ?? 'غير محدد' }} •
                                            {{ \Carbon\Carbon::parse($box->validated_at)->diffForHumans() }}
                                        </p>
                                        <div class="mt-2 text-xs text-gray-500">
                                            <span class="inline-flex items-center">
                                                <x-heroicon-o-document-text class="h-3 w-3 mr-1"/>
                                                {{ $box->files_count }} ملفات
                                            </span>
                                            <span class="inline-flex items-center ml-4">
                                                <x-heroicon-o-clock class="h-3 w-3 mr-1"/>
                                                    معالجة: {{ round(\Carbon\Carbon::parse($box->validated_at)->diffInHours(\Carbon\Carbon::parse($box->created_at)), 1) }} ساعات
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Validation Stats -->
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('إحصائيات المعالجة') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="h-64">
                                <canvas id="validationChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Metrics -->
                    <div class="bg-white rounded-xl shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('مقاييس الأداء') }}</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-1">{{ __('متوسط وقت المعالجة') }}</h4>
                                <p class="text-2xl font-bold text-blue-600">{{ round($avg_validation_time, 1) }} ساعة</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-1">{{ __('الملفات المعالجة') }}</h4>
                                <p class="text-2xl font-bold text-green-600">{{ $files_stats['validated'] }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-1">{{ __('معدل المعالجة') }}</h4>
                                <div class="flex items-center">
                                    <p class="text-2xl font-bold text-purple-600">{{ $validation_rate }}%</p>
                                    @if($validation_rate > 75)
                                    <x-heroicon-o-arrow-trending-up class="h-5 w-5 ml-2 text-green-500"/>
                                    @elseif($validation_rate < 50)
                                    <x-heroicon-o-arrow-trending-down class="h-5 w-5 ml-2 text-red-500"/>
                                    @else
                                    <x-heroicon-o-arrow-right class="h-5 w-5 ml-2 text-yellow-500"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation Status Chart
        if (document.getElementById('validationChart')) {
            new Chart(document.getElementById('validationChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['المعالجة بواسطتك', 'قيد المعالجة'],
                    datasets: [{
                        data: [{{ $total_validated }}, {{ $pending_assigned }}],
                        backgroundColor: ['#10B981', '#F59E0B'],
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
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((context.raw / total) * 100);
                                    return `${context.label}: ${context.raw} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>