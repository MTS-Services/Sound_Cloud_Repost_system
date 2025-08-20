<x-admin::layout>
    <x-slot name="title">{{ __('Payment Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Payment Detail') }}</x-slot>
    <x-slot name="page_slug">Detail</x-slot>

    <div
        class="glass-card rounded-xl p-6 mb-6 bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ __('Payment Details') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Comprehensive information about this payment transaction') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <x-button href="{{ route('om.credit-transaction.payments') }}"
                    class="flex items-center gap-2 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 transition-colors duration-200 rounded-lg px-4 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Overview Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payment Overview</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-4">
                                <div
                                    class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</h4>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $payments->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $payments->email_address ?? '' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center mb-4">
                                <div
                                    class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</h4>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $payments->payment_method ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Reference</h4>
                                    <p class="text-md font-medium text-gray-900 dark:text-white">
                                        {{ $payments->reference ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</h4>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $payments->status == \App\Models\Payment::STATUS_SUCCEEDED
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                        : ($payments->status == \App\Models\Payment::STATUS_PROCESSING
                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
                                            : ($payments->status == \App\Models\Payment::STATUS_FAILED
                                                ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                    {{ $payments->status_label }}
                                </span>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Amount</h4>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $payments->currency ?? 'USD' }} {{ number_format($payments->amount, 2) }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Credits Purchased
                                </h4>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-1"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                    </svg>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $payments->credits_purchased ?? '0' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payment Details</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Gateway</h4>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $payments->payment_gateway_label }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Provider ID
                                </h4>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $payments->payment_provider_id ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Intent ID</h4>
                                <p class="text-sm font-medium text-gray-900 dark:text-white break-all">
                                    {{ $payments->payment_intent_id ?? 'N/A' }}</p>
                            </div>

                        </div>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">User URN</h4>
                                <p class="text-sm font-medium text-gray-900 dark:text-white break-all">
                                    {{ $payments->user_urn ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Order ID</h4>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $payments->order->order_id ?? 'N/A' }}</p>
                            </div>
                            
                            @if ($payments->failure_reason)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Failure Reason
                                    </h4>
                                    <p class="text-sm font-medium text-red-600 dark:text-red-400">
                                        {{ $payments->failure_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Address Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Billing Address</h4>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $payments->address ?? 'N/A' }}<br>
                                {{ $payments->postal_code ?? 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Receipt</h4>
                            @if ($payments->receipt_url)
                                <a href="{{ $payments->receipt_url }}" target="_blank"
                                    class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    View Receipt
                                </a>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">No receipt available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata Card -->
            {{-- @if ($payments->metadata && is_array($payments->metadata))
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Metadata</h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($payments->metadata as $key => $value)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                        <dd class="text-sm text-gray-900 dark:text-white break-all">
                                            {{ $value }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                </div>
            @endif --}}
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Timeline Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payment Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0 flex flex-col items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="h-12 w-0.5 bg-gray-200 dark:bg-gray-700 mt-1"></div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Created</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ timeFormat($payments->created_at) }}</p>
                            </div>
                        </div>

                        @if ($payments->processed_at)
                            <div class="flex">
                                <div class="flex-shrink-0 flex flex-col items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-green-600 dark:text-green-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="h-12 w-0.5 bg-gray-200 dark:bg-gray-700 mt-1"></div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Processed</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ timeFormat($payments->processed_at) }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Updated</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ timeFormat($payments->updated_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          

            <!-- Notes Card -->
            @if ($payments->notes)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Notes</h3>
                    </div>
                    <div class="p-6">
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 border border-gray-100 dark:border-yellow-800 rounded-lg p-4">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">{{ $payments->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin::layout>
