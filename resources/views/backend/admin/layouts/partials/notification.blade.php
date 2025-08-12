{{-- @props(['notifications']) --}}

<div id="notification-toast"
    class="absolute top-5 right-5 w-72 z-50 rounded-2xl shadow-2xl bg-white text-black transition-all duration-500 ease-in-out transform translate-x-full opacity-0">
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
        <div class="space-y-4 h-full max-h-[80vh] overflow-y-auto px-6">

            @foreach ($notifications as $notification)
                <x-admin.notification-card :notification="$notification" :isRead="$notification->statuses->isEmpty() ? false : true" />
            @endforeach


            {{-- <template x-for="notification in notifications" :key="notification.id">
            <div class="p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="notification.iconBg">
                        <i :data-lucide="notification.icon" class="w-4 h-4" :class="notification.iconColor"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-black dark:text-text-white text-sm font-medium mb-1" x-text="notification.title">
                        </p>
                        <p class=" text-gray-600 dark:text-text-white/60 text-xs" x-text="notification.message"></p>
                        <span class="dark:text-text-white/40 text-gray-400 text-xs" x-text="notification.time"></span>
                    </div>
                </div>
            </div>
        </template> --}}
        </div>
        <div class="flex items-center justify-between p-6 glass-card">
            <x-button href="" :outline="true" type="secondary">
                {{ __('Mark All As Read') }}
            </x-button>
            <x-button href="" :outline="true">
                {{ __('See All') }}
            </x-button>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Pusher.log = (message) => {
            //     if (window.console && window.console.log) {
            //         window.console.log(message);
            //     }
            // };

            window.Echo.channel('admins')
                .listen('.notification.sent', (e) => {
                    console.log(e);
                    showNotification('New message received.');
                    // Livewire.dispatch('notification-updated');
                });

            window.Echo.private('admin.{{ auth()->id() }}')
                .listen('.notification.sent', (e) => {
                    console.log(e);
                    showNotification('New message received.');
                    // Livewire.dispatch('notification-updated');
                });

            function showNotification(message) {
                const toast = document.getElementById('notification-toast');
                const messageElement = document.getElementById('notification-message');
                const closeButton = document.getElementById('close-notification-btn');

                if (!toast || !messageElement || !closeButton) {
                    console.error('Notification elements not found.');
                    return;
                }

                // Set the message
                messageElement.textContent = message;

                // Show the notification with animation
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');

                // Automatic dismissal after 3 seconds
                const timeoutId = setTimeout(() => {
                    hideNotification();
                }, 3000); // 3 seconds

                // Manual dismissal on click
                closeButton.addEventListener('click', () => {
                    clearTimeout(timeoutId); // Clear the auto-dismiss timer
                    hideNotification();
                }, {
                    once: true
                }); // Ensure the event listener is removed after first use
            }

            function hideNotification() {
                const toast = document.getElementById('notification-toast');
                if (toast) {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                }
            }

        });
    </script>
</div>
