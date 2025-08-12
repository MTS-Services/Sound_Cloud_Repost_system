<div id="notification-toast"
    class="absolute top-5 right-5 w-72 z-[100] rounded-2xl shadow-2xl bg-white text-black transition-all duration-500 ease-in-out transform translate-x-full opacity-0">
    <div class="p-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 flex-grow">
            <x-heroicon-o-information-circle class="w-6 h-6 text-blue-500 flex-shrink-0" />
            <p id="notification-message" class="text-sm leading-snug font-normal"></p>
        </div>
        <button id="close-notification-btn"
            class="flex-shrink-0 p-1 rounded-full hover:bg-gray-100 transition-colors duration-200">
            <x-heroicon-o-x-mark class="w-5 h-5" />
        </button>
    </div>
</div>

<div x-show="showNotifications" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full" class="hidden fixed right-0 top-0 h-full max-h-screen z-50"
    :class="showNotifications ? '!block' : '!hidden'">

    <div class="w-96 glass-card overflow-y-auto custom-scrollbar my-2 mr-2 rounded-2xl">
        <div class="flex items-center justify-between p-6">
            <h3 class="text-xl font-bold text-black dark:text-text-white">Notifications</h3>
            <button @click="toggleNotifications()"
                class="p-2 rounded-lg bg-orange-500/20 transition-colors inset-shadow-2xs hover:bg-orange-500/10 group cursor-pointer">
                <i data-lucide="x" class="w-5 h-5 text-orange-800 dark:text-orange-100 group-hover:text-orange-500"></i>
            </button>
        </div>

        <div class="space-y-4 h-full max-h-[80vh] overflow-y-auto px-6" id="notification-container">
            @foreach ($notifications as $notification)
                <div class="notification-item">
                    <x-admin.notification-card :notification="$notification" :isRead="$notification->statuses->isEmpty() ? false : true" />
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between p-6 glass-card">
            <x-button href="#" :outline="true" type="secondary">
                {{ __('Mark All As Read') }}
            </x-button>
            <x-button href="" :outline="true">
                {{ __('See All') }}
            </x-button>
        </div>
    </div>

    <script>
        // Make Laravel user data available for Echo channels
        window.Laravel = window.Laravel || {};
        window.Laravel.user = {
            id: {{ auth()->id() ?? 'null' }}
        };

        // Optimized Notification Management System
        class NotificationManager {
            constructor() {
                this.maxNotifications = 15;
                this.notificationContainer = null;
                this.init();
            }

            init() {
                document.addEventListener('DOMContentLoaded', () => {
                    this.notificationContainer = document.getElementById('notification-container');
                    this.setupEchoListeners();
                });
            }

            setupEchoListeners() {
                // Public notifications for all admins
                window.Echo.channel('admins')
                    .listen('.notification.sent', (e) => {
                        console.log('Public notification received:', e);
                        this.handleNewNotification(e, false);
                        this.showToast('New public notification received.');
                    });

                // Private notifications for specific admin
                if (window.Laravel.user.id) {
                    window.Echo.private(`admin.${window.Laravel.user.id}`)
                        .listen('.notification.sent', (e) => {
                            console.log('Private notification received:', e);
                            this.handleNewNotification(e, false);
                            this.showToast('New private notification received.');
                        });
                }
            }

            handleNewNotification(notificationData, isRead = false) {
                if (!this.notificationContainer) {
                    console.error('Notification container not found');
                    return;
                }

                // Create new notification card
                const newNotificationCard = this.createNotificationCard(notificationData, isRead);

                // Add smooth entrance animation
                newNotificationCard.style.opacity = '0';
                newNotificationCard.style.transform = 'translateY(-10px)';

                // Insert at the beginning of the container
                this.notificationContainer.insertBefore(newNotificationCard, this.notificationContainer.firstChild);

                // Animate in
                requestAnimationFrame(() => {
                    newNotificationCard.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    newNotificationCard.style.opacity = '1';
                    newNotificationCard.style.transform = 'translateY(0)';
                });

                // Manage notification count
                this.maintainNotificationLimit();

                // Update notification badge/counter if exists
                this.updateNotificationBadge();

                // Initialize Lucide icons for new content
                this.initializeLucideIcons(newNotificationCard);
            }

            createNotificationCard(data, isRead = false) {
                const cardElement = document.createElement('div');
                cardElement.className = 'notification-item';

                const url = data.url || '#';
                const icon = data.icon || 'bell';
                const title = data.title || 'New Notification';
                const message = data.message || '';
                const timestamp = data.timestamp || 'Just now';

                cardElement.innerHTML = `
                    <a href="${url}" class="p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-orange-500/20 relative">
                                <i data-lucide="${icon}" class="w-4 h-4 text-orange-400"></i>
                                ${!isRead ? '<span class="absolute top-0 right-0 w-2 h-2 bg-orange-500 rounded-full animate-ping"></span>' : ''}
                            </div>
                            <div class="flex-1">
                                <p class="text-black dark:text-text-white text-sm font-medium mb-1 line-clamp-1">
                                    ${this.escapeHtml(title)}
                                </p>
                                <p class="text-gray-600 dark:text-text-white/60 text-xs line-clamp-2">
                                    ${this.escapeHtml(message)}
                                </p>
                                <span class="dark:text-text-white/40 text-gray-400 text-xs">
                                    ${this.escapeHtml(timestamp)}
                                </span>
                            </div>
                        </div>
                    </a>
                `;

                return cardElement;
            }

            maintainNotificationLimit() {
                const notifications = this.notificationContainer.querySelectorAll('.notification-item');

                if (notifications.length > this.maxNotifications) {
                    const excessCount = notifications.length - this.maxNotifications;

                    // Remove excess notifications from the end with animation
                    for (let i = notifications.length - 1; i >= notifications.length - excessCount; i--) {
                        const notification = notifications[i];
                        this.removeNotificationWithAnimation(notification);
                    }
                }
            }

            removeNotificationWithAnimation(element) {
                element.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                element.style.opacity = '0';
                element.style.transform = 'translateX(-20px) scale(0.95)';
                element.style.maxHeight = element.offsetHeight + 'px';

                setTimeout(() => {
                    element.style.maxHeight = '0';
                    element.style.padding = '0';
                    element.style.margin = '0';
                }, 150);

                setTimeout(() => {
                    if (element.parentNode) {
                        element.parentNode.removeChild(element);
                    }
                }, 300);
            }

            updateNotificationBadge() {
                // Update any notification badge/counter in your UI
                const badge = document.querySelector('.notification-badge, [data-notification-badge]');
                if (badge) {
                    const currentCount = parseInt(badge.textContent || '0');
                    badge.textContent = currentCount + 1;
                    badge.classList.remove('hidden');
                }
            }

            showToast(message) {
                const toast = document.getElementById('notification-toast');
                const messageElement = document.getElementById('notification-message');
                const closeButton = document.getElementById('close-notification-btn');

                if (!toast || !messageElement || !closeButton) {
                    console.error('Toast elements not found.');
                    return;
                }

                // Set the message
                messageElement.textContent = message;

                // Show the notification with animation
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');

                // Auto dismiss after 4 seconds
                const timeoutId = setTimeout(() => {
                    this.hideToast();
                }, 4000);

                // Manual dismiss on click (remove existing listeners first)
                const newCloseButton = closeButton.cloneNode(true);
                closeButton.parentNode.replaceChild(newCloseButton, closeButton);

                newCloseButton.addEventListener('click', () => {
                    clearTimeout(timeoutId);
                    this.hideToast();
                });
            }

            hideToast() {
                const toast = document.getElementById('notification-toast');
                if (toast) {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                }
            }

            initializeLucideIcons(element) {
                // Initialize Lucide icons for new content
                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    lucide.createIcons();
                } else if (typeof window.lucide !== 'undefined') {
                    window.lucide.createIcons();
                }
            }

            // Utility methods
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

            
        
            getNotificationCount() {
                return this.notificationContainer ?
                    this.notificationContainer.querySelectorAll('.notification-item').length : 0;
            }
        }

        // Initialize the notification manager
        const notificationManager = new NotificationManager();

        // Make it globally accessible
        window.notificationManager = notificationManager;
    </script>
</div>
