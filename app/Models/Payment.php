<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'name',
        'email_address',
        'address',
        'postal_code',
        'reference',
        'user_urn',
        'order_id',
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

        'creater_id',
        'updater_id',
        'deleter_id',

        'creater_type',
        'updater_type',
        'deleter_type',

    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'status_label',
            'status_color',
            'payment_gateway_label',
            'payment_gateway_color',
        ]);
    }

    public const STATUS_REQUIRES_PAYMENT_METHOD = "requires_payment_method";
    public const STATUS_REQUIRES_CONFIRMATION = "requires_confirmation";
    public const STATUS_REQUIRES_ACTION = "requires_action";
    public const STATUS_PROCESSING = "processing";
    public const STATUS_SUCCEEDED = "succeeded";
    public const STATUS_CANCELED = "canceled";
    public const STATUS_FAILED = "failed";


    public function getStatusColorList(): array
    {
        return [
            self::STATUS_REQUIRES_PAYMENT_METHOD => 'primary',
            self::STATUS_REQUIRES_CONFIRMATION => 'blue',
            self::STATUS_REQUIRES_ACTION => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_SUCCEEDED => 'success',
            self::STATUS_CANCELED => 'error',
            self::STATUS_FAILED => 'secondary',
        ];
    }
    public function getStatusList(): array
    {
        return [
            self::STATUS_REQUIRES_PAYMENT_METHOD => 'Requires Payment Method',
            self::STATUS_REQUIRES_CONFIRMATION => 'Requires Confirmation',
            self::STATUS_REQUIRES_ACTION => 'Requires Action',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SUCCEEDED => 'Succeeded',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_FAILED => 'Failed',
        ];
    }


    public function getStatusLabelAttribute()
    {
        return isset($this->status) ? $this->getStatusList()[$this->status] : 'Unknown';
    }
    public function getStatusColorAttribute()
    {
        return isset($this->status) ? $this->getStatusColorList()[$this->status] : 'gray';
    }


    public const PAYMENT_GATEWAY_STRIPE = 1;
    public const PAYMENT_GATEWAY_PAYPAL = 2;
    public const PAYMENT_GATEWAY_UNKNOWN = 3;

    public function getPaymentGateways(): array
    {
        return [
            self::PAYMENT_GATEWAY_STRIPE => 'Stripe',
            self::PAYMENT_GATEWAY_PAYPAL => 'PayPal',
            self::PAYMENT_GATEWAY_UNKNOWN => 'Unknown',
        ];
    }
    public function getPaymentGatewayColors(): array
    {
        return [
            self::PAYMENT_GATEWAY_STRIPE => 'blue',
            self::PAYMENT_GATEWAY_PAYPAL => 'yellow',
            self::PAYMENT_GATEWAY_UNKNOWN => 'secondary',
        ];
    }

    public function getPaymentGatewayLabelAttribute()
    {
        return isset($this->payment_gateway) ? $this->getPaymentGateways()[$this->payment_gateway] : 'Unknown';
    }

    public function getPaymentGatewayColorAttribute()
    {
        return isset($this->payment_gateway) ? $this->getPaymentGateways()[$this->payment_gateway] : 'Unknown';
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->status == self::STATUS_SUCCEEDED) {
                if ($model->order) {
                    $model->order?->update([
                        'status' => Order::STATUS_COMPLETED
                    ]);
                }
            }
        });

        // When updating
        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status == self::STATUS_SUCCEEDED) {
                if ($model->order) {
                    $model->order?->update([
                        'status' => Order::STATUS_COMPLETED
                    ]);
                }
            }
            if ($model->isDirty('status') && $model->status == self::STATUS_FAILED) {
                if ($model->order) {
                    $model->order?->update([
                        'status' => Order::STATUS_FAILED
                    ]);
                }
            }
            if ($model->isDirty('status') && $model->status == self::STATUS_CANCELED) {
                if ($model->order) {
                    $model->order?->update([
                        'status' => Order::STATUS_CANCELED
                    ]);
                }
            }
        });
    }

}
