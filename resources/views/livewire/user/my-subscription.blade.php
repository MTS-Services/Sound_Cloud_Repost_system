<section x-data="{
    showCancelModal: @entangle('showCancelModal').live
}">
    <x-slot name="page_slug">my-subscription</x-slot>

    <div class="mx-auto">
        <div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-8">

            {{-- Page Title --}}
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">My Subscription</h2>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="p-4 mb-6 rounded-lg bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ACTIVE SUBSCRIPTION --}}
            @if ($userPlan)

                {{-- Plan Card --}}
                <div class="bg-linear-to-br from-orange-500 to-orange-600 p-6 rounded-2xl text-white shadow-md mb-8">
                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                        <div>
                            <h3 class="text-2xl font-semibold">{{ $userPlan->plan->name }}</h3>
                            <p class="text-sm text-orange-100 mt-1">
                                {{ ucfirst($userPlan->billing_cycle) }} Plan
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-4xl font-bold">${{ number_format($userPlan->price, 2) }}</p>
                            <p class="text-sm text-orange-200">
                                per {{ $userPlan->billing_cycle === 'yearly' ? 'year' : 'month' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Status Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="p-4 rounded-xl bg-gray-100 dark:bg-slate-700">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Status</p>
                        <p class="mt-2">
                            <span
                                class="px-3 py-1 inline-flex rounded-full text-sm font-medium
                                {{ $userPlan->status === \App\Models\UserPlan::STATUS_ACTIVE
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300'
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300' }}">
                                {{ $userPlan->status_label }}
                            </span>
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-100 dark:bg-slate-700">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Started On</p>
                        <p class="mt-2 text-lg text-gray-900 dark:text-white font-semibold">
                            {{ $userPlan->start_date->format('M d, Y') }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-100 dark:bg-slate-700">
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $userPlan->auto_renew ? 'Next Billing' : 'Expires On' }}
                        </p>
                        <p class="mt-2 text-lg text-gray-900 dark:text-white font-semibold">
                            {{ $userPlan->auto_renew ? $userPlan->next_billing_date_formatted : $userPlan->end_date_formatted }}

                        </p>
                    </div>
                </div>

                {{-- Auto Renewal --}}
                @if ($userPlan->auto_renew)
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-6">
                        <div
                            class="p-4 flex-1 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                            <p class="text-blue-900 dark:text-blue-200 font-semibold">
                                Auto-Renew Enabled
                            </p>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                Your subscription will renew on {{ $userPlan?->next_billing_date_formatted ?? '-' }}.
                            </p>
                        </div>

                        <button x-on:click="showCancelModal = true"
                            class="px-4 py-2 font-medium text-sm bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-300 rounded-xl hover:bg-red-100 transition">
                            Cancel Auto-Renew
                        </button>
                    </div>
                @else
                    {{-- Already Cancelled --}}
                    @if ($userPlan->canceled_at)
                        <div
                            class="p-4 mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl">
                            <p class="font-medium text-yellow-900 dark:text-yellow-200">
                                Subscription Cancelled
                            </p>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                Cancelled on {{ $userPlan->canceled_at_formatted ?? '-' }}.
                                Access until {{ $userPlan->end_date_formatted ?? '-' }}.
                            </p>
                        </div>
                    @endif

                    {{-- Renew Button --}}
                    <a href="{{ route('user.plans') }}"
                        class="inline-flex items-center px-5 py-3 bg-orange-600 text-white font-semibold rounded-xl hover:bg-orange-700 transition">
                        Renew Subscription
                    </a>
                @endif
            @else
                {{-- NO SUBSCRIPTION --}}
                <div class="py-16 text-center">
                    <div
                        class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">No Active Subscription</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2 mb-6">Subscribe to unlock premium features.</p>

                    <a href="{{ route('user.plans') }}"
                        class="px-6 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition">
                        View Plans
                    </a>
                </div>
            @endif

            {{-- PAYMENT HISTORY --}}
            <div class="mt-10 border-t pt-8">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Payment History</h3>

                <div class="space-y-4">
                    @forelse($payments as $payment)
                        <div class="p-4 rounded-xl bg-gray-100 dark:bg-slate-700 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $payment->notes ?? 'Payment' }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $payment->processed_at ? $payment->processed_at->format('M d, Y h:i A') : '' }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($payment->amount, 2) }}
                                </p>
                                @if ($payment->receipt_url)
                                    <a href="{{ $payment->receipt_url }}" target="_blank"
                                        class="text-xs text-blue-600 hover:text-blue-800">
                                        View Receipt
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No payment records found.</p>
                    @endforelse
                </div>

                @if ($payments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- CANCEL MODAL --}}

    <div x-show="showCancelModal" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-xl w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Cancel Auto-Renew?
            </h3>

            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                Are you sure you want to cancel auto-renew?
                You will still have access until your current billing period ends.
            </p>

            <div class="flex justify-end mt-6 space-x-3">
                <button x-on:click="showCancelModal = false"
                    class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-slate-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300">
                    Close
                </button>

                <button x-on:click="Livewire.dispatch('confirmCancelSubscription')"
                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</section>
