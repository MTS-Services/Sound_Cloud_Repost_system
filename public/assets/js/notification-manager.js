/**
 * Reusable Notification Management System
 * Handles both side-panel and full-page notifications
 */
class NotificationManager {
    constructor(config = {}) {
        this.config = {
            maxNotifications: 15,
            containerId: 'notification-container',
            toastId: 'notification-toast',
            autoRefresh: true,
            animations: true,
            type: 'sidebar', // 'sidebar' or 'full-page'
            ...config
        };
        
        this.container = null;
        this.isInitialized = false;
        this.echoChannels = [];
        
        this.init();
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.container = document.getElementById(this.config.containerId);
        if (!this.container) {
            console.warn(`Notification container #${this.config.containerId} not found`);
            return;
        }

        this.setupEchoListeners();
        this.isInitialized = true;
    }

    setupEchoListeners() {
        if (!window.Echo) {
            console.warn('Laravel Echo not available');
            return;
        }

        // Public notifications channel
        const publicChannel = window.Echo.channel('admins');
        publicChannel.listen('.notification.sent', (e) => {
            // console.log('Public notification received:', e);
            this.handleNewNotification(e, false);
            this.showToast(e.title || 'New notification received.');
        });
        this.echoChannels.push(publicChannel);

        // Private notifications channel
        if (window.Laravel?.user?.id) {
            const privateChannel = window.Echo.private(`admin.${window.Laravel.user.id}`);
            privateChannel.listen('.notification.sent', (e) => {
                // console.log('Private notification received:', e);
                this.handleNewNotification(e, false);
                this.showToast(e.title || 'New notification received.');
            });
            this.echoChannels.push(privateChannel);
        }
    }

    handleNewNotification(notificationData, isRead = false) {
        if (!this.container) return;

        if (this.config.type === 'sidebar') {
            this.handleSidebarNotification(notificationData, isRead);
        } else {
            this.handleFullPageNotification(notificationData, isRead);
        }

        this.updateNotificationBadge();
    }

    handleSidebarNotification(data, isRead) {
        // Create new notification card for sidebar
        const newNotificationCard = this.createSidebarNotificationCard(data, isRead);
        
        if (this.config.animations) {
            newNotificationCard.style.opacity = '0';
            newNotificationCard.style.transform = 'translateY(-10px)';
        }
        
        // Insert at the beginning
        this.container.insertBefore(newNotificationCard, this.container.firstChild);
        
        if (this.config.animations) {
            requestAnimationFrame(() => {
                newNotificationCard.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                newNotificationCard.style.opacity = '1';
                newNotificationCard.style.transform = 'translateY(0)';
            });
        }

        // Maintain notification limit for sidebar
        this.maintainNotificationLimit();
        this.initializeLucideIcons(newNotificationCard);
    }

    handleFullPageNotification(data, isRead) {
        // For full page, we typically want to refresh or show a toast
        if (this.config.autoRefresh) {
            this.showToast('New notification received. Page will refresh in 3 seconds...');
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        } else {
            this.showToast('New notification received. Refresh page to see it.');
        }
    }

    createSidebarNotificationCard(data, isRead = false) {
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
        if (this.config.type !== 'sidebar') return;

        const notifications = this.container.querySelectorAll('.notification-item');
        
        if (notifications.length > this.config.maxNotifications) {
            const excessCount = notifications.length - this.config.maxNotifications;
            
            for (let i = notifications.length - 1; i >= notifications.length - excessCount; i--) {
                const notification = notifications[i];
                this.removeNotificationWithAnimation(notification);
            }
        }
    }

    removeNotificationWithAnimation(element) {
        if (!this.config.animations) {
            element.remove();
            return;
        }

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
        // Update notification badges
        const badges = document.querySelectorAll('.notification-badge, [data-notification-badge]');
        badges.forEach(badge => {
            const currentCount = parseInt(badge.textContent || '0');
            badge.textContent = currentCount + 1;
            badge.classList.remove('hidden');
        });

        // Update unread count displays
        const unreadCounts = document.querySelectorAll('#all-notifications-unread-count, [data-unread-count]');
        unreadCounts.forEach(counter => {
            const currentCount = parseInt(counter.textContent || '0');
            counter.textContent = currentCount + 1;
        });
    }

