{{-- @props(['notifications']) --}}

<div x-show="showNotifications" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    class="hidden fixed right-0 top-0 h-full max-h-screen z-50 py-2 pr-2 backdrop-blur-sm"
    :class="showNotifications ? '!block' : '!hidden'">

    <div class="w-96 glass-card overflow-y-auto custom-scrollbar rounded-2xl h-full flex flex-col">

        <div class="flex items-center justify-between p-6">
            <h3 class="text-xl font-bold text-black dark:text-text-white">Notifications</h3>
            <button @click="toggleNotifications()"
                class="p-2 rounded-lg bg-orange-500/20 transition-colors inset-shadow-2xs hover:bg-orange-500/10 group cursor-pointer">
                <i data-lucide="x" class="w-5 h-5 text-orange-800 dark:text-orange-100 group-hover:text-orange-500"></i>
            </button>
        </div>
        <div class="flex-1 space-y-4 h-full overflow-y-hidden px-6" id="notification-container">
            @foreach ($notifications as $notification)
                <div class="notification-item">
                    <x-admin.notification-card :notification="$notification" :isRead="$notification->statuses->isEmpty() ? false : true" />
                </div>
            @endforeach
        </div>


        <div class="flex items-center justify-between p-6 glass-card">
            <x-button href="javascript:void(0)" type="secondary"
                onclick="window.notificationManager?.sendMarkAllAsReadRequest()">
                {{ __('Mark All As Read') }}
            </x-button>
            <x-button href="{{ route('admin.notifications.index') }}">
                {{ __('See All') }}
            </x-button>
        </div>
    </div>
</div>


<script>
    // Make Laravel user data available for Echo channels
    window.Laravel = window.Laravel || {};
    window.Laravel.user = {
        id: {{ auth()->id() ?? 'null' }}
    };

    // The NotificationManager will auto-initialize from the included script
</script>
