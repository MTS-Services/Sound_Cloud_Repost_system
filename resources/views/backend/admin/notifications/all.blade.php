<x-admin::layout>
    <x-slot name="title">All Notifications</x-slot>
    <x-slot name="breadcrumb">Notifications</x-slot>
    <x-slot name="page_slug">notifications</x-slot>

    <section>

        <div class="max-w-4xl mx-auto">
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
                        <div class="px-4 py-2 bg-orange-500/20 rounded-xl">
                            <span class="text-orange-600 dark:text-orange-400 font-medium text-sm">
                                <span id="all-notifications-unread-count">{{ $unreadCount }}</span>
                                unread
                            </span>
                        </div>

                        <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-600 dark:text-blue-400 rounded-xl transition-colors duration-200 font-medium text-sm">
                                <i data-lucide="check-circle" class="w-4 h-4 inline mr-2"></i>
                                Mark All Read
                            </button>
                        </form>

                        <a href="{{ route('admin.notifications.index') }}"
                            class="px-4 py-2 bg-green-500/20 hover:bg-green-500/30 text-green-600 dark:text-green-400 rounded-xl transition-colors duration-200 font-medium text-sm">
                            <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                            Refresh
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="glass-card rounded-2xl p-4 mb-6">
                <form method="GET" action="{{ route('admin.notifications.index') }}"
                    class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter:</label>
                        <select name="filter" onchange="this.form.submit()"
                            class="px-3 py-2 bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="all" {{ $filterType === 'all' ? 'selected' : '' }}>All Notifications
                            </option>
                            <option value="unread" {{ $filterType === 'unread' ? 'selected' : '' }}>Unread Only
                            </option>
                            <option value="read" {{ $filterType === 'read' ? 'selected' : '' }}>Read Only</option>
                            <option value="private" {{ $filterType === 'private' ? 'selected' : '' }}>Private
                                Messages</option>
                            <option value="public" {{ $filterType === 'public' ? 'selected' : '' }}>Public
                                Announcements</option>
                        </select>

                        <!-- Per Page Selector -->
                        <select name="per_page" onchange="this.form.submit()"
                            class="pl-3 pr-8 py-2 bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10
                            </option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20
                            </option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30
                            </option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                            </option>
                        </select>
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }}
                        of {{ $notifications->total() }} notifications
                        @if ($filterType !== 'all')
                            <span
                                class="ml-2 px-2 py-1 bg-orange-500/20 text-orange-600 dark:text-orange-400 rounded text-xs font-medium">
                                {{ ucfirst($filterType) }} Filter Active
                            </span>
                        @endif
                    </div>
                </form>
            </div>

            <div class="glass-card rounded-2xl overflow-hidden">
                <div id="all-notifications-container" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($notifications as $notification)
                        <div class="all-notification-item {{ $notification->statuses->isEmpty() ? 'unread' : 'read' }}"
                            data-notification-id="{{ $notification->id }}"
                            data-notification-type="{{ $notification->receiver_id ? 'private' : 'public' }}">

                            <a href="{{ route('admin.notifications.show', $notification->id) }}"
                                class="block p-6 hover:bg-white/30 dark:hover:bg-gray-800/30 transition-colors duration-200">
                                <div class="flex items-start gap-4">
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

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <h3
                                                    class="text-lg font-semibold text-black dark:text-white mb-1 line-clamp-1">
                                                    {{ $notification->message_data['title'] ?? 'New Notification' }}
                                                </h3>
                                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-2 line-clamp-2">
                                                    {{ $notification->message_data['message'] ?? '' }}
                                                </p>
                                                @if (!empty($notification->message_data['description']))
                                                    <p
                                                        class="text-gray-500 dark:text-gray-400 text-xs mb-3 line-clamp-3">
                                                        {{ $notification->message_data['description'] }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2">
                                                @if ($notification->statuses->isEmpty())
                                                    <form method="POST"
                                                        action="{{ route('admin.notifications.mark-as-read') }}"
                                                        class="inline">
                                                        @csrf
                                                        <input type="hidden" name="notification_id"
                                                            value="{{ $notification->id }}">
                                                        <button type="submit"
                                                            onclick="event.preventDefault(); event.stopPropagation(); this.form.submit();"
                                                            class="p-2 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-lg transition-colors duration-200"
                                                            title="Mark as read">
                                                            <i data-lucide="check" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($notification->receiver_id === admin()->id)
                                                    <form method="POST"
                                                        action="{{ route('admin.notifications.destroy') }}"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="notification_id"
                                                            value="{{ $notification->id }}">
                                                        <button type="submit"
                                                            onclick="event.preventDefault(); event.stopPropagation(); if(confirm('Are you sure you want to delete this notification?')) this.form.submit();"
                                                            class="p-2 hover:bg-red-500/20 text-red-600 dark:text-red-400 rounded-lg transition-colors duration-200"
                                                            title="Delete notification">
                                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>

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
                            </a>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                <i data-lucide="bell-off" class="w-12 h-12 text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300 mb-2">
                                No notifications
                                @if ($filterType !== 'all')
                                    for {{ $filterType }} filter
                                @else
                                    yet
                                @endif
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                @if ($filterType !== 'all')
                                    Try changing the filter or
                                    <a href="{{ route('admin.notifications.index') }}"
                                        class="text-orange-500 hover:underline">view all notifications</a>
                                @else
                                    When you receive notifications, they'll appear here.
                                @endif
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="mt-8 glass-card rounded-2xl p-6">
                    <x-admin.custom-pagination :paginator="$notifications" :filterType="$filterType" :showInfo="true" />
                </div>
            @endif
        </div>

        <div id="loading-overlay"
            class=" fixed inset-0 bg-black/20 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="glass-card rounded-2xl py-6 flex items-center gap-3 px-10">
                <div class="w-6 h-6 border-2 border-orange-500 border-t-transparent rounded-full animate-spin">
                </div>
                <span class="text-black dark:text-white font-medium">Loading...</span>
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
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Lucide icons
                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    lucide.createIcons();
                }

                // Setup Echo listeners for real-time updates
                if (window.Echo) {
                    window.Echo.channel('admins')
                        .listen('.notification.sent', (e) => {
                            handleNewNotification(e);
                        });

                    window.Echo.private(`admin.{{ auth()->id() }}`)
                        .listen('.notification.sent', (e) => {
                            handleNewNotification(e);
                        });
                }

                // Show flash messages
                @if (session('success'))
                    showToast('{{ session('success') }}', 'success');
                @endif

                @if (session('error'))
                    showToast('{{ session('error') }}', 'error');
                @endif
            });

            function handleNewNotification(data) {
                showToast('New notification received', 'info');

                // Refresh the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }

            function showToast(message, type = 'info') {
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
        </script>

        <script src="{{ asset('assets/js/notification-manager.js') }}"></script>
    @endpush
</x-admin::layout>
