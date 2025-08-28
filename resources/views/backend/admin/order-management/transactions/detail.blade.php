<x-admin::layout>
    <x-slot name="title">{{ __('Credit Transaction Detail') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Credit Transaction Detail') }}</x-slot>
    <x-slot name="page_slug">credit-transaction</x-slot>

    <div class="glass-card rounded-xl p-6 mb-6 bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ __('Transaction Details') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Comprehensive information about this credit transaction') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <x-button href="{{ route('om.credit-transaction.index') }}" 
                    class="flex items-center gap-2 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 transition-colors duration-200 rounded-lg px-4 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back ') }}
                </x-button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Overview Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Transaction Overview</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Receiver</h4>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $transactions->receiver?->name ?? 'System' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Sender</h4>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $transactions->sender?->name ?? 'System' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Source Type</h4>
                                    <p class="text-md font-medium text-gray-900 dark:text-white">{{ $transactions->source_type ? SouceClassName($transactions->source_type) : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Transaction Type</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $transactions->transaction_type_name }}
                                </span>
                            </div>
                            
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Calculation Type</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                                    $transactions->calculation_type == \App\Models\CreditTransaction::CALCULATION_TYPE_DEBIT ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                }}">
                                    {{ $transactions->calculation_type_name }}
                                </span>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                                    $transactions->status == \App\Models\CreditTransaction::STATUS_SUCCEEDED ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                    ($transactions->status == \App\Models\CreditTransaction::STATUS_PROCESSING ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                    ($transactions->status == \App\Models\CreditTransaction::STATUS_FAILED ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : 
                                    ($transactions->status == \App\Models\CreditTransaction::STATUS_REFUNDED ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 
                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'))) 
                                }}">
                                    {{ $transactions->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Financial Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Financial Details</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Credits</h4>
                            <div class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                </svg>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $transactions->credits ?? '0' }}</p>
                            </div>
                        </div>
                        
                        <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Amount</h4>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $transactions->amount, 2 }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h4>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            {{ $transactions->description ?? 'No description provided' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Metadata Card -->
            {{-- @if($transactions->metadata && is_array($transactions->metadata))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Metadata</h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($transactions->metadata as $key => $value)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white break-all">{{ is_array($value) ? json_encode($value) : $value }}</dd>
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Transaction Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0 flex flex-col items-center">
                                <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="h-12 w-0.5 bg-gray-200 dark:bg-gray-700 mt-1"></div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Created</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ timeFormat($transactions->created_at) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Updated</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ timeFormat($transactions->updated_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layout>