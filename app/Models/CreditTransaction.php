<?php

namespace App\Models;

use App\Models\BaseModel;
use Faker\Core\Color;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransaction extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'receiver_urn',
        'sender_urn',
        'calculation_type',
        'source_id',
        'source_type',
        'transaction_type',
        'status',
        'amount',
        'credits',
        'description',
        'metadata',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];



    public const STATUS_PROCESSING = 0;
    public const STATUS_SUCCEEDED = 1;
    public const STATUS_FAILED = 2;
    public const STATUS_REFUNDED = 3;
    public const STATUS_CANCELED = 4;


    protected $casts = [
        'metadata' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'calculation_type_name',
            'calculation_type_color',
            'status_label',
            'status_color',
            'balance',

            'transaction_type_name',
        ]);
    }

    // 👇 Credit balance attribute
    public function getBalanceAttribute()
    {
        $credit = $this->where('receiver_urn', $this->receiver_urn)
            ->where('calculation_type', self::CALCULATION_TYPE_CREDIT)
            ->sum('credits');

        $debit = $this->where('receiver_urn', $this->receiver_urn)
            ->where('calculation_type', self::CALCULATION_TYPE_DEBIT)
            ->sum('credits');

        return $credit - $debit;
    }

    public function getStatusList(): array
    {
        return [
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SUCCEEDED => 'Succeeded',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_REFUNDED => 'Refunded',
            self::STATUS_CANCELED => 'Canceled',
        ];
    }
    public function getStatusClassList(): array
    {
        return [
            self::STATUS_PROCESSING => 'warning',
            self::STATUS_SUCCEEDED => 'success',
            self::STATUS_FAILED => 'secondary',
            self::STATUS_REFUNDED => 'info',
            self::STATUS_CANCELED => 'error',
        ];
    }

    public function getStatusColorAttribute()
    {
        return [
            self::STATUS_SUCCEEDED => 'badge-success',
            self::STATUS_PROCESSING => 'badge-warning',
            self::STATUS_FAILED => 'badge-info',
            self::STATUS_REFUNDED => 'badge-error',
        ][$this->status] ?? 'badge-secondary';
    }

    public function getStatusBtnColorAttribute()
    {
        return [
            self::STATUS_SUCCEEDED => 'btn-success',
            self::STATUS_PROCESSING => 'btn-warning',
            self::STATUS_FAILED => 'btn-info',
            self::STATUS_REFUNDED => 'btn-error',
        ][$this->status] ?? 'btn-secondary';
    }
    public function getStatusLabelAttribute(): string
    {
        return isset($this->status) ? $this->getStatusList()[$this->status] : 'Unknown';
    }




    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                    Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_urn', 'urn');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_urn', 'urn');
    }

    public function source()
    {
        return $this->morphTo();
    }
    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                    End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */




    public const CALCULATION_TYPE_DEBIT = 0; // ADDITION
    public const CALCULATION_TYPE_CREDIT = 1; // SUBTRACTION

    public static function getCalculationTypes()
    {
        return [
            self::CALCULATION_TYPE_DEBIT => 'Debit',
            self::CALCULATION_TYPE_CREDIT => 'Credit',
        ];
    }
    public function getCalculationTypeNameAttribute(): string
    {
        return isset($this->calculation_type) ? self::getCalculationTypes()[$this->calculation_type] : 'Unknown';
    }
    public function getCalculationTypeColorAttribute(): string
    {
        return isset($this->calculation_type) ? [
            self::CALCULATION_TYPE_DEBIT => 'badge-error',
            self::CALCULATION_TYPE_CREDIT => 'badge-success',
        ][$this->calculation_type] : 'badge-secondary';
    }

    // Scope
    public function scopeDebit()
    {
        return $this->where('calculation_type', '=', self::CALCULATION_TYPE_DEBIT);
    }

    public function scopeCredit()
    {
        return $this->where('calculation_type', '=', self::CALCULATION_TYPE_CREDIT);
    }


    public const TYPE_EARN = 0;
    public const TYPE_SPEND = 1;
    public const TYPE_REFUND = 2;
    public const TYPE_PURCHASE = 3;
    public const TYPE_PENALTY = 4;
    public const TYPE_BONUS = 5;
    public const TYPE_MANUAL = 6;

    public static function getTypes()
    {
        return [
            self::TYPE_EARN => 'Earn',
            self::TYPE_SPEND => 'Spend',
            self::TYPE_REFUND => 'Refund',
            self::TYPE_PURCHASE => 'Purchase',
            self::TYPE_PENALTY => 'Penalty',
            self::TYPE_BONUS => 'Bonus',
            self::TYPE_MANUAL => 'Manual',
        ];
    }
    public function getTransactionTypeNameAttribute(): string
    {
        return isset($this->transaction_type) ? self::getTypes()[$this->transaction_type] : 'Unknown';
    }


    #####################################
    ########### Type Scopes #############
    #####################################
    public function scopeEarn(Builder $query): Builder
    {
        return $query->where('transaction_type', self::TYPE_EARN);
    }

    public function scopeSpend(Builder $query): Builder
    {
        return $query->where('transaction_type', self::TYPE_SPEND);
    }

    public function scopeRefund(Builder $query): Builder
    {
        return $query->where('transaction_type', self::TYPE_REFUND);
    }

    public function scopePurchase(Builder $query): Builder
    {
        return $query->where('transaction_type', self::TYPE_PURCHASE);
    }
    public function scopePenalty(Builder $query): Builder
    {
        return $query->where('transaction_type', self::TYPE_PENALTY);
    }
    public function scopeBonus(Builder $query): Builder
    {
        return $query->where('transaction_type', self::TYPE_BONUS);
    }
    public function scopeManual(Builder $query): Builder
    {
        return $query->where('transaction_type', self::TYPE_MANUAL);
    }

    public function scopeProcessing(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }
    public function scopeSucceeded(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SUCCEEDED);
    }
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FAILED);
    }
    public function scopeRefunded(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REFUNDED);
    }
    public function scopeCanceled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CANCELED);
    }

    public function scopeSelf(Builder $query): Builder
    {
        return $query->where('sender_urn', user()->urn)->orWhere('receiver_urn', user()->urn);
    }
    public function scopeWithoutSelf(Builder $query): Builder
    {
        return $query->where('sender_urn', '!=', user()->urn)->orWhere('receiver_urn', '!=', user()->urn);
    }
    public function scopeSelfSend(Builder $query): Builder
    {
        return $query->where('sender_urn', user()->urn);
    }
    public function scopeSelfReceive(Builder $query): Builder
    {
        return $query->where('receiver_urn', user()->urn);
    }
}
