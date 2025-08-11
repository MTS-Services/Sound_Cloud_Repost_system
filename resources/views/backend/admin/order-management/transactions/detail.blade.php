<x-admin::layout>
    <x-slot name="title">{{ __('Credit Transaction Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Credit Transaction Detail') }}</x-slot>
    <x-slot name="page_slug">credit-transaction</x-slot>


    <div class="glass-card rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-text-black dark:text-text-white">{{ __('Credit Transaction Detail List') }}
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
                <div class="flex-1">
                    <h2 class="text-3xl font-bold mb-2 text-gray-900 dark:text-white">Receiver:
                        {{ $transactions->receiver?->name ?? '' }}</h2>
                </div>
            </div>
            <!-- Campaign Stats -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Sender</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $transactions->sernder->name ?? 'System' }}
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Calculation Type</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        <span
                            class='badge badge-soft {{ $transactions->calculation_type_color }}'>{{ $transactions->calculation_type_name }}</span>
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Source Type</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $transactions->source_type ? SouceClassName($transactions->source_type) : 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Transaction Type</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $transactions->type_name }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Status</h4>
                    <p
                        class="text-xl font-bold
                        @if ($transactions->status == 'succeeded') text-green-500
                        @elseif($transactions->status == 'pending') text-yellow-500
                        @elseif($transactions->status == 'failed') text-red-500
                        @else @endif
                        ">
                        {{ $transactions->status }}
                    </p>
                </div>

                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Amount</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $transactions->amount ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Credits</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $transactions->credits ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Description</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $transactions->description ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Metadata</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        @if (is_array($transactions->metadata))
                            @foreach ($transactions->metadata as $key => $value)
                                {{ ucfirst($key) }}: {{ $value }}<br>
                            @endforeach
                        @else
                            {{ $transactions->metadata ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">Created At</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $transactions->created_at ?? 'N/A' }}</p>
                </div>
                <div class="bg-gray-100 dark:bg-slate-800 p-5 rounded-lg shadow">
                    <h4 class="text-gray-600 dark:text-gray-400 text-sm">updated_at</h4>
                    <p class="text-xl font-bold text-black dark:text-white">
                        {{ $transactions->updated_at ?? 'N/A' }}</p>
                </div>

            </div>


        </div>

    </div>

</x-admin::layout>
