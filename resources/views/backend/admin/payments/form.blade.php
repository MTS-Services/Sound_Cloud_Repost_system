<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Payment - Stripe Gateway</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="max-w-xl w-full mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white text-center p-8">
                <h1 class="text-xl font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-credit-card"></i> Secure Payment
                </h1>
                <p class="opacity-90">Complete your transaction safely and securely</p>
            </div>

            <div class="p-8">
                <!-- Alerts -->
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                <!-- Payment Form -->
                <form id="payment-form">
                    <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">

                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        Total Payment: ${{ number_format($order->amount, 2) }}
                    </h2>

                    @if ($order->source_type === 'App\Models\Credit' || $order->credits)
                        <h3 class="text-lg font-semibold text-gray-600 mb-4">
                            Credits to receive: {{ $order->credits }}
                        </h3>
                    @endif

                    @if ($order->source_type === 'App\Models\Plan')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                This is a subscription payment. Your plan will auto-renew unless cancelled.
                            </p>
                        </div>
                    @endif

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-user mr-1"></i> Full Name
                        </label>
                        <input type="text" id="name" name="name" required placeholder="Enter your full name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>

                    <!-- Email and Phone -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <div class="w-full">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-envelope mr-1"></i> Email Address
                            </label>
                            <input type="email" id="email_address" name="email_address" required
                                placeholder="your@email.com"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                        <div class="w-full">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-phone mr-1"></i> Phone (Optional)
                            </label>
                            <input type="tel" id="customer_phone" name="customer_phone"
                                placeholder="+1 (555) 123-4567"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                    </div>

                    <!-- Card Element -->
                    <div class="mb-4 bg-gray-100 border border-gray-300 rounded-lg p-4">
                        <h3 class="text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-credit-card text-orange-600"></i> Payment Information
                        </h3>
                        <div id="card-element" class="p-3 border border-gray-300 rounded-lg bg-white"></div>
                        <div id="card-errors" role="alert" class="mt-2 text-sm text-red-600"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-button"
                        class="w-full bg-gradient-to-br from-orange-500 to-orange-600 text-white font-semibold py-3 rounded-xl hover:shadow-lg transition-all">
                        <div id="loading-spinner"
                            class="hidden w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin inline-block mr-2">
                        </div>
                        <span id="button-text"><i class="fas fa-lock mr-1"></i> Complete Secure Payment</span>
                    </button>

                    <!-- Payment Logos -->
                    <div class="flex justify-center gap-4 mt-6 opacity-70">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa"
                            class="h-8">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                            alt="Mastercard" class="h-8">
                    </div>

                    <!-- Security Note -->
                    <div
                        class="mt-6 text-center text-sm bg-green-50 border border-green-300 text-green-700 p-4 rounded-lg">
                        <p><i class="fas fa-shield-alt mr-1"></i> Your payment information is encrypted and secure.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();

        // Create card element
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#2d3748',
                    '::placeholder': {
                        color: '#a0aec0'
                    }
                },
                invalid: {
                    color: '#e53e3e'
                }
            },
            hidePostalCode: false
        });
        cardElement.mount('#card-element');

        // Handle card validation errors
        cardElement.on('change', ({
            error
        }) => {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = error ? error.message : '';
        });

        // Form elements
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const loadingSpinner = document.getElementById('loading-spinner');

        // Handle form submission
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            setLoadingState(true);

            const formData = new FormData(form);

            try {
                // Step 1: Create payment/setup intent
                console.log('Creating payment intent...');
                const response = await fetch('{{ route('user.payment.create-intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: formData.get('name'),
                        email_address: formData.get('email_address'),
                        customer_phone: formData.get('customer_phone'),
                        order_id: formData.get('order_id')
                    })
                });

                const data = await response.json();
                // console.log('Server response:', data);

                if (data.error) {
                    throw new Error(data.error);
                }

                const billingDetails = {
                    name: formData.get('name'),
                    email: formData.get('email_address'),
                    phone: formData.get('customer_phone')
                };

                // Step 2: Handle subscription or one-time payment
                if (data.requires_setup) {
                    // SUBSCRIPTION FLOW
                    console.log('Confirming setup intent for subscription...');

                    const setupResult = await stripe.confirmCardSetup(data.client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: billingDetails
                        }
                    });

                    if (setupResult.error) {
                        throw new Error(setupResult.error.message);
                    }

                    console.log('Setup confirmed, creating subscription...');

                    // Step 3: Create subscription with confirmed payment method
                    const subResponse = await fetch('{{ route('user.payment.create-subscription') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            setup_intent_id: setupResult.setupIntent.id,
                            payment_method_id: setupResult.setupIntent.payment_method,
                            customer_id: data.customer_id,
                            price_id: data.price_id,
                            order_id: formData.get('order_id')
                        })
                    });

                    const subData = await subResponse.json();

                    if (subData.error) {
                        throw new Error(subData.error);
                    }

                    console.log('Subscription created successfully!');
                    window.location.href = '{{ route('user.payment.success') }}?sid=' + subData.subscription_id;

                } else {
                    // ONE-TIME PAYMENT FLOW
                    console.log('Confirming one-time payment...');

                    const paymentResult = await stripe.confirmCardPayment(data.client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: billingDetails
                        }
                    });

                    if (paymentResult.error) {
                        throw new Error(paymentResult.error.message);
                    }

                    console.log('Payment successful!');
                    window.location.href = '{{ route('user.payment.success') }}?pid=' + data.payment_intent_id;
                }

            } catch (error) {
                console.error('Payment error:', error);
                document.getElementById('card-errors').textContent = error.message;
                setLoadingState(false);
            }
        });

        function setLoadingState(loading) {
            submitButton.disabled = loading;
            if (loading) {
                buttonText.style.display = 'none';
                loadingSpinner.classList.remove('hidden');
            } else {
                buttonText.style.display = 'inline';
                loadingSpinner.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