    showToast(message, type = 'info') {
        const toast = document.getElementById(this.config.toastId);
        const messageElement = document.getElementById('notification-message');
        const closeButton = document.getElementById('close-notification-btn');

        if (!toast || !messageElement || !closeButton) {
            // Fallback toast
            this.createFallbackToast(message, type);
            return;
        }

        messageElement.textContent = message;

        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');

        const timeoutId = setTimeout(() => {
            this.hideToast();
        }, 4000);

        // Handle close button
        const newCloseButton = closeButton.cloneNode(true);
        closeButton.parentNode.replaceChild(newCloseButton, closeButton);
        
        newCloseButton.addEventListener('click', () => {
            clearTimeout(timeoutId);
            this.hideToast();
        });
    }

    hideToast() {
        const toast = document.getElementById(this.config.toastId);
        if (toast) {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0');
        }
    }

    createFallbackToast(message, type = 'info') {
        const existingToast = document.getElementById('fallback-toast');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.id = 'fallback-toast';
        toast.className = `fixed top-5 right-5 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'warning' ? 'bg-yellow-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <span>${this.escapeHtml(message)}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="ml-2 text-white hover:text-gray-200">
                    ✕
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    initializeLucideIcons(element = document) {
        try {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            } else if (typeof window.lucide !== 'undefined') {
                window.lucide.createIcons();
            }
        } catch (error) {
            console.warn('Could not initialize Lucide icons:', error);
        }
    }

    // Public API methods
    markAsRead(notificationElement) {
        const unreadIndicator = notificationElement.querySelector('.animate-ping');
        if (unreadIndicator) {
            unreadIndicator.remove();
        }
    }

    markAllAsRead() {
        if (!this.container) return;

        const unreadIndicators = this.container.querySelectorAll('.animate-ping');
        unreadIndicators.forEach(indicator => indicator.remove());
        
        // Update badges
        const badges = document.querySelectorAll('.notification-badge, [data-notification-badge]');
        badges.forEach(badge => {
            badge.textContent = '0';
            badge.classList.add('hidden');
        });
    }

    async sendMarkAllAsReadRequest() {
        try {
            const response = await fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            const data = await response.json();
            if (data.success) {
                this.markAllAsRead();
                this.showToast('All notifications marked as read', 'success');
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
            this.showToast('Failed to mark notifications as read', 'error');
        }
    }

    getNotificationCount() {
        return this.container ? this.container.querySelectorAll('.notification-item').length : 0;
    }

    refresh() {
        if (this.config.type === 'full-page') {
            window.location.reload();
        } else {
            // For sidebar, we could implement AJAX refresh
            this.showToast('Refreshing notifications...');
            setTimeout(() => window.location.reload(), 1000);
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

    destroy() {
        // Cleanup Echo channels
        this.echoChannels.forEach(channel => {
            try {
                window.Echo.leave(channel.name);
            } catch (error) {
                console.warn('Error leaving Echo channel:', error);
            }
        });
        this.echoChannels = [];
        this.isInitialized = false;
    }
}

// Factory function for easy initialization
window.createNotificationManager = function(config = {}) {
    return new NotificationManager(config);
};

// Auto-initialize based on page context
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar notification manager if container exists
    if (document.getElementById('notification-container')) {
        window.notificationManager = new NotificationManager({
            type: 'sidebar',
            containerId: 'notification-container'
        });
    }
    
    // Initialize full-page notification manager if all-notifications container exists
    if (document.getElementById('all-notifications-container')) {
        window.allNotificationsManager = new NotificationManager({
            type: 'full-page',
            containerId: 'all-notifications-container',
            autoRefresh: false // Prevent auto-refresh on all notifications page
        });
    }
});