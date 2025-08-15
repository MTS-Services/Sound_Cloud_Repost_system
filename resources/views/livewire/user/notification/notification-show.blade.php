<div class="container mx-auto px-4 py-8">
    <x-slot name="page_slug">notification</x-slot>

    <div class="max-w-4xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('user.notifications.index') }}" wire:navigate
                class="btn btn-outline border-orange-300 text-orange-600 hover:bg-orange-500 hover:text-white hover:border-orange-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Notifications
            </a>
        </div>

        {{-- confirmation modal --}}
        {{-- <div x-data="{ showConfirmModal: @entangle('showConfirmModal').live }" x-show="showConfirmModal"
            class="absolute inset-0 pointer-events-none z-50 bg-black/50 backdrop-blur-xs">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="card bg-white dark:bg-gray-800/90 shadow-lg max-w-4xl">
                    <div class="card-body p-8">
                        <h1 class="text-3xl font-bold mb-2 text-gray-800 dark:text-gray-200">
                            {{ $this->getNotificationTitle() }}
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400">
                            {{ $this->getNotificationMessage() }}
                        </p>
                        <div class="flex justify-between mt-4">
                            <button wire:click="confirmNotification"
                                class="btn btn-outline border-orange-300 text-orange-600 hover:bg-orange-500 hover:text-white hover:border-orange-500">
                                Confirm
                            </button>
                            <button wire:click="cancelNotification"
                                class="btn btn-outline border-gray-600 text-gray-600 hover:bg-gray-500 hover:text-white hover:border-gray-500">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- Notification Detail Card --}}
        <div class="card bg-white/90  dark:bg-gray-800/90 shadow-xl">
            <div class="card-body p-8">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg bg-orange-500/30">
                            <i data-lucide="{{ $this->getNotificationIcon() }}" class="w-8 h-8 text-orange-400"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-2 text-gray-800 dark:text-gray-200">
                                {{ $this->getNotificationTitle() }}
                            </h1>
                            <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $customNotification->created_at_human }}
                                </span>
                                <span class="badge badge-outline text-orange-600 border-orange-300">
                                    {{ $this->getTypeLabel() }}
                                </span>

                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-white dark:bg-gray-800 rounded-box z-[1] w-52 p-2 shadow-xl border border-gray-200 dark:border-gray-700">
                            <li>
                                <a wire:click="toggleRead" class="hover:bg-orange-100 dark:hover:bg-orange-900/30">
                                    <i class="fas fa-{{ $isRead ? 'eye-slash' : 'eye' }} mr-2"></i>
                                    {{ $isRead ? 'Mark Unread' : 'Mark Read' }}
                                </a>
                            </li>
                            <li>
                                <a wire:click="deleteNotification" wire:navigate="route('user.notifications.index')"
                                    class="text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30">
                                    <i class="fas fa-trash mr-2"></i>
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Content --}}
                <div class="prose max-w-none dark:prose-invert">
                    <div
                        class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 rounded-lg p-6 mb-6">
                        <p class="text-lg leading-relaxed text-gray-800 dark:text-gray-300 mb-2">
                            {{ $this->getNotificationMessage() }}
                        </p>
                        <p class="leading-relaxed text-gray-600 dark:text-gray-400 mb-0">
                            {{ $this->getNotificationDescription() }}
                        </p>
                    </div>

                    {{-- Additional Data --}}
                    @if (isset($customNotification->message_data['additional_data']))
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Additional
                                Information</h3>
                            <div class="space-y-2">
                                @foreach ($customNotification->message_data['additional_data'] as $key => $value)
                                    <div
                                        class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                        <span
                                            class="font-medium text-gray-600 dark:text-gray-400">{{ str_replace('_', ' ', $key) }}:</span>
                                        <span
                                            class="text-gray-800 dark:text-gray-200">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Action URL --}}
                    @if ($customNotification->url)
                        <div class="flex justify-center mt-8">
                            <a href="{{ $customNotification->url }}"
                                class="btn btn-lg bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white border-none shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Continue
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Metadata --}}
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 bg-ora">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm home">
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Notification Details</h4>
                            <div class="space-y-1 text-gray-600 dark:text-gray-400">
                                <div>Created: {{ $customNotification->created_at_formatted }}</div>
                                <div>Updated: {{ $customNotification->updated_at_formatted }}</div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Read Status</h4>
                            <div class="space-y-1 text-gray-600 dark:text-gray-400">
                                @if ($isRead)
                                    @php
                                        $status = $customNotification->statuses->first();
                                    @endphp
                                    <div class="text-green-600">✓ Read on
                                        {{ $status ? $status->read_at_formatted : 'Unknown' }}</div>
                                @else
                                    <div class="text-red-600">✗ Not read yet</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
