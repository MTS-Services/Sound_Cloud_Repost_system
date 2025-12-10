<?php

namespace App\Services\Payments;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripeServiceCopy
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent(array $data): PaymentIntent
    {
        try {
            return PaymentIntent::create([
                'amount' => round($data['amount']) * 100, // Convert to cents
                'currency' => $data['currency'] ?? 'usd',
                'customer' => $data['customer_id'] ?? null,
                'metadata' => $data['metadata'] ?? [],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve a payment intent
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Create a customer
     */
    public function createCustomer(array $data)
    {
        try {
            return Customer::create([
                'email' => $data['email'],
                'name' => $data['name'] ?? null,
                'metadata' => $data['metadata'] ?? [],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create customer: ' . $e->getMessage());
        }
    }

    /**
     * Confirm a payment intent
     */
    public function confirmPaymentIntent(string $paymentIntentId, array $data = [])
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId)->confirm($data);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to confirm payment intent: ' . $e->getMessage());
        }
    }
}
