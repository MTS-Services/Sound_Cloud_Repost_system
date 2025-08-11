<div
    class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-orange-200 dark:border-gray-700 sticky top-16 z-20">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4 flex-wrap">
                {{-- Filter Buttons --}}
                <div class="join shadow-lg">
                    <button wire:click="$set('filter', 'all')"
                        class="btn join-item {{ $filter === 'all' ? 'btn-active bg-orange-500 text-white border-orange-500' : 'btn-outline border-orange-300 text-orange-600 hover:bg-orange-500 hover:text-white hover:border-orange-500' }}">
                        <i class="fas fa-list mr-1"></i>
                        All
                        <span
                            class="badge badge-xs ml-1 bg-orange-100 text-orange-800 border-none">{{ $totalCount }}</span>
                    </button>

                    <button wire:click="$set('filter', 'unread')"
                        class="btn join-item {{ $filter === 'unread' ? 'btn-active bg-orange-500 text-white border-orange-500' : 'btn-outline border-orange-300 text-orange-600 hover:bg-orange-500 hover:text-white hover:border-orange-500' }}">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Unread
                        @if ($unreadCount > 0)
                            <span
                                class="badge badge-xs ml-1 bg-red-500 text-white border-none blink-indicator">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <button wire:click="$set('filter', 'read')"
                        class="btn join-item {{ $filter === 'read' ? 'btn-active bg-orange-500 text-white border-orange-500' : 'btn-outline border-orange-300 text-orange-600 hover:bg-orange-500 hover:text-white hover:border-orange-500' }}">
                        <i class="fas fa-check-circle mr-1"></i>
                        Read
                        <span
                            class="badge badge-xs ml-1 bg-green-100 text-green-800 border-none">{{ $readCount }}</span>
                    </button>
                </div>

                {{-- Reset Filters --}}
                @if ($filter !== 'all' || $search || $sort !== 'newest')
                    <button wire:click="resetFilters"
                        class="btn btn-sm btn-ghost text-orange-600 hover:bg-orange-100 dark:hover:bg-orange-900/30">
                        <i class="fas fa-undo mr-2"></i>
                        Reset
                    </button>
                @endif
            </div>

            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search notifications..."
                        class="input input-sm input-bordered w-64 border-orange-300 focus:border-orange-500 focus:outline-none pr-10" />
                    @if ($search)
                        <button wire:click="clearSearch"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    @else
                        <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    @endif
                </div>

                {{-- Sort --}}
                <select wire:model.live="sort"
                    class="select select-sm select-bordered border-orange-300 focus:border-orange-500">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="unread">Unread First</option>
                    <option value="type">By Type</option>
                </select>

                {{-- Loading Indicator --}}
                <div wire:loading class="flex items-center text-orange-600">
                    <div class="loading loading-spinner loading-sm mr-2"></div>
                    <span class="text-sm">Filtering...</span>
                </div>
            </div>
        </div>

        {{-- Active Filters Display --}}
        @if ($search || $filter !== 'all' || $sort !== 'newest')
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-orange-200 dark:border-gray-700">
                <span class="text-sm text-gray-600 dark:text-gray-400">Active filters:</span>

                @if ($filter !== 'all')
                    <span class="badge bg-orange-100 text-orange-800 border-orange-300">
                        Filter: {{ ucfirst($filter) }}
                        <button wire:click="$set('filter', 'all')" class="ml-1 hover:text-red-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif

                @if ($search)
                    <span class="badge bg-blue-100 text-blue-800 border-blue-300">
                        Search: "{{ $search }}"
                        <button wire:click="clearSearch" class="ml-1 hover:text-red-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif

                @if ($sort !== 'newest')
                    <span class="badge bg-purple-100 text-purple-800 border-purple-300">
                        Sort: {{ ucfirst(str_replace('_', ' ', $sort)) }}
                        <button wire:click="$set('sort', 'newest')" class="ml-1 hover:text-red-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
            </div>
        @endif
    </div>

    <style>
        @keyframes blink {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0.4;
            }
        }

        .blink-indicator {
            animation: blink 2s infinite;
        }
    </style>
</div>
