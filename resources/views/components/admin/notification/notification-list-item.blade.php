<div @click="handleNotificationClick(notification)"
    class="p-6 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors"
    :class="{ 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500': !notification.is_read }">

    <div class="flex items-start space-x-4">
        <!-- Notification Icon -->
        <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                :class="notification.is_read ? 'bg-gray-200 dark:bg-slate-600' : 'bg-orange-100 dark:bg-orange-900/30'">
                <svg class="w-5 h-5"
                    :class="notification.is_read ? 'text-gray-500 dark:text-gray-400' : 'text-orange-600 dark:text-orange-400'"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <!-- Notification Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white" x-text="notification.title">
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1" x-text="notification.message">
                    </p>
                    <div class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span x-text="notification.created_at"></span>
                        <span x-show="!notification.is_read"
                            class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full">
                            New
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-2 ml-4">
                    <button @click.stop="toggleReadStatus(notification)"
                        class="p-2 rounded-md hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
                        :title="notification.is_read ? 'Mark as unread' : 'Mark as read'">
                        <svg class="w-4 h-4" :class="notification.is_read ? 'text-gray-400' : 'text-blue-500'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="p-2 rounded-md hover:bg-red-100 dark:hover:bg-red-900/30 text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
