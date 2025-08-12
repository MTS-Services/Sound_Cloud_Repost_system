<div class="border-b border-gray-100 dark:border-slate-700 last:border-b-0">
    <div @click="handleNotificationClick(notification)"
        class="p-4 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors"
        :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.is_read }">

        <div class="flex items-start space-x-3">
            <!-- Notification Icon -->
            <div class="flex-shrink-0 mt-1">
                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                    :class="notification.is_read ? 'bg-gray-200 dark:bg-slate-600' : 'bg-orange-100 dark:bg-orange-900/30'">
                    <svg class="w-4 h-4"
                        :class="notification.is_read ? 'text-gray-500 dark:text-gray-400' :
                            'text-orange-600 dark:text-orange-400'"
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
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate"
                            x-text="notification.title">
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-2"
                            x-text="notification.message">
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2" x-text="notification.created_at">
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <x-admin.notification.notification-actions />
                </div>
            </div>

            <!-- Unread Indicator -->
            <div x-show="!notification.is_read" class="flex-shrink-0">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
            </div>
        </div>
    </div>
</div>
