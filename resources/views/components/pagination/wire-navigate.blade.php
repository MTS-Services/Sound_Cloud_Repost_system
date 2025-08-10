
@php
    // Name of the page query param (e.g. tracksPage, playlistsPage, playlistTracksPage)
    $pageParam = $pageName ?? $paginator->getPageName();

    // Extra params to keep across navigation (e.g., ['tab' => 'tracks', 'selectedPlaylistId' => 123])
    $keep = $keep ?? [];

    // Build a RELATIVE querystring URL like ?tab=tracks&tracksPage=2
    $makeRelativeUrl = function (int $page) use ($keep, $pageParam) {
        $qs = http_build_query(array_filter(array_merge($keep, [$pageParam => $page]), fn ($v) => $v !== null && $v !== ''));
        return $qs ? ('?' . $qs) : '?';
    };
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between gap-2" x-data>
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-transparent text-gray-400 dark:text-slate-500 bg-gray-100 dark:bg-slate-800 cursor-not-allowed">
                Previous
            </span>
        @else
            <a
                href="{{ $makeRelativeUrl($paginator->currentPage() - 1) }}"
                wire:navigate
                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800"
            >
                Previous
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="hidden md:flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" --}}
                @if (is_string($element))
                    <span class="px-2 text-sm text-gray-500 dark:text-slate-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center px-3 py-2 text-sm font-semibold rounded-md text-white bg-orange-500 dark:bg-orange-600">
                                {{ $page }}
                            </span>
                        @else
                            <a
                                href="{{ $makeRelativeUrl($page) }}"
                                wire:navigate
                                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800"
                            >
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a
                href="{{ $makeRelativeUrl($paginator->currentPage() + 1) }}"
                wire:navigate
                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-800"
            >
                Next
            </a>
        @else
            <span class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-transparent text-gray-400 dark:text-slate-500 bg-gray-100 dark:bg-slate-800 cursor-not-allowed">
                Next
            </span>
        @endif
    </nav>
@endif