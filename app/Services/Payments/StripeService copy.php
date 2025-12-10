<?php

namespace App\Services\Payments;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Price;
use Stripe\Product;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent (for one-time payments)
     */
    public function createPaymentIntent(array $data): PaymentIntent
    {
        try {
            return PaymentIntent::create([
                'amount' => round($data['amount']) * 100,
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
     * Create or get Stripe customer
     */
    public function createOrGetCustomer(array $data)
    {
        try {
            if (!empty($data['stripe_customer_id'])) {
                try {
                    return Customer::retrieve($data['stripe_customer_id']);
                } catch (ApiErrorException $e) {
                    // Customer not found, create new one
                }
            }

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
     * Create a Stripe Price for recurring billing
     */
    public function createPrice(array $data): Price
    {
        try {
            return Price::create([
                'product' => $data['product_id'],
                'unit_amount' => (int)($data['amount'] * 100),
                'currency' => $data['currency'] ?? 'usd',
                'recurring' => [
                    'interval' => $data['interval'],
                ],
                'metadata' => $data['metadata'] ?? [],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create price: ' . $e->getMessage());
        }
    }

    /**
     * Create a Stripe Product
     */
    public function createProduct(array $data): Product
    {
        try {
            return Product::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'metadata' => $data['metadata'] ?? [],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId, bool $immediately = false): Subscription
    {
        try {
            if ($immediately) {
                return Subscription::cancel($subscriptionId);
            }

            return Subscription::update($subscriptionId, [
                'cancel_at_period_end' => true,
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve a subscription
     */
    public function retrieveSubscription(string $subscriptionId): Subscription
    {
        try {
            return Subscription::retrieve($subscriptionId);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve subscription: ' . $e->getMessage());
        }
    }

    /**
     * Update subscription
     */
    public function updateSubscription(string $subscriptionId, array $data): Subscription
    {
        try {
            return Subscription::update($subscriptionId, $data);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to update subscription: ' . $e->getMessage());
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
