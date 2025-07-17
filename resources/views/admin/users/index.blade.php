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
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Users Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Responsive Table Container -->
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" style="text-align: start" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('البريد الإلكتروني') }}</th>
                                    <th scope="col" style="text-align: start" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الدور') }}</th>
                                    <th scope="col" style="text-align: start" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الحالة') }}</th>
                                    <th scope="col" style="text-align: start" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('الاسم') }}</th>
                                    <th scope="col" style="text-align: start" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $user->getRoleNames()->first() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? __('نشيط') : __('غير نشط') }}
                                            </span>
                                        </td>
                                        <td style="display: flex" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                                style="margin: 0px 10px"
                                               class="text-blue-600 hover:text-blue-900">
                                                <x-heroicon-o-eye class="h-5 w-5 inline" />
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                                style="margin: 0px 10px"
                                               class="text-indigo-600 hover:text-indigo-900">
                                                <x-heroicon-o-pencil class="h-5 w-5 inline" />
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No users found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($users->hasPages())
                        <div class="mt-4 px-6 py-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>