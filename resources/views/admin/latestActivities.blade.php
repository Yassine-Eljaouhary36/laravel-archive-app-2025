<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{  __('النشاط الأخير') }}
            </h2>
            <div class="text-sm text-gray-500">
                آخر تحديث: {{ now()->translatedFormat('l، j F Y - h:i A') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols gap-8">
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
                                            {{ $activity->isValidated() ? 'المعالجة' : 'قيد المعالجة' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $activity->user->name }} • 
                                        {{ $activity->created_at->diffForHumans() }}
                                        @if($activity->isValidated())
                                        • تمت معالجته بواسطة{{ $activity->validator->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="p-4">
                        {{ $recentActivity->links() }}
                    </div>

                </div>

            </div>
        </div>
    </div>

</x-app-layout>