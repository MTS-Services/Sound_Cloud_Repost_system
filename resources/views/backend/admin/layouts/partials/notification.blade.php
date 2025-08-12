{{-- @props(['notifications']) --}}

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
            <x-button href="#" :outline="true" type="secondary" onclick="window.notificationManager?.sendMarkAllAsReadRequest()">
                {{ __('Mark All As Read') }}
            </x-button>
            <x-button href="{{ route('admin.notifications.index') }}" :outline="true">
                {{ __('See All') }}
            </x-button>
        </div>
    </div>
</div>

{{-- Include the reusable notification manager --}}
<script src="{{ asset('assets/js/notification-manager.js') }}"></script>

<script>
    // Make Laravel user data available for Echo channels
    window.Laravel = window.Laravel || {};
    window.Laravel.user = {
        id: {{ auth()->id() ?? 'null' }}
    };

    // The NotificationManager will auto-initialize from the included script
</script>