<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Payment - Stripe Gateway</title>
    <script src="https://js.stripe.com/v3/" async="async"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- tailwind cdn  --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="max-w-xl w-full mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-center p-8 relative">
                <h1 class="text-xl font-semibold flex items-center justify-center gap-2 relative z-10">
                    <i class="fas fa-credit-card"></i> Secure Payment
                </h1>
                <p class="opacity-90 relative z-10">Complete your transaction safely and securely</p>
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,...')] bg-cover"></div>
            </div>

            <!-- Payment Form -->
            <div class="p-8">
                <!-- Error/Success Messages -->
                @if (session('error'))
                    <div
                        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg flex items-center gap-2 text-sm">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div
                        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg flex items-center gap-2 text-sm">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form id="payment-form">
                    <!-- Customer Information -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <div class="w-full">
                            <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                                <i class="fas fa-user mr-1"></i> Full Name
                            </label>
                            <input type="text" id="name" name="name" required
                                placeholder="Enter your full name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <div class="w-full">
                            <label class="block text-gray-700 text-sm font-semibold mb-2" for="email_address">
                                <i class="fas fa-envelope mr-1"></i> Email Address
                            </label>
                            <input type="email" id="email_address" name="email_address" required
                                placeholder="your@email.com"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                        <div class="w-full">
                            <label class="block text-gray-700 text-sm font-semibold mb-2" for="customer_phone">
                                <i class="fas fa-phone mr-1"></i> Phone (Optional)
                            </label>
                            <input type="tel" id="customer_phone" name="customer_phone"
                                placeholder="+1 (555) 123-4567"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                    </div>

                    <!-- Amount Section -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="amount">
                            <i class="fas fa-dollar-sign mr-1"></i> Credits
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center font-bold text-gray-600">$</span>
                            <input type="credits" id="credits" name="credits" step="0.01" min="0.50" 
                                value="{{ old('credits', $order->credits) }}" placeholder="0.00"
                                class="w-full pl-8 pr-4 py-2 font-semibold text-lg border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                    </div>
                    <!-- Amount Section -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="amount">
                            <i class="fas fa-dollar-sign mr-1"></i> Payment Amount
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center font-bold text-gray-600">$</span>
                            <input type="text" id="amount" name="amount" step="0.01" min="0.50"
                                value="{{ old('amount', $order->amount) }}" placeholder="0.00"
                                class="w-full pl-8 pr-4 py-2 font-semibold text-lg border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>
                    </div>

                    <!-- Currency Selection -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="currency">
                            <i class="fas fa-coins mr-1"></i> Currency
                        </label>
                        <select id="currency" name="currency"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="usd">USD - US Dollar</option>
                            <option value="eur">EUR - Euro</option>
                            <option value="gbp">GBP - British Pound</option>
                            <option value="cad">CAD - Canadian Dollar</option>
                            <option value="aud">AUD - Australian Dollar</option>
                        </select>
                    </div>

                    <!-- Card Information -->
                    <div
                        class="mb-4 bg-gray-100 border border-gray-300 rounded-lg p-4 focus-within:border-indigo-500 focus-within:bg-white">
                        <h3 class="text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-credit-card text-indigo-600"></i> Payment Information
                        </h3>
                        <div id="card-element" class="p-3 border border-gray-300 rounded-lg bg-white"></div>
                        <div id="card-errors" role="alert" class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        </div>
                    </div>

                    <!-- Save Payment Method -->
                    <div
                        class="mb-4 flex items-center gap-2 p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-800 text-sm">
                        <input type="checkbox" id="save_payment_method" name="save_payment_method" class="w-4 h-4">
                        <label for="save_payment_method" class="cursor-pointer">
                            <i class="fas fa-bookmark mr-1"></i> Save this payment method for future purchases
                        </label>
                    </div>

                    <!-- Order Notes -->
                    {{-- <div class="mb-4">
                    <label for="order_notes" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-sticky-note mr-1"></i> Order Notes (Optional)
                    </label>
                    <input type="text" id="order_notes" name="order_notes"
                        placeholder="Any special instructions..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div> --}}

                    <!-- Submit Button -->
                    <button type="submit" id="submit-button"
                        class="w-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-semibold py-3 rounded-xl hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <div id="loading-spinner"
                            class="hidden w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin">
                        </div>
                        <span id="button-text"><i class="fas fa-lock mr-1"></i> Complete Secure Payment</span>
                    </button>

                    <!-- Payment Methods -->
                    <div class="flex justify-center gap-4 mt-6 opacity-70">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" alt="Visa"
                            class="h-8 grayscale hover:grayscale-0 transition">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                            alt="Mastercard" class="h-8 grayscale hover:grayscale-0 transition">
                    </div>

                    <!-- Security Info -->
                    <div
                        class="mt-6 text-center text-sm bg-green-50 border border-green-300 text-green-700 p-4 rounded-lg">
                        <p><i class="fas fa-shield-alt mr-1"></i> Your payment information is encrypted and secure. We
                            never store your card details.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();

        // Custom styling for Stripe Elements
        const style = {
            base: {
                fontSize: '16px',
                color: '#2d3748',
                fontFamily: '"Segoe UI", Tahoma, Geneva, Verdana, sans-serif',
                '::placeholder': {
                    color: '#a0aec0',
                },
                padding: '15px',
            },
            invalid: {
                color: '#e53e3e',
                iconColor: '#e53e3e'
            }
        };

        // Create card element
        const cardElement = elements.create('card', {
            style: style,
            hidePostalCode: false
        });
        cardElement.mount('#card-element');

        // Handle real-time validation errors from the card Element
        cardElement.on('change', ({
            error
        }) => {
            const displayError = document.getElementById('card-errors');
            if (error) {
                displayError.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${error.message}`;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const loadingSpinner = document.getElementById('loading-spinner');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            // Disable submit button and show loading
            setLoadingState(true);

            const formData = new FormData(form);

            try {
                // Validate form
                if (!validateForm(formData)) {
                    setLoadingState(false);
                    return;
                }

                // Create payment intent
                const response = await fetch('{{ route('f.payment.create-intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        amount: parseFloat(formData.get('amount')),
                        credits: formData.get('credits'),
                        currency: formData.get('currency'),
                        name: formData.get('name'),
                        email_address: formData.get('email_address'),
                        customer_phone: formData.get('customer_phone'),
                        save_payment_method: formData.get('save_payment_method') === 'on',
                        // order_notes: formData.get('order_notes')
                    })
                });

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                // Confirm payment with Stripe
                const {
                    error,
                    paymentIntent
                } = await stripe.confirmCardPayment(data.client_secret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: formData.get('name'),
                            email: formData.get('email_address'),
                            phone: formData.get('customer_phone')
                        }
                    }
                });

                if (error) {
                    // Show error to customer
                    showError(error.message);
                } else {
                    // Payment succeeded, redirect to success page
                    window.location.href = '{{ route('f.payment.success') }}?pid=' + data.payment_intent_id;

                }
            } catch (error) {
                showError(error.message);
            }

            setLoadingState(false);
        });

        // Helper functions
        function setLoadingState(loading) {
            submitButton.disabled = loading;
            if (loading) {
                buttonText.style.display = 'none';
                loadingSpinner.style.display = 'block';
            } else {
                buttonText.style.display = 'flex';
                loadingSpinner.style.display = 'none';
            }
        }

        function validateForm(formData) {
            const requiredFields = ['name', 'email_address', 'amount'];

            for (let field of requiredFields) {
                if (!formData.get(field) || formData.get(field).trim() === '') {
                    showError(`Please fill in the ${field.replace('_', ' ')} field.`);
                    return false;
                }
            }

            const amount = parseFloat(formData.get('amount'));
            if (amount < 0.50) {
                showError('Minimum payment amount is $0.50');
                return false;
            }

            const email = formData.get('email_address');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('Please enter a valid email address.');
                return false;
            }

            return true;
        }

        function showError(message) {
            const errorDiv = document.getElementById('card-errors');
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;

            // Scroll to error
            errorDiv.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        // Format amount input
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = parseFloat(e.target.value);
            if (!isNaN(value)) {
                e.target.value = value.toFixed(2);
            }
        });

        // Auto-format phone number
        document.getElementById('customer_phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
            }
            e.target.value = value;
        });

        // Real-time email validation
        document.getElementById('email_address').addEventListener('blur', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                e.target.style.borderColor = '#e53e3e';
                showError('Please enter a valid email address.');
            } else {
                e.target.style.borderColor = '#e2e8f0';
                document.getElementById('card-errors').textContent = '';
            }
        });
    </script>
</body>

</html>
