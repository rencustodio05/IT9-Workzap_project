@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4">
    <span class="inline-flex rounded-lg shadow-sm">
        @if (! $paginator->onFirstPage())
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-50 hover:text-gray-900 transition">
            <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Back
        </a>
        @endif

        @foreach ($elements as $element)
        @if (is_string($element))
        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border-t border-b border-gray-200">{{ $element }}</span>
        @endif

        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <span aria-current="page" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-slate-900 border border-gray-200">{{ $page }}</span>
        @else
        <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition">{{ $page }}</a>
        @endif
        @endforeach
        @endif
        @endforeach

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-r-lg hover:bg-gray-50 hover:text-gray-900 transition">
            Next
            <svg class="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
        @else
        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-r-lg cursor-not-allowed">
            Next
            <svg class="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </span>
        @endif
    </span>
</nav>
@else
<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4">
    <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-white bg-slate-900 border border-gray-200">1</span>
    <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-r-lg cursor-not-allowed">
        Next
    </span>
</nav>
@endif