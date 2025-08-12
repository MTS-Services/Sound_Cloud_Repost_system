<span x-data="notificationCounter()" @unread-count-updated.window="updateCount($event.detail.count)" x-show="unreadCount > 0"
    x-text="unreadCount > 99 ? '99+' : unreadCount"
    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1">
</span>

<script>
    function notificationCounter() {
        return {
            unreadCount: 0,

            init() {
                this.getUnreadCount();
            },

            async getUnreadCount() {
                try {
                    const response = await axios.get('/admin/notifications/unread-count');
                    this.unreadCount = response.data.unread_count;
                } catch (error) {
                    console.error('Error fetching unread count:', error);
                }
            },

            updateCount(count) {
                this.unreadCount = count;
            }
        }
    }
</script>
