<x-admin::layout>
    <x-slot name="title">{{ __('Payment Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Payment Detail') }}</x-slot>
    <x-slot name="page_slug">Detail</x-slot>


    <div class="glass-card rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Payment Detail List') }}
            </h2>
            <div class="flex items-center gap-2">

                <x-button href="{{ route('om.credit-transaction.index') }}" permission="credit-create">
                    {{ __('Back') }}
                </x-button>
            </div>
        </div>
    </div>
    <div
        class="w-full max-w-8xl mx-auto rounded-2xl shadow-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
        <div class="p-6 text-center">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1 text-left">
                    <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">Name:
                        {{ $payments->name ?? '' }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Email: {{ $payments->email_address ?? '' }}
                        </p>
                </div>
            </div>
            <!-- Campaign Stats -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Address</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->address ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Postal Code</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->postal_code ?? 'N/A' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Reference</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->reference ?? 'N/A'}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">User URN</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->user_urn ?? 'N/A'}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credit Transaction ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->credit_transaction_id ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Status</h4>
                    <p
                        class="text-xl font-bold 
                        @if ($payments->status == 'Success') text-green-500 
                        @elseif($payments->status == 'Pending') text-yellow-500 
                        @elseif($payments->status == 'Failed') text-red-500 
                        @else  @endif
                        ">
                        {{ $payments->status ?? 'N/A'}}
                    </p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Payment Method</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->payment_method ?? 'N/A'}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Payment Gateway</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->payment_gateway ?? 'N/A'}}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Payment Provider</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->payment_provider_id ?? '' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Metadata</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        @if (is_array($payments->metadata))
                            @foreach ($payments->metadata as $key => $value)
                                {{ ucfirst($key) }}: {{ $value }}<br>
                            @endforeach
                        @else
                            {{ $payments->metadata ?? 'N/A' }}
                        @endif
                    </p>
                </div>

                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Amount</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->amount ?? 'N/A'}}</p>
                </div>
                 <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Currency</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->currency ?? 'N/A'}}</p>
                </div>
                  <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credits Purchased</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->credits_purchased ?? 'N/A'}}</p>
                </div>
                  <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Exchange Rate</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->exchange_rate ?? 'N/A'}}</p>
                </div>
                  <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Payment Intent ID</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->payment_intent_id ?? 'N/A'}}</p>
                </div>
                  <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Receipt URL</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->receipt_url ?? 'N/A'}}</p>
                </div>
                  <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Failure Reason</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->failure_reason ?? 'N/A'}}</p>
                </div>
                  <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Processed At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->processed_at ?? 'N/A'}}</p>
                </div>
                  <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->created_at ?? 'N/A'}}</p>
                </div>
                  <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Updated At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $payments->updated_at ?? 'N/A'}}</p>
                </div>

            </div>


        </div>

    </div>

</x-admin::layout>
