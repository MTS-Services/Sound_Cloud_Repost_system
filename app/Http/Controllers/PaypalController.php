<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Payment; // Assuming you have a Payment model
use Illuminate\Support\Facades\Log;


class PaypalController extends Controller
{
    public function paypalPaymentLink()
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Create order data
        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('paypal.paymentSuccess'),
                'cancel_url' => route('paypal.paymentCancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => '100' // This should be dynamic based on the transaction
                    ]
                ]
            ]
        ];

        // Create the order
        $order = $provider->createOrder($data);

        // Get the approval link from PayPal response
        $url = collect($order['links'])->Where('rel', 'approve')->first()['href'];

        return redirect()->away($url);
    }
    public function paypalPaymentSuccess(Request $request)
    {
        $token = $request->token;

        try {
            // Initialize PayPal client
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            // Capture the payment order
            $order = $provider->capturePaymentOrder($token);

            // Log the full PayPal response for debugging
            Log::info('PayPal Order Response:', $order);

            // Check if the order status is 'COMPLETED'
            if (isset($order['status']) && $order['status'] == 'COMPLETED') {

                // Ensure that purchase_units exists and contains data
                $purchaseUnit = $order['purchase_units'][0] ?? null;
                if ($purchaseUnit) {
                    // Access the amount from captures
                    $capture = $purchaseUnit['payments']['captures'][0] ?? null;
                    if ($capture) {
                        $amount = $capture['amount']['value'] ?? null;
                        $currency = $capture['amount']['currency_code'] ?? 'USD';
                        $paymentProviderId = $order['id'] ?? null;
                        $receiptUrl = $purchaseUnit['payments']['captures'][0]['links'][0]['href'] ?? null;

                        // Ensure required data is available
                        if (!$amount || !$paymentProviderId) {
                            Log::error('PayPal Payment Error: Missing amount or paymentProviderId in response', ['order' => $order]);
                            session()->flash('error', "Payment details are missing.");
                            return redirect(route('user.add-credits'));
                        }

                        // Capture additional user information
                        $payer = $order['payer'] ?? null;
                        $name = $payer['name']['given_name'] . ' ' . $payer['name']['surname'] ?? null;
                        $email = $payer['email_address'] ?? null;

                        // user address from the purchase unit
                        $userAddress = $purchaseUnit['shipping']['address'] ?? null;
                        $address = $userAddress['address_line_1'] ?? null;
                        $postalCode = $userAddress['postal_code'] ?? null;

                        // Store the payment in the database
                        $payment = Payment::create([
                            'user_urn' => user()->id, // Get the authenticated user's ID
                            'payment_method' => 'PayPal',
                            'payment_gateway' => Payment::PAYMENT_METHOD_PAYPAL, // PayPal gateway identifier
                            'payment_provider_id' => $paymentProviderId,
                            'amount' => $amount,
                            'currency' => $currency,
                            'credits_purchased' => $amount, // Set credits_purchased to the same value as amount
                            'status' => 'succeeded',
                            'payment_intent_id' => $paymentProviderId,
                            'receipt_url' => $receiptUrl,
                            'name' => $name, // Store the name
                            'email_address' => $email, // Store the email address
                            'address' => $address, // Store the address
                            'postal_code' => $postalCode, // Store the postal code
                            'metadata' => json_encode($order), // Store order metadata if needed
                            'processed_at' => now(),
                        ]);

                        // Flash success message and redirect to the add credits page
                        session()->flash('success', "Payment was successful!");
                        return redirect(route('user.add-credits'));
                    } else {
                        Log::error('PayPal Payment Error: No captures found in purchase_units', ['order' => $order]);
                        session()->flash('error', "Payment details are missing.");
                        return redirect(route('user.add-credits'));
                    }
                } else {
                    Log::error('PayPal Payment Error: purchase_units is missing', ['order' => $order]);
                    session()->flash('error', "Payment details are missing.");
                    return redirect(route('user.add-credits'));
                }
            }

            // If payment is not successful or data is missing
            Log::error('PayPal Payment Error: Payment status is not completed', ['order' => $order]);
            session()->flash('error', "Payment failed. Please try again.");
            return redirect(route('user.add-credits'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('PayPal Payment Error: ' . $e->getMessage(), ['exception' => $e]);

            // Flash error message
            session()->flash('error', "An error occurred while processing the payment.");
            return redirect(route('user.add-credits'));
        }
    }





    public function paypalPaymentCancel(Request $request)
    {
        // Show cancellation message to the user
        session()->flash('error', "Payment was canceled.");
        return redirect(route('user.add-credits'));
    }
}
