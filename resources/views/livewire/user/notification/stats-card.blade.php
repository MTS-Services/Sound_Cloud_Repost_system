<div class="card bg-white/90 dark:bg-gray-800/90 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 stats-card {{ getColorClasses()['glow'] }}"
    wire:loading.class="opacity-50">
    <div class="card-body p-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $title }}</p>
                <h2
                    class="text-3xl font-bold {{ $getColorClasses()['text'] }} mt-2 {{ $type === 'unread' && $value > 0 ? 'animate-pulse' : '' }}">
                    {{ number_format($value) }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $description }}</p>
            </div>
            <div class="ml-4">
                <div
                    class="w-16 h-16 {{ $getColorClasses()['bg'] }} rounded-2xl flex items-center justify-center shadow-lg {{ $type === 'unread' && $value > 0 ? 'pulse-glow' : '' }}">
                    <i class="{{ $icon }} text-2xl text-white"></i>
                </div>
            </div>
        </div>

        @if ($showProgress)
            <div class="mt-4">
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{{ number_format($this->getProgressPercentage(), 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                    <div class="{{ $getColorClasses()['progress'] }} h-2 rounded-full transition-all duration-1000 ease-out {{ $type === 'unread' && $value > 0 ? 'blink-indicator' : '' }}"
                        style="width: {{ $this->getProgressPercentage() }}%"></div>
                </div>
            </div>
        @endif

        {{-- Loading State --}}
        <div wire:loading
            class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 rounded-lg flex items-center justify-center">
            <div class="loading loading-spinner loading-md {{ $getColorClasses()['text'] }}"></div>
        </div>
    </div>

    <style>
        .stats-card {
            background: linear-gradient(135deg, rgba(234, 88, 12, 0.1) 0%, rgba(249, 115, 22, 0.05) 100%);
            border: 1px solid rgba(234, 88, 12, 0.2);
            backdrop-filter: blur(10px);
        }

        [data-theme="dark"] .stats-card {
            background: linear-gradient(135deg, rgba(234, 88, 12, 0.2) 0%, rgba(249, 115, 22, 0.1) 100%);
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

        .pulse-glow {
            animation: pulse-glow 2s infinite;
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

        .blink-indicator {
            animation: blink 2s infinite;
        }
    </style>

</div>
