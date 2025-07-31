<!-- resources/views/admin/statistics/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <x-heroicon-s-chart-bar class="ml-2 h-7 w-7 inline"/>
            {{ __('إحصائيات مفصلة') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.statistics.index') }}" class="mb-6 bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    
                    <!-- Tribunal Filter -->
                    <div class="mb-4">
                        <x-input-label for="tribunal_id" :value="__('المحكمة')" />
                        <div class="tribunal-search-container relative">
                            <input type="text" id="tribunal_search" placeholder="{{ __('ابحث عن محكمة...') }}" 
                                class="tribunal-search-input w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <select id="tribunal_id" name="tribunal_id" size="5" 
                                    class="tribunal-select hidden absolute left-0 right-0 mt-1 w-full border border-gray-300 rounded-md shadow-lg bg-white z-50">
                                <option value="">{{ __('كل المحاكم') }}</option>
                                @foreach($tribunals as $tribunal)
                                    <option value="{{ $tribunal->id }}" {{ request('tribunal_id') == $tribunal->id ? 'selected' : '' }}>
                                        {{ $tribunal->tribunal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Year Filter -->
                    <div>
                        <x-input-label for="year_of_judgment" :value="__('سنة الحكم')" />
                        <select id="year_of_judgment" name="year_of_judgment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">{{ __('كل السنوات') }}</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year_of_judgment') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-5 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 flex items-center gap-2">
                            <x-heroicon-o-funnel class="h-5 w-5"/>
                            {{ __('تصفية') }}
                        </button>
                        
                        <a href="{{ route('admin.statistics.index') }}" style="margin-right: 20px" class="px-5 py-2 rounded border-4 border-dashed text-gray-500 border-gray-500 hover:border-orange-500 hover:text-orange-500 flex items-center gap-2">
                            <x-heroicon-o-arrow-path class="h-5 w-5" style="stroke-width: 2.5;"/>
                        </a>

                        {{-- <a href="{{ route('admin.statistics.export', request()->query()) }}" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <x-heroicon-s-document-arrow-down class="ml-2 h-5 w-5 inline"/>
                            {{ __('تصدير PDF') }}
                        </a> --}}
                    </div>
                </div>
            </form>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <x-heroicon-o-archive-box class="h-8 w-8 text-purple-500"/>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">{{ __('إجمالي العلب المعالجة') }}</p>
                            <p class="text-2xl font-semibold">{{ $totalStats['total_boxes'] }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <x-heroicon-o-document-text class="h-8 w-8 text-orange-500"/>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">{{ __('إجمالي الملفات المعالجة') }}</p>
                            <p class="text-2xl font-semibold">{{ $totalStats['total_files'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics by Type -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">{{ __('الإحصائيات حسب النوع') }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('النوع') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('عدد العلب') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('عدد الملفات') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($statsByType as $type => $stats)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $stats['total_boxes'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $stats['total_files'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                            {{ __('لا توجد بيانات') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Visualization Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium mb-4">{{ __('توزيع العلب حسب النوع') }}</h3>
                    <canvas id="boxesByTypeChart" height="200"></canvas>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium mb-4">{{ __('توزيع الملفات حسب النوع') }}</h3>
                    <canvas id="filesByTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container = document.querySelector('.tribunal-search-container');
            const searchInput = document.getElementById('tribunal_search');
            const selectElement = document.getElementById('tribunal_id');
            const originalOptions = Array.from(selectElement.options);
            
            // Show dropdown when input is focused
            searchInput.addEventListener('focus', function() {
                selectElement.classList.remove('hidden');
                searchInput.classList.add('rounded-b-none');
                selectElement.style.top = `${searchInput.offsetHeight + searchInput.offsetTop}px`;
            });
            
            // Filter options based on search input
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                selectElement.innerHTML = '';
                
                originalOptions.forEach(option => {
                    if (option.text.toLowerCase().includes(searchTerm) || searchTerm === '') {
                        const newOption = new Option(option.text, option.value);
                        newOption.selected = option.selected;
                        selectElement.add(newOption);
                    }
                });
            });
    
            // Update search input when selection is made
            selectElement.addEventListener('change', function() {
                searchInput.value = this.options[this.selectedIndex].text;
                selectElement.classList.add('hidden');
                searchInput.classList.remove('rounded-b-none');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    selectElement.classList.add('hidden');
                    searchInput.classList.remove('rounded-b-none');
                }
            });


            // Prepare data for charts
            const types = @json($statsByType->keys());
            const boxesData = @json($statsByType->pluck('total_boxes'));
            const filesData = @json($statsByType->pluck('total_files'));
            
            // Colors for charts
            const backgroundColors = [
                'rgba(255, 99, 132, 0.7)',   // Red
                'rgba(54, 162, 235, 0.7)',   // Blue
                'rgba(255, 206, 86, 0.7)',   // Yellow
                'rgba(75, 192, 192, 0.7)',   // Teal
                'rgba(153, 102, 255, 0.7)',  // Purple
                'rgba(255, 159, 64, 0.7)',   // Orange
                'rgba(201, 203, 207, 0.7)',  // Grey
                'rgba(0, 204, 102, 0.7)',    // Green
                'rgba(255, 102, 255, 0.7)',  // Pink
                'rgba(0, 153, 255, 0.7)',    // Light Blue
                'rgba(255, 204, 0, 0.7)',    // Gold
                'rgba(102, 0, 204, 0.7)'     // Deep Purple
            ];
            
            // Boxes by Type Chart
            new Chart(
                document.getElementById('boxesByTypeChart').getContext('2d'),
                {
                    type: 'pie',
                    data: {
                        labels: types,
                        datasets: [{
                            data: boxesData,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                rtl: true
                            }
                        }
                    }
                }
            );
            
            // Files by Type Chart
            new Chart(
                document.getElementById('filesByTypeChart').getContext('2d'),
                {
                    type: 'doughnut',
                    data: {
                        labels: types,
                        datasets: [{
                            data: filesData,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                rtl: true
                            }
                        }
                    }
                }
            );
        });
    </script>
    @endpush
    @push('styles')
    <style>
        .tribunal-search-container {
            position: relative;
        }
        
        .tribunal-search-input {
            padding-right: 2.5rem;
            transition: all 0.2s ease;
        }
        
        .tribunal-search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .tribunal-select {
            max-height: 250px;
            overflow-y: auto;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .tribunal-select option {
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .tribunal-select option:hover {
            background-color: #f8fafc;
        }
        
        .tribunal-select option:last-child {
            border-bottom: none;
        }
        
        /* Search icon */
        .tribunal-search-container::after {
            content: "🔍";
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
    @endpush
</x-app-layout>
