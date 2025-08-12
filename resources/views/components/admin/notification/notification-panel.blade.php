<div x-data="notificationPanel()" @notification-updated.window="refreshNotifications()" class="relative">
    <!-- Notification Bell Button -->
    <button @click="togglePanel()"
        class="relative p-2 rounded-xl hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
        :class="{ 'bg-orange-50 dark:bg-orange-900/20': panelOpen }">
       <i data-lucide="bell" class="w-5 h-5 text-text-light-primary dark:text-text-white"></i>

        <!-- Unread Count Badge -->
        <x-admin.notification.notification-counter />
    </button>

    <!-- Notification Panel -->
    <div x-show="panelOpen" @click.away="panelOpen = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 md:w-96 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-gray-200 dark:border-slate-700 z-50"
        x-cloak>

        <!-- Panel Header -->
        <div class="p-4 border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Notifications
                    <span x-show="unreadCount > 0" x-text="'(' + unreadCount + ')'"
                        class="text-sm text-gray-500 dark:text-gray-400 ml-1">
                    </span>
                </h3>
                <div class="flex items-center space-x-2">
                    <button @click="markAllAsRead()" x-show="unreadCount > 0"
                        class="text-sm text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300">
                        Mark all read
                    </button>
                    <button @click="panelOpen = false"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search and Filter -->
            <x-admin.notification.notification-search />
        </div>

        <!-- Notifications List -->
        <div class="max-h-80 overflow-y-auto">
            <div x-show="loading" class="p-4 text-center">
                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-orange-600"></div>
            </div>

            <div x-show="!loading && notifications.length === 0"
                class="p-4 text-center text-gray-500 dark:text-gray-400">
                No notifications found
            </div>

            <template x-for="notification in notifications" :key="notification.id">
                <x-admin.notification.notification-item :compact="true" />
            </template>
        </div>

        <!-- Load More Button -->
        <div x-show="!loading && pagination.has_more" class="p-4 border-t border-gray-200 dark:border-slate-700">
            <button @click="loadMore()"
                class="w-full py-2 px-4 text-sm text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-md transition-colors">
                Load More
            </button>
        </div>
    </div>
</div>

<script>
    function notificationPanel() {
        return {
            panelOpen: false,
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

                // Show toast notification
                this.showToastNotification(data.message || 'New notification received');

                // Refresh from server to get actual data
                setTimeout(() => {
                    this.refreshNotifications();
                }, 1000);
            },

            showToastNotification(message) {
                if (window.showNotification) {
                    window.showNotification(message);
                }
            },

            togglePanel() {
                this.panelOpen = !this.panelOpen;
                if (this.panelOpen && this.notifications.length === 0) {
                    this.refreshNotifications();
                }
            },

            async refreshNotifications() {
                this.loading = true;
                this.pagination.current_page = 1;

                try {
                    const response = await axios.get('/admin/notifications', {
                        params: {
                            search: this.searchTerm,
                            filter: this.filter,
                            per_page: 10
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
                            per_page: 10,
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

                    // Dispatch event for other components
                    window.dispatchEvent(new CustomEvent('unread-count-updated', {
                        detail: {
                            count: this.unreadCount
                        }
                    }));
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

                    // Dispatch event for other components
                    window.dispatchEvent(new CustomEvent('unread-count-updated', {
                        detail: {
                            count: this.unreadCount
                        }
                    }));
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

                    // Dispatch event for other components
                    window.dispatchEvent(new CustomEvent('unread-count-updated', {
                        detail: {
                            count: 0
                        }
                    }));
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

                            // Dispatch event for other components
                            window.dispatchEvent(new CustomEvent('unread-count-updated', {
                                detail: {
                                    count: this.unreadCount
                                }
                            }));
                        }
                    }
                } catch (error) {
                    console.error('Error deleting notification:', error);
                }
            }
        }
    }
</script>
