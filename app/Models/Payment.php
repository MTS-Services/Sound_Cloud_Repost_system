<?php

namespace App\Models;

use App\Models\BaseModel;

class Payment extends BaseModel
{
    
    // protected $fillable = [
    //     'payment_intent_id',
    //     'stripe_customer_id',
    //     'amount',
    //     'currency',
    //     'status',
    //     'payment_method',
    //     'metadata',
    //     'paid_at',
    // ];

    // protected $casts = [
    //     'metadata' => 'array',
    //     'paid_at' => 'datetime',
    //     'amount' => 'decimal:2',
    // ];
     protected $fillable = [
        'user_urn',
        'credit_transaction_id',
        'payment_method',
        'payment_gateway',
        'payment_provider_id',
        'amount',
        'currency',
        'credits_purchased',
        'exchange_rate',
        'status',
        'payment_intent_id',
        'receipt_url',
        'failure_reason',
        'metadata',
        'processed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public const STATUS_PENDING = 0;
    public const STATUS_COMPLETED = 1;
    public const STATUS_FAILED = 2;
    public const STATUS_REFUNDED = 3;
    public const STATUS_DISPUTED = 4;

    // ['stripe', 'paypal', 'razorpay', 'bank_transfer']
    public const PAYMENT_METHOD_STRIPE = 0;
    public const PAYMENT_METHOD_PAYPAL = 1;
    public const PAYMENT_METHOD_RAZORPAY = 2;
    public const PAYMENT_METHOD_BANK_TRANSFER = 3;
}
