<x-frontend::layout>
    <x-slot name="title">{{ __('Payment Success') }}</x-slot>
    <x-slot name="breadcrumb">{{ __('Payment Success') }}</x-slot>
    <x-slot name="page_slug">payment</x-slot>

    <div class="flex items-center justify-center h-screen">
        <div
            class="relative max-w-[500px] w-full bg-white rounded-[20px] shadow-[0_20px_40px_rgba(0,0,0,0.1)] mx-auto p-10 text-center overflow-hidden" style="padding: 20px;">

            <!-- Top Gradient Bar -->
            <div class="absolute top-0 left-0 right-0 h-[5px] bg-gradient-to-r from-orange-600 to-orange-600"></div>

            <!-- Confetti -->
            @for ($i = 1; $i <= 9; $i++)
                <div class="confetti absolute w-[10px] h-[10px] bg-orange-600 animate-[confetti-fall_3s_linear_infinite]"
                    style="left: {{ 10 * $i }}%; animation-delay: {{ ($i * 0.3) % 2 }}s; background: {{ ['#00d4aa', '#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#6c5ce7', '#a29bfe', '#fd79a8', '#00b894'][$i - 1] }}">
                </div>
            @endfor

            <!-- Success Icon -->
            <div
                class="w-20 h-20 bg-gradient-to-br from-orange-600 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-8 animate-pulse">
                <i class="fas fa-check text-white text-[35px]"></i>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Payment Successful!</h1>
            <p class="text-base text-gray-500 mb-8 leading-relaxed">
                Thank you for your payment. Your transaction has been completed successfully.
            </p>

            <!-- Payment Details -->
            @if (isset($payment))
                <div class="bg-gray-100 border-l-4 border-orange-600 rounded-xl p-6 mb-8">
                    <h3 class="text-lg text-gray-800 font-semibold mb-5 flex items-center gap-2">
                        <i class="fas fa-receipt"></i> Payment Details
                    </h3>

                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-semibold text-gray-600 flex items-center gap-2">
                            <i class="fas fa-coins"></i> Credits
                        </span>
                        <span class="font-bold text-orange-600 text-lg">
                            {{ number_format($payment->creditTransaction?->credits, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-semibold text-gray-600 flex items-center gap-2">
                            <i class="fa-solid fa-sack-dollar"></i> Amount
                        </span>
                        <span class="font-bold text-orange-600 text-lg">
                            ${{ number_format($payment->amount, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-semibold text-gray-600 flex items-center gap-2">
                            <i class="fas fa-coins"></i> Currency
                        </span>
                        <span class="font-bold text-gray-800">
                            {{ strtoupper($payment->currency) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-semibold text-gray-600 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i> Status
                        </span>
                        <span class="uppercase bg-orange-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-semibold text-gray-600 flex items-center gap-2">
                            <i class="fas fa-hashtag"></i> Transaction ID
                        </span>
                        <span class="font-bold text-gray-800 text-xs font-mono">
                            {{ substr($payment->payment_intent_id, 0, 20) }}...
                        </span>
                    </div>

                    <div class="flex justify-between items-start py-3">
                        <span class="font-semibold text-gray-600 flex items-center gap-2">
                            <i class="fas fa-calendar-alt"></i> Date & Time
                        </span>
                        <span class="text-right text-gray-800 font-bold">
                            {{ $payment->processed_at ? $payment->processed_at->format('M d, Y') : 'N/A' }}
                            <br>
                            <small class="text-sm text-gray-500">
                                {{ $payment->processed_at ? $payment->processed_at->format('h:i A') : '' }}
                            </small>
                        </span>
                    </div>
                </div>
            @endif

            <!-- Email Notice -->
            <div class="bg-orange-600 border border-blue-200 rounded-lg p-4 mb-6 flex items-center gap-3">
                <i class="fas fa-envelope text-white"></i>
                <p class="text-sm text-white m-0">
                    A confirmation email has been sent to your registered email address with the payment receipt.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 mt-6 justify-center">
                <a href="{{ route('user.dashboard') }}"
                    class="flex items-center justify-center gap-2 px-6 py-3 rounded-lg text-white font-semibold bg-gradient-to-br from-orange-600 to-orange-600 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 min-w-[140px]">
                    <i class="fas fa-home"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // Add some interactive effects
            document.addEventListener('DOMContentLoaded', function() {
                // Print receipt functionality (optional)
                function printReceipt() {
                    window.print();
                }

                // Add print button if needed
                const actionButtons = document.querySelector('.action-buttons');
                const printBtn = document.createElement('a');
                printBtn.href = '#';
                printBtn.className = 'btn btn-secondary';
                printBtn.innerHTML = '<i class="fas fa-print"></i> Print Receipt';
                printBtn.onclick = function(e) {
                    e.preventDefault();
                    printReceipt();
                };

                // Uncomment to add print button
                // actionButtons.appendChild(printBtn);
            });
        </script>
    @endpush
</x-frontend::layout>
