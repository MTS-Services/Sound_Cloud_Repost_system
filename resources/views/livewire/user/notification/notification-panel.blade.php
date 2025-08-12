<div class="relative">
    {{-- Notification Button --}}
    <button wire:click="togglePanel"
        class="btn btn-ghost btn-circle relative hover:bg-orange-100 dark:hover:bg-orange-900/30 text-orange-500">
        <x-lucide-bell class="w-6 h-6" />
        @if ($this->unreadCount > 0)
            <span
                class="absolute -top-1 -right-1 badge badge-sm bg-gradient-to-r from-red-500 to-pink-500 text-white border-none blink-indicator pulse-glow">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    {{-- Panel Overlay --}}
    @if ($showPanel)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" wire:click="closePanel"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>
    @endif

    {{-- Notification Panel --}}
    @if ($showPanel)
        <div class="fixed right-0 top-0 h-full w-96 bg-white/95 dark:bg-gray-900/95 notification-panel z-50 overflow-hidden border-l border-orange-200 dark:border-gray-700"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="transform translate-x-full"
            x-transition:enter-end="transform translate-x-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full">

            {{-- Panel Header --}}
            <div
                class="flex items-center justify-between p-4 border-b border-orange-200 dark:border-gray-700 bg-gradient-to-r from-orange-500 to-amber-500 text-white">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-bell mr-2"></i>
                    Notifications
                    @if ($this->unreadCount > 0)
                        <span class="ml-2 badge bg-white/20 text-white border-none">{{ $this->unreadCount }} new</span>
                    @endif
                </h2>
                <button wire:click="closePanel"
                    class="btn btn-ghost btn-sm text-white hover:bg-white/20 bg-white/20 border-none">
                    <i class="fas fa-items">x</i>
                </button>
            </div>

            {{-- Panel Content --}}
            <div class="flex flex-col h-full">
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    @forelse($notifications as $notification)
                        <livewire:user.notification.notification-card :notification="$notification" :compact="true"
                            :show-actions="false" :key="'panel-notification-' . $notification->id" />
                    @empty
                        <div class="text-center py-8">
                            <div
                                class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 flex items-center justify-center">
                                <i class="fas fa-bell-slash text-2xl text-orange-400"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No notifications yet</p>
                        </div>
                    @endforelse
                </div>

                {{-- Panel Footer --}}
                <div
                    class="flex items-center justify-between p-4 border-t border-orange-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-gray-800 dark:to-gray-700 space-x-4 mb-15">

                    @if ($this->unreadCount > 0)
                        <button wire:click="markAllAsRead"
                            class="btn btn-outline btn-sm border-orange-300 text-white bg-orange-500 hover:bg-orange-600 hover:text-white hover:border-orange-500 flex-1 max-w-xs">
                            <i class="fas fa-check-circle mr-2"></i>
                            Mark All as Read
                        </button>
                    @else
                        <div></div>
                    @endif

                    <a href="{{ route('user.notifications.index') }}"
                        class="btn btn-sm bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white border-none flex-1 max-w-xs text-center">
                        <i class="fas fa-list mr-2"></i>
                        View All
                    </a>
                </div>

                <a href="{{ route('user.notifications.index') }}" wire:navigate
                    class="btn btn-sm w-full bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white border-none">
                    <i class="fas fa-list mr-2"></i>
                    View All Notifications
                </a>
            </div>
        </div>

    @endif

    @push('cs')
        <style>
            .notification-panel {
                box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(10px);
            }

            [data-theme="dark"] .notification-panel {
                box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.3);
            }

            @keyframes blink {

                0%,
                50% {
                    opacity: 1;
                }

                51%,
                100% {
                    opacity: 0.4;
                }
            }

            @keyframes pulse-glow {

                0%,
                100% {
                    box-shadow: 0 0 5px rgba(234, 88, 12, 0.5);
                }

                50% {
                    box-shadow: 0 0 20px rgba(234, 88, 12, 0.8), 0 0 30px rgba(234, 88, 12, 0.6);
                }
            }

            .blink-indicator {
                animation: blink 2s infinite;
            }

            .pulse-glow {
                animation: pulse-glow 2s infinite;
            }
        </style>
    @endpush

</div>
