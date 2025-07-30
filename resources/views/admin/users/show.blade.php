<x-app-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تفاصيل المستخدم') }} - {{ $user->name }}
        </h2>
        <div class="space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-heroicon-o-pencil class="h-4 w-4 mr-1"/>
                {{ __('تعديل') }}
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-heroicon-o-arrow-left class="h-4 w-4 mr-1"/>
                {{ __('رجوع') }}
            </a>
        </div>
    </div>
</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Profile Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- User Avatar and Basic Info -->
                        <div class="flex-shrink-0 flex flex-col items-center">
                            <div class="h-32 w-32 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                                <span class="text-4xl font-bold text-indigo-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                <div class="mt-2">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? __('نشط') : __('غير نشط') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- User Details -->
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">{{ __('معلومات الحساب') }}</h4>
                                    <dl class="mt-2 space-y-3">
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">{{ __('الدور') }}</dt>
                                            <dd class="text-sm font-medium text-gray-900 capitalize">
                                                {{ $user->getRoleNames()->first() }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">{{ __('تاريخ الإنشاء') }}</dt>
                                            <dd class="text-sm font-medium text-gray-900">
                                                {{ $user->created_at->translatedFormat('d F Y') }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dd class="text-sm font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <!-- Status Toggle -->
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="pt-4 border-t border-gray-200">
                                    @csrf
                                    @method('PATCH')
                                    <x-primary-button type="submit" class="{{ $user->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">
                                        {{ $user->is_active ? __('تعطيل الحساب') : __('تفعيل الحساب') }}
                                    </x-primary-button>
                                </form>
                            </div>

                            <!-- Quick Stats -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-medium text-gray-500">{{ __('إحصائيات سريعة') }}</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Total Boxes -->
                                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl shadow border-l-4 border-blue-500">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-600">{{ __('إجمالي العلب') }}</h3>
                                                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $user->boxes_count }}</p>
                                            </div>
                                            <div class="bg-blue-100 p-2 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Validated Boxes -->
                                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl shadow border-l-4 border-green-500">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-600">{{ __('العلب المعالجة') }}</h3>
                                                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $user->validated_boxes_count }}</p>
                                            </div>
                                            <div class="bg-green-100 p-2 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pending Boxes -->
                                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-xl shadow border-l-4 border-yellow-500">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-600">{{ __('العلب قيد المعالجة') }}</h3>
                                                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['pending_boxes_count'] }}</p>
                                            </div>
                                            <div class="bg-yellow-100 p-2 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Files Count -->
                                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl shadow border-l-4 border-purple-500">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-600">{{ __('الملفات') }}</h3>
                                                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['files_count'] }}</p>
                                            </div>
                                            <div class="bg-purple-100 p-2 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-xs text-gray-500">{{ __('معدل الاعتماد') }}</p>
                                    <div class="flex items-center">
                                        <p class="text-2xl font-bold text-blue-600">{{ $stats['validation_rate'] }}%</p>
                                        <div class="ml-2 w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" 
                                                 style="width: {{ $stats['validation_rate'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                <!-- Recent Activity -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('النشاط الأخير') }}</h3>
                        <div class="space-y-4">
                            @forelse($user->boxes->take(5) as $box)
                            <div class="flex items-start p-4 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="h-10 w-10 rounded-full {{ $box->validated_at ? 'bg-green-100' : 'bg-yellow-100' }} flex items-center justify-center">
                                        <x-heroicon-o-archive-box class="h-5 w-5 {{ $box->validated_at ? 'text-green-500' : 'text-yellow-500' }}"/>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            علبة رقم {{ $box->box_number }}
                                        </h4>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $box->validated_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $box->validated_at ? 'معتمدة' : 'معلقة' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($box->created_at)->diffForHumans() }} • 
                                        {{ $box->files_count }} ملفات
                                        @if($box->tribunal)
                                        • {{ $box->tribunal->tribunal }}
                                        @endif
                                    </p>
                                    @if($box->validated_at)
                                    <p class="text-xs text-gray-400 mt-1">
                                        تم الاعتماد {{ \Carbon\Carbon::parse($box->validated_at)->diffForHumans() }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500 text-center py-4">{{ __('لا يوجد نشاط مسجل') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('الأداء الشهري') }}</h3>
                        <div class="h-64">
                            <canvas id="performanceChart"
                                    data-labels="{{ json_encode(['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']) }}"
                                    data-boxes="{{ json_encode(array_column($stats['monthly_activity'], 'boxes')) }}"
                                    data-validated="{{ json_encode(array_column($stats['monthly_activity'], 'validated')) }}">
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Weekly Activity -->
                <div class="bg-white p-6 rounded-lg shadow col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('النشاط الأسبوعي') }}</h3>
                    <div class="h-64">
                        <canvas id="weeklyChart"
                                data-days="{{ json_encode($stats['weekly_activity']['days']) }}"
                                data-added="{{ json_encode(array_values($stats['weekly_activity']['boxes_added'])) }}"
                                data-validated="{{ json_encode(array_values($stats['weekly_activity']['boxes_validated'])) }}">
                        </canvas>
                    </div>
                </div>

                <!-- Average Creation Time -->
                <div class="bg-white p-6 rounded-lg shadow col-span-1">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('متوسط وقت الإنشاء') }}</h3>
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <p class="text-5xl font-bold text-indigo-600">
                                {{ round($stats['avg_box_creation_time'] / 60, 1) }}
                            </p>
                            <p class="text-lg text-gray-600 mt-2">{{ __('ساعة لكل علبة') }}</p>
                            <p class="text-sm text-gray-400 mt-4">
                                {{ __('متوسط الوقت المستغرق لإنشاء العلبة') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('performanceChart');
        if (ctx) {
            const labels = JSON.parse(ctx.dataset.labels);
            const boxesData = JSON.parse(ctx.dataset.boxes);
            const validatedData = JSON.parse(ctx.dataset.validated);
            
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'العلب المضافة',
                            data: boxesData,
                            borderColor: 'rgba(79, 70, 229, 1)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'العلب المعتمدة',
                            data: validatedData,
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
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
                                    return `${context.dataset.label}: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }


        // Weekly Activity Chart
        const weeklyCtx = document.getElementById('weeklyChart');
        if (weeklyCtx) {
            new Chart(weeklyCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: JSON.parse(weeklyCtx.dataset.days),
                    datasets: [
                        {
                            label: 'العلب المضافة',
                            data: JSON.parse(weeklyCtx.dataset.added),
                            borderColor: 'rgba(79, 70, 229, 1)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'العلب المعتمدة',
                            data: JSON.parse(weeklyCtx.dataset.validated),
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
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
                            rtl: true
                        }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>