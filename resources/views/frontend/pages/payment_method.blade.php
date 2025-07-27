<x-frontend::layout>

    <x-slot name="title">Payment Method</x-slot>
    <x-slot name="page_slug">payment-method</x-slot>

    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-gray-800 shadow-md rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-semibold text-center mb-6">Select Payment Method</h2>

            <p class="text-center text-gray-300 mb-6">Please choose your preferred payment option to complete your
                purchase.
            </p>

            <div class="flex flex-col gap-4">
                <!-- PayPal Button -->
                <a href="{{ route('paypal.paymentLink', encrypt($order->id)) }}"
                    class="flex items-center justify-center gap-3 bg-yellow-400 hover:bg-yellow-300 text-black font-medium py-3 rounded transition">
                    <img src="https://www.paypalobjects.com/webstatic/icon/pp258.png" alt="PayPal" class="h-6">
                    Pay with PayPal
                </a>
                <!-- Stripe Button -->
                <button onclick="window.location.href='{{ route('f.payment.form', encrypt($order->id)) }}'"
                    class="flex items-center justify-center gap-3 bg-blue-600 hover:bg-indigo-500 text-white font-medium rounded py-2 transition">
                    <span class="text-2xl"><i class="fab fa-cc-stripe"></i></span>
                    Pay with Stripe
                </button>
            </div>

            <!-- Cancel or Go Back -->
            <div class="mt-6">
                <a href="{{ route('user.add-credits') }}" class="text-gray-200 hover:text-orange-400">Back</a>
            </div>
        </div>
    </div>

</x-frontend::layout>
