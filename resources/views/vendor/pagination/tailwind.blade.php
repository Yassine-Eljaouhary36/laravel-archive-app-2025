@if ($paginator->hasPages())
    <nav class="flex items-center justify-between my-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md cursor-not-allowed">
                &laquo;
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                &laquo;
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex space-x-2">
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 bg-blue-600 text-white rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 bg-white text-blue-600 rounded-md hover:bg-blue-100 transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                &raquo;
            </a>
        @else
            <span class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md cursor-not-allowed">
                &raquo;
            </span>
        @endif
    </nav>
@endif