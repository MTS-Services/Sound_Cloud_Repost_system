<div>

    <x-slot name="page_slug">notifications</x-slot>



    {{-- Main Content --}}
    <main class="">
        <div class="flex flex-col mb-6  sticky -top-6 py-4 left-0 z-10 bg-orange-200/10 backdrop-blur-2xl dark:bg-slate-800 rounded-md">
            {{-- Filter and Search Bar --}}
            <div class="flex items-center justify-between gap-4 z-10 max-w-4xl mx-auto w-full">
                <livewire:user.notification.notification-filter :filter="$filter" :total-count="$totalCount" :unread-count="$unreadCount"
                    :read-count="$readCount" />
                @if ($totalCount > 0)
                    <div class="flex-1">
                        <div class="flex items-center justify-end">
                            <div class="flex items-center gap-3 ">
                                @if ($unreadCount > 0)
                                    <button wire:click="$dispatch('mark-all-as-read')"
                                        wire:confirm="Are you sure you want to mark all notifications as read?"
                                        class="btn btn-sm py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white border-none">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Mark All as Read ({{ $unreadCount }})
                                    </button>
                                @endif

                                <button wire:click="$dispatch('clear-all-notifications')"
                                    wire:confirm="Are you sure you want to delete all notifications? This action cannot be undone."
                                    class="btn btn-sm py-3 btn-outline border-red-300 text-red-600 hover:bg-red-500 hover:text-white hover:border-red-500">
                                    <i class="fas fa-trash mr-2"></i>
                                    Clear All
                                </button>
                            </div>
                        </div>
                    </div>

                @endif
            </div>
        </div>
        <div class="max-w-4xl mx-auto">

            {{-- Notifications List --}}
            <div class="space-y-4" wire:loading.class="opacity-50">
                @forelse($notifications as $notification)
                    <livewire:user.notification.notification-card :notification="$notification" :show-actions="true"
                        :key="'notification-' . $notification->id" />
                @empty
                    {{-- Empty State --}}
                    <div class="text-center py-16">
                        <div
                            class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 flex items-center justify-center">
                            <i class="fas fa-bell-slash text-4xl text-orange-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-gray-800 dark:text-gray-200">No notifications found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            @if ($search)
                                No notifications match your search for "{{ $search }}".
                            @elseif($filter !== 'all')
                                No {{ $filter }} notifications found.
                            @else
                                You don't have any notifications yet.
                            @endif
                        </p>
                        <div class="flex items-center justify-center gap-4">
                            @if ($search || $filter !== 'all')
                                <button wire:click="$set('search', ''); $set('filter', 'all')"
                                    class="btn bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white border-none">
                                    <i class="fas fa-refresh mr-2"></i>
                                    Show All Notifications
                                </button>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Loading State --}}
            <div wire:loading class="flex justify-center py-8">
                <div class="flex items-center gap-3 text-orange-600">
                    <div class="loading loading-spinner loading-md"></div>
                    <span>Loading notifications...</span>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($notifications->hasPages())
                <div class="flex justify-center mt-12">
                    {{ $notifications->links() }}
                    {{-- {{ $notifications->links('components.pagination.wire-navigate') }} --}}
                </div>
            @endif
        </div>
    </main>
</div>
