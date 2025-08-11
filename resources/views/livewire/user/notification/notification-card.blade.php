<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @if ($compact)
        {{-- Compact Card for Sidebar --}}
        <div class="card bg-white/90 dark:bg-gray-800/90 shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer {{ $this->isRead ? 'opacity-75' : 'ring-2 ring-orange-200 dark:ring-orange-700' }}"
            wire:click="openDetail">
            <div class="card-body p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 pt-1">
                        <div
                            class="w-2 h-2 rounded-full {{ $this->isRead ? 'bg-gray-400' : 'bg-gradient-to-r from-orange-500 to-red-500 blink-indicator' }}">
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-sm truncate text-gray-800 dark:text-gray-200">
                            {{ $this->getNotificationTitle() }}</h3>
                        <p class="text-xs mt-1 line-clamp-2 text-gray-600 dark:text-gray-400">
                            {{ $this->getNotificationMessage() }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ $this->getNotificationTime() }}</span>
                            <span
                                class="badge badge-outline badge-xs text-orange-600 border-orange-300">{{ $this->getTypeLabel() }}</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="{{ $this->getNotificationIcon() }} text-sm text-orange-500"></i>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Full Card for Main Views --}}
        <div class="card bg-white/90 dark:bg-gray-800/90 shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer {{ $this->isRead ? 'opacity-75' : 'ring-2 ring-orange-200 dark:ring-orange-700' }}"
            wire:click="openDetail">
            <div class="card-body p-6">
                <div class="flex items-start gap-4">
                    {{-- Status Indicator --}}
                    <div class="flex-shrink-0 pt-1">
                        <div
                            class="w-4 h-4 rounded-full {{ $this->isRead ? 'bg-gray-400' : 'bg-gradient-to-r from-orange-500 to-red-500 blink-indicator' }}">
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="flex-shrink-0 bg-orange-500 rounded-full">
                        <div
                            class="w-12 h-12  rounded-2xl flex items-center justify-center shadow-lg  bg-orange-500">
                            <x-lucide-home class="w-6 h-6 bg-orange-500"/>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-200">
                                    {{ $this->getNotificationTitle() }}</h2>
                                <p class="line-clamp-2 mb-3 leading-relaxed text-gray-600 dark:text-gray-400">
                                    {{ $this->getNotificationMessage() }}</p>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="flex items-center text-gray-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $this->getNotificationTime() }}
                                    </span>
                                    <span
                                        class="badge badge-outline text-orange-600 border-orange-300">{{ $this->getTypeLabel() }}</span>
                                    @if (!$this->isRead)
                                        <span
                                            class="badge bg-gradient-to-r from-orange-500 to-red-500 text-white border-none">New</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            @if ($showActions)
                                <div class="flex-shrink-0" wire:click.stop>
                                    <div class="dropdown dropdown-end">
                                        <div tabindex="0" role="button" class="btn btn-ghost btn-sm">
                                          <i class="fas fa-ellipsis-v"></i>

                                        </div>
                                        <ul tabindex="0"
                                            class="dropdown-content menu bg-white dark:bg-gray-800 rounded-box z-[1] w-52 p-2 shadow-xl border border-gray-200 dark:border-gray-700">
                                            <li>
                                                <a wire:click="toggleRead"
                                                    class="hover:bg-orange-100 dark:hover:bg-orange-900/30">
                                                    <i
                                                        class="fas fa-{{ $this->isRead ? 'eye-slash' : 'eye' }} mr-2"></i>
                                                    {{ $this->isRead ? 'Mark Unread' : 'Mark Read' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a wire:click="deleteNotification"
                                                    wire:confirm="Are you sure you want to delete this notification?"
                                                    class="text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @push('cs')
        <style>
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

            .blink-indicator {
                animation: blink 2s infinite;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    @endpush
</div>
