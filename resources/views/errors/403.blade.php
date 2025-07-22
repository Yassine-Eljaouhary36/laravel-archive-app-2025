<x-app-layout>
    <div class="flex flex-col items-center justify-center min-h-screen text-center px-6 py-12 bg-white text-gray-800">
        <x-heroicon-s-shield-exclamation class="w-16 h-16 text-yellow-500 mb-4" />
        <h1 class="font-bold text-yellow-600" style="font-size: 40px">403</h1>
        
        <p class="mt-2 text-lg font-bold">عذرًا، لا تملك صلاحية الوصول إلى هذه الصفحة.</p>

        <a href="{{ url('/') }}"
           class="mt-6 inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <x-heroicon-s-arrow-left class="w-5 h-5" />
            الرجوع إلى الصفحة الرئيسية
        </a>
    </div>
</x-app-layout>
