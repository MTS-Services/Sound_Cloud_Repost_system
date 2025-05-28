<div x-show="showNotifications" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    class="fixed right-0 top-0 h-full w-80 glass-card z-50 p-6 overflow-y-auto custom-scrollbar">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-text-white">Notifications</h3>
        <button @click="toggleNotifications()" class="p-2 rounded-lg hover:bg-white/10 transition-colors">
            <i data-lucide="x" class="w-5 h-5 text-text-white"></i>
        </button>
    </div>
    <div class="space-y-4">
        <template x-for="notification in notifications" :key="notification.id">
            <div class="p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="notification.iconBg">
                        <i :data-lucide="notification.icon" class="w-4 h-4" :class="notification.iconColor"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-text-white text-sm font-medium mb-1" x-text="notification.title"></p>
                        <p class="text-text-dark-primary text-xs" x-text="notification.message"></p>
                        <span class="text-text-white/40 text-xs" x-text="notification.time"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
