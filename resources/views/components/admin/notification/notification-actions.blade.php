<div class="flex items-center space-x-1 ml-2">
    <button @click.stop="toggleReadStatus(notification)"
        class="p-1 rounded hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
        :title="notification.is_read ? 'Mark as unread' : 'Mark as read'">
        <svg class="w-4 h-4" :class="notification.is_read ? 'text-gray-400' : 'text-blue-500'" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <template x-if="notification.is_read">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </template>
            <template x-if="!notification.is_read">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </template>
        </svg>
    </button>

    <button @click.stop="deleteNotification(notification)"
        class="p-1 rounded hover:bg-red-100 dark:hover:bg-red-900/30 text-red-500 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>
</div>
