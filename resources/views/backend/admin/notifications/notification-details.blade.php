<x-admin::layout>
    <x-slot name="title">Notification Details</x-slot>
    <x-slot name="breadcrumb">Notification</x-slot>
    <x-slot name="page_slug">notification-details</x-slot>

    <section>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 dark:from-gray-900 dark:to-slate-800 p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Loading State -->
                <div id="loading-state" class="glass-card rounded-2xl p-8 text-center">
                    <div
                        class="w-16 h-16 border-4 border-orange-500 border-t-transparent rounded-full animate-spin mx-auto mb-4">
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">Loading notification details...</p>
                </div>

                <!-- Error State -->
                <div id="error-state" class="glass-card rounded-2xl p-8 text-center hidden">
                    <div
                        class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="alert-circle" class="w-8 h-8 text-red-500"></i>
                    </div>
                    <h2 class="text-xl font-bold text-black dark:text-white mb-2">Notification Not Found</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">The notification you're looking for doesn't exist
                        or
                        has been removed.</p>
                    <div class="flex gap-3 justify-center">
                        <a href="{{ route('admin.notifications.index') }}"
                            class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                            View All Notifications
                        </a>
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Content State -->
                <div id="content-state" class="hidden">
                    <!-- Header -->
                    <div class="glass-card rounded-2xl p-6 mb-8">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('admin.notifications.index') }}"
                                    class="p-2 hover:bg-orange-500/20 text-orange-600 dark:text-orange-400 rounded-lg transition-colors">
                                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                                </a>
                                <div>
                                    <h1 class="text-2xl font-bold text-black dark:text-white">Notification Details</h1>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm" id="notification-meta"></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <button id="mark-as-read-btn"
                                    class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-600 dark:text-blue-400 rounded-lg transition-colors font-medium text-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4 inline mr-2"></i>
                                    Mark as Read
                                </button>

                                <button id="delete-notification-btn"
                                    class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-600 dark:text-red-400 rounded-lg transition-colors font-medium text-sm">
                                    <i data-lucide="trash-2" class="w-4 h-4 inline mr-2"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Content -->
                    <div class="glass-card rounded-2xl overflow-hidden">
                        <div class="p-8">
                            <!-- Icon and Status -->
                            <div class="flex items-start gap-6 mb-8">
                                <div class="relative flex-shrink-0">
                                    <div id="notification-icon-container"
                                        class="w-16 h-16 rounded-2xl flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 shadow-lg">
                                        <i id="notification-icon" data-lucide="bell" class="w-8 h-8 text-white"></i>
                                    </div>
                                    <span id="unread-indicator"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full hidden">
                                        <span class="absolute inset-0 bg-red-500 rounded-full animate-ping"></span>
                                    </span>
                                </div>

                                <div class="flex-1">
                                    <h2 id="notification-title"
                                        class="text-3xl font-bold text-black dark:text-white mb-3">
                                    </h2>
                                    <div class="flex items-center gap-6 text-sm text-gray-500 dark:text-gray-400 mb-6">
                                        <span id="notification-date" class="flex items-center gap-2">
                                            <i data-lucide="clock" class="w-4 h-4"></i>
                                            <span></span>
                                        </span>
                                        <span id="notification-type" class="flex items-center gap-2">
                                            <i data-lucide="users" class="w-4 h-4"></i>
                                            <span></span>
                                        </span>
                                        <span id="notification-status" class="flex items-center gap-2 font-medium">
                                            <i data-lucide="circle" class="w-4 h-4"></i>
                                            <span></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-black dark:text-white mb-3">Message</h3>
                                    <div id="notification-message"
                                        class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 text-gray-700 dark:text-gray-300 leading-relaxed">
                                    </div>
                                </div>

                                <div id="notification-description-section" class="hidden">
                                    <h3 class="text-lg font-semibold text-black dark:text-white mb-3">Description</h3>
                                    <div id="notification-description"
                                        class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 text-gray-700 dark:text-gray-300 leading-relaxed">
                                    </div>
                                </div>

                                <div id="notification-url-section" class="hidden">
                                    <h3 class="text-lg font-semibold text-black dark:text-white mb-3">Related Link</h3>
                                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6">
                                        <a id="notification-url" href="#"
                                            class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                            <span>View Related Content</span>
                                        </a>
                                    </div>
                                </div>

                                <div id="additional-data-section" class="hidden">
                                    <h3 class="text-lg font-semibold text-black dark:text-white mb-3">Additional
                                        Information
                                    </h3>
                                    <div id="additional-data" class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Footer -->
                        <div
                            class="bg-gray-50 dark:bg-gray-800/30 px-8 py-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Notification ID: <span id="notification-display-id" class="font-mono"></span>
                                </div>
                                <div class="flex gap-3">
                                    <button onclick="window.history.back()"
                                        class="px-4 py-2 bg-gray-500/20 hover:bg-gray-500/30 text-gray-600 dark:text-gray-400 rounded-lg transition-colors">
                                        Go Back
                                    </button>
                                    <a href="{{ route('admin.notifications.index') }}"
                                        class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                                        All Notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Toast -->
        <div id="action-toast"
            class="fixed top-5 right-5 z-50 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 transform translate-x-full opacity-0 transition-all duration-300">
            <div class="p-4 flex items-center gap-3">
                <div id="toast-icon" class="w-8 h-8 rounded-full flex items-center justify-center">
                    <i data-lucide="check" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <p id="toast-message" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                </div>
                <button id="toast-close" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </section>

    @push('css')
        <style>
            .notification-content img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                margin: 1rem 0;
            }

            .notification-content pre {
                background: #f3f4f6;
                border-radius: 8px;
                padding: 1rem;
                overflow-x: auto;
                font-size: 0.875rem;
            }

            .dark .notification-content pre {
                background: #374151;
            }
        </style>
    @endpush

    @push('js')
        <script>
            // Notification Details Manager
            class NotificationDetailsManager {
                constructor() {
                    this.notificationId = null;
                    this.notification = null;
                    this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    this.init();
                }

                init() {
                    // Extract notification ID from URL
                    this.extractNotificationId();

                    if (this.notificationId) {
                        this.loadNotification();
                    } else {
                        this.showError();
                    }

                    this.setupEventListeners();
                }

                extractNotificationId() {
                    // Extract from URL pattern: /notifications/details/{encrypted_id}
                    const pathParts = window.location.pathname.split('/');
                    const detailsIndex = pathParts.indexOf('details');

                    if (detailsIndex !== -1 && pathParts[detailsIndex + 1]) {
                        this.notificationId = pathParts[detailsIndex + 1];
                    }
                }

                async loadNotification() {
                    try {
                        this.showLoading();

                        const response = await axios.get(
                            `{{ route('admin.notifications.details', '') }}/${this.notificationId}`);

                        if (response.data.success) {
                            this.notification = response.data.notification;

                            // Check if notification has URL and should redirect
                            if (this.notification.url && this.notification.url !== '#' && this.notification.url !== null) {
                                // Mark as read first, then redirect
                                await this.markAsReadSilently();
                                window.location.href = this.notification.url;
                                return;
                            }

                            // Render notification details
                            this.renderNotification();
                            this.showContent();
                        } else {
                            this.showError();
                        }
                    } catch (error) {
                        console.error('Error loading notification:', error);
                        this.showError();
                    }
                }

                renderNotification() {
                    const data = this.notification.message_data;
                    const isRead = this.notification.is_read;

                    // Set title and meta
                    document.getElementById('notification-title').textContent = data.title || 'Notification';
                    document.getElementById('notification-meta').textContent = 'Notification Details';

                    // Set icon
                    const icon = data.icon || 'bell';
                    document.getElementById('notification-icon').setAttribute('data-lucide', icon);

                    // Set date and type
                    document.getElementById('notification-date').querySelector('span').textContent =
                        new Date(this.notification.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                    document.getElementById('notification-type').querySelector('span').textContent =
                        this.notification.receiver_id ? 'Private Message' : 'Public Announcement';

                    // Set status
                    const statusElement = document.getElementById('notification-status');
                    const statusIcon = statusElement.querySelector('i');
                    const statusText = statusElement.querySelector('span');

                    if (isRead) {
                        statusIcon.setAttribute('data-lucide', 'check-circle');
                        statusIcon.className = 'w-4 h-4 text-green-500';
                        statusText.textContent = 'Read';
                        statusText.className = 'text-green-500';
                        document.getElementById('mark-as-read-btn').style.display = 'none';
                    } else {
                        statusIcon.setAttribute('data-lucide', 'circle');
                        statusIcon.className = 'w-4 h-4 text-orange-500';
                        statusText.textContent = 'Unread';
                        statusText.className = 'text-orange-500';
                        document.getElementById('unread-indicator').classList.remove('hidden');
                    }

                    // Set message
                    document.getElementById('notification-message').textContent = data.message || 'No message content';

                    // Set description if exists
                    if (data.description) {
                        document.getElementById('notification-description').textContent = data.description;
                        document.getElementById('notification-description-section').classList.remove('hidden');
                    }

                    // Set URL if exists (for display purposes, not redirect)
                    if (data.url && data.url !== '#') {
                        document.getElementById('notification-url').href = data.url;
                        document.getElementById('notification-url-section').classList.remove('hidden');
                    }

                    // Set additional data if exists
                    if (data.additional_data) {
                        const additionalDataElement = document.getElementById('additional-data');
                        additionalDataElement.innerHTML = this.formatAdditionalData(data.additional_data);
                        document.getElementById('additional-data-section').classList.remove('hidden');
                    }

                    // Set display ID
                    document.getElementById('notification-display-id').textContent = this.notification.id;

                    // Hide delete button for public notifications
                    if (!this.notification.receiver_id) {
                        document.getElementById('delete-notification-btn').style.display = 'none';
                    }

                    // Reinitialize Lucide icons
                    this.initializeLucideIcons();
                }

                formatAdditionalData(data) {
                    if (typeof data === 'string') {
                        try {
                            data = JSON.parse(data);
                        } catch (e) {
                            return `<pre class="text-sm">${this.escapeHtml(data)}</pre>`;
                        }
                    }

                    if (typeof data === 'object' && data !== null) {
                        let html = '<div class="space-y-2">';
                        for (const [key, value] of Object.entries(data)) {
                            html += `
                        <div class="flex">
                            <span class="font-medium text-gray-600 dark:text-gray-400 w-24 flex-shrink-0">${this.escapeHtml(key)}:</span>
                            <span class="text-gray-900 dark:text-white">${this.escapeHtml(String(value))}</span>
                        </div>
                    `;
                        }
                        html += '</div>';
                        return html;
                    }

                    return `<span class="text-gray-900 dark:text-white">${this.escapeHtml(String(data))}</span>`;
                }

                setupEventListeners() {
                    // Mark as read button
                    document.getElementById('mark-as-read-btn')?.addEventListener('click', () => {
                        this.markAsRead();
                    });

                    // Delete button
                    document.getElementById('delete-notification-btn')?.addEventListener('click', () => {
                        this.deleteNotification();
                    });

                    // Toast close button
                    document.getElementById('toast-close')?.addEventListener('click', () => {
                        this.hideToast();
                    });
                }

                async markAsRead() {
                    try {
                        const response = await axios.post('{{ route('admin.notifications.mark-as-read') }}', {
                            notification_id: this.notification.id
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });

                        if (response.data.success) {
                            // Update UI
                            const statusElement = document.getElementById('notification-status');
                            const statusIcon = statusElement.querySelector('i');
                            const statusText = statusElement.querySelector('span');

                            statusIcon.setAttribute('data-lucide', 'check-circle');
                            statusIcon.className = 'w-4 h-4 text-green-500';
                            statusText.textContent = 'Read';
                            statusText.className = 'text-green-500';

                            document.getElementById('mark-as-read-btn').style.display = 'none';
                            document.getElementById('unread-indicator').classList.add('hidden');

                            this.notification.is_read = true;
                            this.initializeLucideIcons();
                            this.showToast('Notification marked as read', 'success');
                        }
                    } catch (error) {
                        console.error('Error marking as read:', error);
                        this.showToast('Failed to mark as read', 'error');
                    }
                }

                async markAsReadSilently() {
                    try {
                        await axios.post('{{ route('admin.notifications.mark-as-read') }}', {
                            notification_id: this.notification.id
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });
                    } catch (error) {
                        console.error('Error marking as read silently:', error);
                    }
                }

                async deleteNotification() {
                    if (!confirm('Are you sure you want to delete this notification? This action cannot be undone.')) {
                        return;
                    }

                    try {
                        const response = await axios.delete('{{ route('admin.notifications.destroy') }}', {
                            data: {
                                notification_id: this.notification.id
                            },
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken
                            }
                        });

                        if (response.data.success) {
                            this.showToast('Notification deleted successfully', 'success');

                            // Redirect after 2 seconds
                            setTimeout(() => {
                                window.location.href = '{{ route('admin.notifications.index') }}';
                            }, 2000);
                        } else {
                            this.showToast(response.data.message || 'Failed to delete notification', 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting notification:', error);
                        if (error.response?.status === 403) {
                            this.showToast('You can only delete your private notifications', 'error');
                        } else {
                            this.showToast('Failed to delete notification', 'error');
                        }
                    }
                }

                showLoading() {
                    document.getElementById('loading-state').classList.remove('hidden');
                    document.getElementById('error-state').classList.add('hidden');
                    document.getElementById('content-state').classList.add('hidden');
                }

                showError() {
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('error-state').classList.remove('hidden');
                    document.getElementById('content-state').classList.add('hidden');
                }

                showContent() {
                    document.getElementById('loading-state').classList.add('hidden');
                    document.getElementById('error-state').classList.add('hidden');
                    document.getElementById('content-state').classList.remove('hidden');
                }

                showToast(message, type = 'info') {
                    const toast = document.getElementById('action-toast');
                    const icon = document.getElementById('toast-icon');
                    const messageEl = document.getElementById('toast-message');

                    messageEl.textContent = message;

                    // Set icon and colors based on type
                    if (type === 'success') {
                        icon.className =
                            'w-8 h-8 rounded-full flex items-center justify-center bg-green-100 dark:bg-green-900/30';
                        icon.querySelector('i').setAttribute('data-lucide', 'check');
                        icon.querySelector('i').className = 'w-5 h-5 text-green-600 dark:text-green-400';
                    } else if (type === 'error') {
                        icon.className =
                            'w-8 h-8 rounded-full flex items-center justify-center bg-red-100 dark:bg-red-900/30';
                        icon.querySelector('i').setAttribute('data-lucide', 'x');
                        icon.querySelector('i').className = 'w-5 h-5 text-red-600 dark:text-red-400';
                    } else {
                        icon.className =
                            'w-8 h-8 rounded-full flex items-center justify-center bg-blue-100 dark:bg-blue-900/30';
                        icon.querySelector('i').setAttribute('data-lucide', 'info');
                        icon.querySelector('i').className = 'w-5 h-5 text-blue-600 dark:text-blue-400';
                    }

                    // Show toast
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');

                    // Auto hide after 5 seconds
                    setTimeout(() => {
                        this.hideToast();
                    }, 5000);

                    this.initializeLucideIcons();
                }

                hideToast() {
                    const toast = document.getElementById('action-toast');
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                }

                initializeLucideIcons() {
                    if (typeof lucide !== 'undefined' && lucide.createIcons) {
                        lucide.createIcons();
                    }
                }

                escapeHtml(text) {
                    const map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };
                    return text ? text.toString().replace(/[&<>"']/g, (m) => map[m]) : '';
                }
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                new NotificationDetailsManager();
            });
        </script>
    @endpush
</x-admin::layout>
