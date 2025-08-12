<div x-data="notificationList()" x-init="init()" class="bg-white dark:bg-slate-800 rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                All Notifications
                <span x-show="unreadCount > 0" x-text="'(' + unreadCount + ' unread)'"
                    class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                </span>
            </h2>
            <button @click="markAllAsRead()" x-show="unreadCount > 0"
                class="px-4 py-2 text-sm bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors">
                Mark All Read
            </button>
        </div>

        <!-- Search and Filters -->
        <x-admin.notification.notification-list-filters />
    </div>

    <!-- Notifications List -->
    <div class="divide-y divide-gray-200 dark:divide-slate-700">
        <div x-show="loading" class="p-8 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-orange-600"></div>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Loading notifications...</p>
        </div>

        <div x-show="!loading && notifications.length === 0" class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M15 17h5l-5-5V9.09c0-5.33-3.67-9.09-9-9.09S3 3.76 3 9.09v2.91L8 17h7z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-300">No notifications</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You're all caught up!</p>
        </div>

        <template x-for="notification in notifications" :key="notification.id">
            <x-admin.notification.notification-list-item />
        </template>
    </div>

    <!-- Pagination -->
    <div x-show="!loading && notifications.length > 0" class="p-6 border-t border-gray-200 dark:border-slate-700">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Showing <span x-text="notifications.length"></span> of <span x-text="pagination.total"></span>
                notifications
            </div>
            <button x-show="pagination.has_more" @click="loadMore()"
                class="px-4 py-2 text-sm bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors">
                Load More
            </button>
        </div>
    </div>
</div>

<script>
    function notificationList() {
        return {
            loading: false,
            notifications: [],
            unreadCount: 0,
            searchTerm: '',
            filter: 'all',
            pagination: {
                current_page: 1,
                has_more: false,
                total: 0
            },

            init() {
                this.refreshNotifications();
                this.getUnreadCount();
                this.setupEchoListeners();
            },

            setupEchoListeners() {
                // Listen for public notifications
                window.Echo.channel('users')
                    .listen('.notification.sent', (e) => {
                        this.handleNewNotification(e);
                    });

                // Listen for private notifications
                const userId = '{{ auth()->id() }}';
                if (userId) {
                    window.Echo.private(`user.${userId}`)
                        .listen('.notification.sent', (e) => {
                            this.handleNewNotification(e);
                        });
                }
            },

            handleNewNotification(data) {
                // Create new notification object
                const newNotification = {
                    id: Date.now(), // Temporary ID
                    title: data.title,
                    message: data.message,
                    icon: 'envelope',
                    url: null,
                    is_read: false,
                    created_at: 'just now',
                    created_at_formatted: new Date().toLocaleString()
                };

                // Add to top of notifications array
                this.notifications.unshift(newNotification);
                this.unreadCount++;

                // Refresh from server to get actual data
                setTimeout(() => {
                    this.refreshNotifications();
                }, 1000);
            },

            async refreshNotifications() {
                this.loading = true;
                this.pagination.current_page = 1;

                try {
                    const response = await axios.get('/admin/notifications', {
                        params: {
                            search: this.searchTerm,
                            filter: this.filter,
                            per_page: 20
                        }
                    });

                    if (response.data.success) {
                        this.notifications = response.data.notifications;
                        this.pagination = response.data.pagination;
                    }
                } catch (error) {
                    console.error('Error fetching notifications:', error);
                } finally {
                    this.loading = false;
                }
            },

            async loadMore() {
                if (this.loading || !this.pagination.has_more) return;

                this.loading = true;

                try {
                    const nextPage = this.pagination.current_page + 1;
                    const response = await axios.get('/admin/notifications', {
                        params: {
                            search: this.searchTerm,
                            filter: this.filter,
                            per_page: 20,
                            page: nextPage
                        }
                    });

                    if (response.data.success) {
                        this.notifications = [...this.notifications, ...response.data.notifications];
                        this.pagination = response.data.pagination;
                    }
                } catch (error) {
                    console.error('Error loading more notifications:', error);
                } finally {
                    this.loading = false;
                }
            },

            setFilter(newFilter) {
                this.filter = newFilter;
                this.refreshNotifications();
            },

            async getUnreadCount() {
                try {
                    const response = await axios.get('/admin/notifications/unread-count');
                    this.unreadCount = response.data.unread_count;
                } catch (error) {
                    console.error('Error fetching unread count:', error);
                }
            },

            handleNotificationClick(notification) {
                if (notification.url) {
                    window.location.href = notification.url;
                }

                if (!notification.is_read) {
                    this.markAsRead(notification);
                }
            },

            async toggleReadStatus(notification) {
                try {
                    const endpoint = notification.is_read ? 'unread' : 'read';
                    await axios.post(`/admin/notifications/${notification.id}/mark-as-${endpoint}`);

                    notification.is_read = !notification.is_read;
                    this.getUnreadCount();
                } catch (error) {
                    console.error('Error toggling read status:', error);
                }
            },

            async markAsRead(notification) {
                if (notification.is_read) return;

                try {
                    await axios.post(`/admin/notifications/${notification.id}/mark-as-read`);

                    notification.is_read = true;
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                } catch (error) {
                    console.error('Error marking as read:', error);
                }
            },

            async markAllAsRead() {
                try {
                    await axios.post('/admin/notifications/mark-all-read');

                    this.notifications.forEach(notification => {
                        notification.is_read = true;
                    });
                    this.unreadCount = 0;
                } catch (error) {
                    console.error('Error marking all as read:', error);
                }
            },

            async deleteNotification(notification) {
                try {
                    await axios.delete(`/admin/notifications/${notification.id}`);

                    const index = this.notifications.findIndex(n => n.id === notification.id);
                    if (index > -1) {
                        this.notifications.splice(index, 1);
                        if (!notification.is_read) {
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                        }
                    }
                } catch (error) {
                    console.error('Error deleting notification:', error);
                }
            }
        }
    }
</script>
