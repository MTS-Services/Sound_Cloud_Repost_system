<x-admin::layout>
    <x-slot name="title">All Notifications</x-slot>
    <x-slot name="breadcrumb">Notifications</x-slot>
    <x-slot name="page_slug">notifications</x-slot>
    <section>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 dark:from-gray-900 dark:to-slate-800 p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="glass-card rounded-2xl p-6 mb-8">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-black dark:text-white mb-2">
                                <i data-lucide="bell" class="w-8 h-8 inline-block mr-3 text-orange-500"></i>
                                Notifications
                            </h1>
                            <p class="text-gray-600 dark:text-gray-300">
                                Manage all your notifications in one place
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Unread Count Badge -->
                            <div class="px-4 py-2 bg-orange-500/20 rounded-xl">
                                <span class="text-orange-600 dark:text-orange-400 font-medium text-sm">
                                    <span id="all-notifications-unread-count">{{ $unreadCount }}</span>
                                    unread
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <button id="mark-all-read-btn"
                                class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-600 dark:text-blue-400 rounded-xl transition-colors duration-200 font-medium text-sm">
                                <i data-lucide="check-circle" class="w-4 h-4 inline mr-2"></i>
                                Mark All Read
                            </button>

                            <button id="refresh-notifications-btn"
                                class="px-4 py-2 bg-green-500/20 hover:bg-green-500/30 text-green-600 dark:text-green-400 rounded-xl transition-colors duration-200 font-medium text-sm">
                                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="glass-card rounded-2xl p-4 mb-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-3">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter:</label>
                            <select id="notification-filter"
                                class="pl-3 pr-10 py-2 bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="all">All Notifications</option>
                                <option value="unread">Unread Only</option>
                                <option value="read">Read Only</option>
                                <option value="private">Private Messages</option>
                                <option value="public">Public Announcements</option>
                            </select>
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Total: <span id="total-notifications-count">{{ $notifications->total() }}</span>
                            notifications
                        </div>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="glass-card rounded-2xl overflow-hidden">
                    <div id="all-notifications-container" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($notifications as $notification)
                            <div class="all-notification-item {{ $notification->statuses->isEmpty() ? 'unread' : 'read' }}"
                                data-notification-id="{{ $notification->id }}"
                                data-notification-type="{{ $notification->receiver_id ? 'private' : 'public' }}">
                                <div class="p-6 hover:bg-white/30 dark:hover:bg-gray-800/30 transition-colors duration-200 cursor-pointer"
                                    onclick="handleNotificationClick({{ $notification->id }}, '{{ $notification->url ?? '#' }}')">
                                    <div class="flex items-start gap-4">
                                        <!-- Icon & Status Indicator -->
                                        <div class="relative flex-shrink-0">
                                            <div
                                                class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 shadow-lg">
                                                <i data-lucide="{{ $notification->message_data['icon'] ?? 'bell' }}"
                                                    class="w-6 h-6 text-white"></i>
                                            </div>
                                            @if ($notification->statuses->isEmpty())
                                                <span
                                                    class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping"></span>
                                                <span
                                                    class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full"></span>
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex-1">
                                                    <h3
                                                        class="text-lg font-semibold text-black dark:text-white mb-1 line-clamp-1">
                                                        {{ $notification->message_data['title'] ?? 'New Notification' }}
                                                    </h3>
                                                    <p
                                                        class="text-gray-600 dark:text-gray-300 text-sm mb-2 line-clamp-2">
                                                        {{ $notification->message_data['message'] ?? '' }}
                                                    </p>
                                                    @if (!empty($notification->message_data['description']))
                                                        <p
                                                            class="text-gray-500 dark:text-gray-400 text-xs mb-3 line-clamp-3">
                                                            {{ $notification->message_data['description'] }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- Action Menu -->
                                                <div class="flex items-center gap-2">
                                                    @if ($notification->statuses->isEmpty())
                                                        <button
                                                            onclick="event.stopPropagation(); markSingleAsRead({{ $notification->id }})"
                                                            class="p-2 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-lg transition-colors duration-200"
                                                            title="Mark as read">
                                                            <i data-lucide="check" class="w-4 h-4"></i>
                                                        </button>
                                                    @endif

                                                    @if ($notification->receiver_id === admin()->id)
                                                        <button
                                                            onclick="event.stopPropagation(); deleteNotification({{ $notification->id }})"
                                                            class="p-2 hover:bg-red-500/20 text-red-600 dark:text-red-400 rounded-lg transition-colors duration-200"
                                                            title="Delete notification">
                                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Meta Information -->
                                            <div
                                                class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mt-3">
                                                <div class="flex items-center gap-4">
                                                    <span class="flex items-center gap-1">
                                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <i data-lucide="{{ $notification->receiver_id ? 'lock' : 'users' }}"
                                                            class="w-3 h-3"></i>
                                                        {{ $notification->receiver_id ? 'Private' : 'Public' }}
                                                    </span>
                                                </div>
                                                @if (!$notification->statuses->isEmpty())
                                                    <span class="text-green-600 dark:text-green-400 font-medium">
                                                        <i data-lucide="check-circle" class="w-3 h-3 inline mr-1"></i>
                                                        Read
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <div
                                    class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                    <i data-lucide="bell-off" class="w-12 h-12 text-gray-400"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300 mb-2">No notifications
                                    yet
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400">When you receive notifications, they'll
                                    appear here.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if ($notifications->hasPages())
                    <div class="mt-8 glass-card rounded-2xl p-6">
                        <x-admin.custom-pagination :paginator="$notifications" :filterType="$filterType" :showInfo="true"
                            :showSizeSelector="true" />
                    </div>
                @endif
            </div>

            <!-- Loading Overlay -->
            <div id="loading-overlay"
                class="hidden fixed inset-0 bg-black/20 backdrop-blur-sm z-50 flex items-center justify-center">
                <div class="glass-card rounded-2xl p-6 flex items-center gap-3">
                    <div class="w-6 h-6 border-2 border-orange-500 border-t-transparent rounded-full animate-spin">
                    </div>
                    <span class="text-black dark:text-white font-medium">Loading...</span>
                </div>
            </div>
        </div>


    </section>

    @push('css')
        <style>
            .line-clamp-1 {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .all-notification-item.read {
                opacity: 0.75;
            }

            .all-notification-item.unread {
                border-left: 4px solid #f97316;
            }
        </style>
    @endpush

    @push('js')
        <script>
            // All Notifications Page Manager
            class AllNotificationsManager {
                constructor() {
                    this.baseUrl = '{{ route('admin.notifications.index') }}';
                    this.currentFilter = 'all';
                    this.init();
                }

                init() {
                    this.setupEventListeners();
                    this.initializeLucideIcons();
                    this.setupEchoListeners();
                }

                setupEventListeners() {
                    // Filter change
                    document.getElementById('notification-filter')?.addEventListener('change', (e) => {
                        this.currentFilter = e.target.value;
                        this.applyFilter();
                    });

                    // Mark all as read
                    document.getElementById('mark-all-read-btn')?.addEventListener('click', () => {
                        this.markAllAsRead();
                    });

                    // Refresh notifications
                    document.getElementById('refresh-notifications-btn')?.addEventListener('click', () => {
                        this.refreshNotifications();
                    });
                }

                setupEchoListeners() {
                    // Listen for new notifications and refresh the page data
                    if (window.Echo) {
                        window.Echo.channel('admins')
                            .listen('.notification.sent', (e) => {
                                this.handleNewNotification(e);
                            });

                        window.Echo.private(`admin.{{ auth()->id() }}`)
                            .listen('.notification.sent', (e) => {
                                this.handleNewNotification(e);
                            });
                    }
                }

                handleNewNotification(data) {
                    // Update unread count
                    this.updateUnreadCount();

                    // Show toast notification
                    this.showToast('New notification received', 'info');

                    // Optionally refresh the list if user is viewing 'all' or 'unread'
                    if (this.currentFilter === 'all' || this.currentFilter === 'unread') {
                        setTimeout(() => this.refreshNotifications(), 1000);
                    }
                }

                applyFilter() {
                    const items = document.querySelectorAll('.all-notification-item');
                    let visibleCount = 0;

                    items.forEach(item => {
                        let show = false;
                        const isRead = item.classList.contains('read');
                        const isPrivate = item.dataset.notificationType === 'private';

                        switch (this.currentFilter) {
                            case 'all':
                                show = true;
                                break;
                            case 'unread':
                                show = !isRead;
                                break;
                            case 'read':
                                show = isRead;
                                break;
                            case 'private':
                                show = isPrivate;
                                break;
                            case 'public':
                                show = !isPrivate;
                                break;
                        }

                        if (show) {
                            item.style.display = '';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Update visible count
                    document.getElementById('total-notifications-count').textContent = visibleCount;
                }

                async markAllAsRead() {
                    try {
                        this.showLoading(true);

                        const response = await fetch('{{ route('admin.notifications.mark-all-read') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Update UI
                            document.querySelectorAll('.all-notification-item.unread').forEach(item => {
                                item.classList.remove('unread');
                                item.classList.add('read');

                                // Remove unread indicators
                                const indicators = item.querySelectorAll('.animate-ping, .bg-red-500');
                                indicators.forEach(indicator => indicator.remove());

                                // Add read status
                                const metaDiv = item.querySelector('.flex.items-center.justify-between.text-xs');
                                if (metaDiv && !metaDiv.querySelector('.text-green-600')) {
                                    const readStatus = document.createElement('span');
                                    readStatus.className = 'text-green-600 dark:text-green-400 font-medium';
                                    readStatus.innerHTML =
                                        '<i data-lucide="check-circle" class="w-3 h-3 inline mr-1"></i>Read';
                                    metaDiv.appendChild(readStatus);
                                }
                            });

                            this.updateUnreadCount(0);
                            this.showToast('All notifications marked as read', 'success');
                        } else {
                            this.showToast('Failed to mark notifications as read', 'error');
                        }
                    } catch (error) {
                        console.error('Error marking all as read:', error);
                        this.showToast('An error occurred', 'error');
                    } finally {
                        this.showLoading(false);
                        this.initializeLucideIcons();
                    }
                }

                async refreshNotifications() {
                    try {
                        this.showLoading(true);
                        window.location.reload();
                    } catch (error) {
                        console.error('Error refreshing notifications:', error);
                        this.showToast('Failed to refresh notifications', 'error');
                        this.showLoading(false);
                    }
                }

                updateUnreadCount(count = null) {
                    if (count === null) {
                        // Fetch from server
                        fetch('{{ route('admin.notifications.unread-count') }}')
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('all-notifications-unread-count').textContent = data.count;
                                }
                            })
                            .catch(error => console.error('Error fetching unread count:', error));
                    } else {
                        document.getElementById('all-notifications-unread-count').textContent = count;
                    }
                }

                showLoading(show) {
                    const overlay = document.getElementById('loading-overlay');
                    if (show) {
                        overlay.classList.remove('hidden');
                    } else {
                        overlay.classList.add('hidden');
                    }
                }

                showToast(message, type = 'info') {
                    // Use existing toast or create simple alert
                    if (window.notificationManager && window.notificationManager.showToast) {
                        window.notificationManager.showToast(message);
                    } else {
                        // Fallback to simple notification
                        const toast = document.createElement('div');
                        toast.className = `fixed top-5 right-5 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                    type === 'success' ? 'bg-green-500 text-white' :
                    type === 'error' ? 'bg-red-500 text-white' :
                    'bg-blue-500 text-white'
                }`;
                        toast.textContent = message;
                        document.body.appendChild(toast);

                        setTimeout(() => {
                            toast.style.opacity = '0';
                            toast.style.transform = 'translateX(100%)';
                            setTimeout(() => toast.remove(), 300);
                        }, 3000);
                    }
                }

                initializeLucideIcons() {
                    if (typeof lucide !== 'undefined' && lucide.createIcons) {
                        lucide.createIcons();
                    }
                }
            }

            // Global functions for inline onclick handlers
            async function handleNotificationClick(notificationId, url) {
                try {
                    // Mark as read first
                    await fetch('{{ route('admin.notifications.mark-as-read') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            notification_id: notificationId
                        })
                    });

                    // Then navigate if URL exists
                    if (url && url !== '#') {
                        window.location.href = url;
                    }
                } catch (error) {
                    console.error('Error handling notification click:', error);
                }
            }

            async function markSingleAsRead(notificationId) {
                try {
                    const response = await fetch('{{ route('admin.notifications.mark-as-read') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            notification_id: notificationId
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
                        if (item) {
                            item.classList.remove('unread');
                            item.classList.add('read');

                            // Remove unread indicators
                            const indicators = item.querySelectorAll('.animate-ping, .bg-red-500');
                            indicators.forEach(indicator => indicator.remove());
                        }

                        // Update unread count
                        window.allNotificationsManager.updateUnreadCount();
                        window.allNotificationsManager.showToast('Notification marked as read', 'success');
                    }
                } catch (error) {
                    console.error('Error marking as read:', error);
                }
            }

            async function deleteNotification(notificationId) {
                if (!confirm('Are you sure you want to delete this notification?')) {
                    return;
                }

                try {
                    const response = await fetch('{{ route('admin.notifications.destroy') }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            notification_id: notificationId
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
                        if (item) {
                            item.style.transition = 'all 0.3s ease';
                            item.style.opacity = '0';
                            item.style.transform = 'translateX(-20px)';
                            setTimeout(() => item.remove(), 300);
                        }

                        window.allNotificationsManager.showToast('Notification deleted', 'success');
                    } else {
                        window.allNotificationsManager.showToast(data.message || 'Failed to delete notification', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting notification:', error);
                    window.allNotificationsManager.showToast('An error occurred', 'error');
                }
            }

            // Initialize the manager
            document.addEventListener('DOMContentLoaded', function() {
                window.allNotificationsManager = new AllNotificationsManager();
            });
        </script>
    @endpush

</x-admin::layout>
