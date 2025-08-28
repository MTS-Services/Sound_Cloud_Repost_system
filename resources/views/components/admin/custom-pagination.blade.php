{{-- Custom Pagination Component --}}
@props(['paginator', 'filterType' => 'all', 'showInfo' => true, 'showSizeSelector' => false, 'maxLinks' => 7])

@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Pagination Info --}}
        @if ($showInfo)
            <div class="text-sm text-gray-600 dark:text-gray-300 order-2 sm:order-1">
                <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                to
                <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                of
                <span class="font-medium">{{ $paginator->total() }}</span>
                results
            </div>
        @endif

        {{-- Pagination Links --}}
        <nav class="flex items-center gap-1 order-1 sm:order-2" aria-label="Pagination Navigation">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-gray-400 dark:text-gray-600 cursor-not-allowed rounded-lg">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </span>
            @else
                <a href="{{ request()->fullUrlWithQuery([$filterType => $paginator->currentPage() - 1]) }}"
                    class="px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-orange-500/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-lg transition-colors duration-200">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @php
                $start = max(1, $paginator->currentPage() - floor($maxLinks / 2));
                $end = min($paginator->lastPage(), $start + $maxLinks - 1);

                if ($end - $start + 1 < $maxLinks) {
                    $start = max(1, $end - $maxLinks + 1);
                }
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <a href="{{ request()->fullUrlWithQuery([$filterType => 1]) }}"
                    class="px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-orange-500/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-lg transition-colors duration-200">
                    1
                </a>
                @if ($start > 2)
                    <span class="px-2 py-2 text-gray-400 dark:text-gray-600">...</span>
                @endif
            @endif

            {{-- Page Links --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-2 bg-orange-500 text-white font-medium rounded-lg shadow-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ request()->fullUrlWithQuery([$filterType => $page]) }}"
                        class="px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-orange-500/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-lg transition-colors duration-200">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <span class="px-2 py-2 text-gray-400 dark:text-gray-600">...</span>
                @endif
                <a href="{{ request()->fullUrlWithQuery([$filterType => $paginator->lastPage()]) }}"
                    class="px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-orange-500/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-lg transition-colors duration-200">
                    {{ $paginator->lastPage() }}
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ request()->fullUrlWithQuery([$filterType => $paginator->currentPage() + 1]) }}"
                    class="px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-orange-500/20 hover:text-orange-600 dark:hover:text-orange-400 rounded-lg transition-colors duration-200">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @else
                <span class="px-3 py-2 text-gray-400 dark:text-gray-600 cursor-not-allowed rounded-lg">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </span>
            @endif
        </nav>

        {{-- Page Size Selector --}}
        @if ($showSizeSelector)
            <div class="flex items-center gap-2 text-sm order-3">
                <label class="text-gray-600 dark:text-gray-300">Show:</label>
                <select onchange="updatePageSize(this.value)"
                    class="px-2 py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        @endif
    </div>

    {{-- Mobile Compact Version --}}
    <div class="sm:hidden mt-4">
        <div
            class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="flex-1 px-4 py-3 text-center text-gray-400 dark:text-gray-600 cursor-not-allowed">
                    Previous
                </span>
            @else
                <a href="{{ request()->fullUrlWithQuery([$filterType => $paginator->currentPage() - 1]) }}"
                    class="flex-1 px-4 py-3 text-center text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    Previous
                </a>
            @endif

            {{-- Current Page Info --}}
            <span
                class="px-4 py-3 bg-orange-500/10 text-orange-600 dark:text-orange-400 font-medium text-center border-x border-gray-200 dark:border-gray-700">
                {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
            </span>

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ request()->fullUrlWithQuery([$filterType => $paginator->currentPage() + 1]) }}"
                    class="flex-1 px-4 py-3 text-center text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                    Next
                </a>
            @else
                <span class="flex-1 px-4 py-3 text-center text-gray-400 dark:text-gray-600 cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>
    </div>

    @if ($showSizeSelector)
        <script>
            function updatePageSize(perPage) {
                const url = new URL(window.location);
                url.searchParams.set('per_page', perPage);
                url.searchParams.set('{{ $filterType }}', 1); // Reset to first page
                window.location.href = url.toString();
            }
        </script>
    @endif
@endif
