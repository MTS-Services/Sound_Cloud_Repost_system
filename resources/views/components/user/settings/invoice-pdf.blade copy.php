    <div id="invoice" class="max-w-4xl mx-auto bg-white shadow-2xl rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-400 text-white p-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold mb-2">INVOICE</h1>
                    <div class="bg-orange-400 bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                        <p class="text-sm opacity-90">Invoice ID</p>
                        <p class="text-xl font-semibold" id="invoice-id">{{ invoiceId() }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-white text-orange-500 p-4 rounded-lg">
                        <a class="flex items-center" href="{{ route('user.dashboard') }}" data-discover="true"
                            wire:navigate>
                            <div>
                                <img src="{{ storage_url(app_setting('app_logo')) }}" alt="{{ config('app.name') }}"
                                    class="w-36 lg:w-48 dark:hidden" />
                                <img src="{{ storage_url(app_setting('app_logo_dark')) }}" alt="{{ config('app.name') }}"
                                    class="w-36 lg:w-48 hidden dark:block" />
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-8">
            <!-- Invoice Details Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Bill To -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-orange-500 pb-2">
                        Bill To</h3>
                    <div class="space-y-2">
                        <p class="font-medium" id="customer-name">{{ $payment->name ?? $payment->user?->name }}</p>
                        <p class="text-gray-600" id="customer-email">
                            {{ $payment->email_address ?? $payment->user?->email }}
                        </p>
                        <p class="text-gray-600" id="customer-address">
                            {{ $payment->address ?? $payment->user?->address }}
                        </p>
                    </div>
                </div>

                <!-- Invoice Info -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-orange-500 pb-2">
                        Invoice Details</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium"
                                id="invoice-date">{{ date('M d, Y', strtotime($payment->created_at)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-nowrap">Order ID:</span>
                            <span class="font-medium text-nowrap" id="order-id">{{ $payment->order?->order_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="badge badge-soft {{ $payment->status_color }}">{{ $payment->status }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-orange-500 pb-2">
                        Payment Details</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Method:</span>
                            <span class="font-medium">{{ $payment->payment_method ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Gateway:</span>
                            <span class="font-medium">{{ $payment->payment_gateway_label ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Currency:</span>
                            <span class="font-medium">{{ $payment->currency ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Purchase Details</h3>
                <div class="overflow-hidden rounded-lg border border-orange-500">
                    <table class="w-full">
                        <thead class="bg-orange-500 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">Description</th>
                                <th class="px-6 py-4 text-center font-semibold">Credits</th>
                                <th class="px-6 py-4 text-center font-semibold">Rate</th>
                                <th class="px-6 py-4 text-right font-semibold">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $payment->notes }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center bg-brand-orange bg-opacity-10 text-orange-500 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $payment->credits_purchased ?? '0.00' }} Credits
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-medium">
                                    {{ ($payment->exchange_rate ?? '0.00') . ' ' . $payment->currency }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-lg">
                                    {{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Transaction Summary -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaction Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span
                                    class="font-medium">{{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Processing Fee:</span>
                                <span class="font-medium">{{ '0.00' . ' ' . $payment->currency }}</span>
                            </div>
                            <div class="flex justify-between border-t border-orange-500 pt-2">
                                <span class="text-lg font-semibold">Total Amount:</span>
                                <span
                                    class="text-xl font-bold text-orange-500">{{ ($payment->amount ?? '0.00') . ' ' . $payment->currency }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border-2 border-orange-500">
                        @if ($payment->order?->source_type == App\Models\Plan::class)
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-1">Your Subscriped Plan</p>
                            <p class="text-3xl font-bold text-orange-500">
                                {{ $payment->order?->source?->name ?? 'N/A' }}
                            </p>
                            {{-- <p class="text-xs text-gray-500 mt-1"></p> --}}
                        </div>
                        @else
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-1">Credits Purchased</p>
                            <p class="text-3xl font-bold text-orange-500">
                                {{ $payment->credits_purchased ?? '0.00' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Added to your account</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                <h4 class="font-semibold text-yellow-800 mb-2">Transaction Notes</h4>
                <p class="text-yellow-700 text-sm">
                    {{ $payment->notes ?? 'N/A' }}
                </p>
            </div>

            <!-- Footer -->
            <div class="border-t border-orange-500 pt-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">Payment Information</h4>
                        <p class="text-sm text-gray-600">
                            Payment processed securely through {{ $payment->payment_gateway_label ?? 'N/A' }}.<br>
                            Order ID: <span
                                class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $payment->order?->order_id ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <h4 class="font-semibold text-gray-800 mb-2">Contact Information</h4>
                        <p class="text-sm text-gray-600">
                            support@yourcompany.com<br>
                            +1 (555) 123-4567
                        </p>
                    </div>
                </div>

                <div class="mt-8 text-center py-4 border-t border-orange-500">
                    <p class="text-xs text-gray-500">
                        This invoice was generated automatically on <span
                            id="generated-date">{{ date('M d, Y h:i A', strtotime($payment->created_at)) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>