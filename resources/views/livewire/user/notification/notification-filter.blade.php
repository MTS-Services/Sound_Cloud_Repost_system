<div>
    <div class="join shadow-lg">
        <button wire:click="$set('filter', 'all')"
            class="btn join-item {{ $filter === 'all' ? 'btn-active bg-orange-500 text-white border-orange-500' : 'btn-outline border-orange-300 text-orange-600 hover:bg-orange-500 hover:text-white hover:border-orange-500' }}">
            <i class="fas fa-list mr-1"></i>
            All
            <span class="badge badge-xs ml-1 bg-orange-100 text-orange-800 border-none">{{ $totalCount }}</span>
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
            <span class="badge badge-xs ml-1 bg-green-100 text-green-800 border-none">{{ $readCount }}</span>
        </button>
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
