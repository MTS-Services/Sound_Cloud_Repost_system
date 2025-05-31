{{-- Modern Details Modal Component --}}
@props(['title' => 'Details Modal'])

<div id="details_modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm animate-fade-in" onclick="closeDetailsModal()">
    </div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div
            class="glass-card relative w-full max-w-2xl mx-auto rounded-2xl shadow-2xl animate-slide-up bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg border border-white/20 dark:border-gray-700/30">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ __($title) }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('View detailed information') }}</p>
                    </div>
                </div>

                <button onclick="closeDetailsModal()"
                    class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Content -->
            <div id="modal-content" class="p-6 max-h-96 overflow-y-auto">
                <!-- Loading State -->
                <div id="loading-state" class="flex flex-col items-center justify-center py-12">
                    <div class="w-6 h-6 border-2 border-gray-300 border-t-blue-600 rounded-full animate-spin mb-4">
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('Loading details...') }}</p>
                </div>

                <!-- Content will be populated here -->
                <div id="details-content" class="hidden space-y-1"></div>

                <!-- Error State -->
                <div id="error-state" class="hidden text-center py-12">
                    <div
                        class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="alert-circle" class="w-8 h-8 text-red-500"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Failed to Load') }}</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        {{ __('Unable to fetch details. Please try again.') }}</p>
                    <button onclick="retryLoadDetails()"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                        <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                        {{ __('Retry') }}
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 rounded-b-2xl">
                <button onclick="closeDetailsModal()"
                    class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    {{ __('Close') }}
                </button>
                <button onclick="exportDetails()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    {{ __('Export') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dark .glass-card {
            background: rgba(31, 41, 55, 0.9);
            border: 1px solid rgba(75, 85, 99, 0.3);
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .detail-item {
            transition: all 0.2s ease;
        }

        .detail-item:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .dark .detail-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }
    </style>
@endpush
