@if ($paginator->hasPages())
    <nav class="flex flex-col sm:flex-row items-center justify-between my-4 gap-4">
        <div class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 sm:px-4 sm:py-2 bg-gray-200 text-gray-600 rounded-md cursor-not-allowed text-sm sm:text-base">
                    &laquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 sm:px-4 sm:py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition text-sm sm:text-base">
                    &laquo;
                </a>
            @endif
        </div>

        {{-- Pagination Elements --}}
        <div class="flex flex-wrap justify-center gap-1 sm:gap-2">
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 sm:px-4 sm:py-2 bg-blue-600 text-white rounded-md text-sm sm:text-base">{{ $page }}</span>
                        @elseif ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2)
                            <a href="{{ $url }}" class="px-3 py-1 sm:px-4 sm:py-2 bg-white border border-gray-300 text-blue-600 rounded-md hover:bg-blue-100 transition text-sm sm:text-base">{{ $page }}</a>
                        @elseif ($page == 1 || $page == $paginator->lastPage())
                            <a href="{{ $url }}" class="px-3 py-1 sm:px-4 sm:py-2 bg-white border border-gray-300 text-blue-600 rounded-md hover:bg-blue-100 transition text-sm sm:text-base">{{ $page }}</a>
                        @elseif ($page == $paginator->currentPage() - 3 || $page == $paginator->currentPage() + 3)
                            <span class="px-3 py-1 sm:px-4 sm:py-2 text-gray-500">...</span>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        <div class="flex items-center gap-2">
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 sm:px-4 sm:py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition text-sm sm:text-base">
                    &raquo;
                </a>
            @else
                <span class="px-3 py-1 sm:px-4 sm:py-2 bg-gray-200 text-gray-600 rounded-md cursor-not-allowed text-sm sm:text-base">
                    &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif