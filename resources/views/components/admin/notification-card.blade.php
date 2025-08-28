@props(['notification', 'isRead' => false])
<a href="{{ route('admin.notifications.show', $notification->id ?? '#') }}">
    <div class="flex items-start gap-3">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-orange-500/20 relative">
            <i data-lucide="{{ $notification->message_data['icon'] }}" class="w-4 h-4 text-orange-400"></i>
            @if (!$isRead)
                <span class="absolute top-0 right-0 w-2 h-2 bg-orange-500 rounded-full animate-ping"></span>
            @endif
        </div>
        <div class="flex-1">
            <p class="text-black dark:text-text-white text-sm font-medium mb-1 line-clamp-1">
                {{ $notification->message_data['title'] }}
            </p>
            <p class=" text-gray-600 dark:text-text-white/60 text-xs line-clamp-2">
                {{ $notification->message_data['message'] }}
            </p>
            <span class="dark:text-text-white/40 text-gray-400 text-xs">
                {{ $notification->created_at->diffForHumans() }}
            </span>
        </div>
    </div>
</a>
