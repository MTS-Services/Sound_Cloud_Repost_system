<?php

namespace App\Models;

use App\Models\BaseModel;
use Faker\Core\Color;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransaction extends BaseModel
{
    protected $fillable = [
        'receiver_urn',
        'sender_urn',
        'calculation_type',
        'source_id',
        'source_type',
        'transaction_type',
        'calculation_type',
        'source_id',
        'source_type',
        'amount',
        'credits',
        'description',
        'metadata',
        'status',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    public const STATUS_PENDING = 0;
    public const STATUS_COMPLETED = 1;
    public const STATUS_FAILED = 2;
    public const STATUS_REFUNDED = 3;
    public const STATUS_DISPUTED = 4;


    protected $casts = [
        'metadata' => 'array',
    ];
     public function getAppends()
     {
         return['status_label', 'status_color', 'status_btn_label', 'status_btn_color',
         
        ];


     }
     
     public function getStatusLabelAttribute(): string
     {
         return $this->status == self::STATUS_PENDING ? 'Pending' : 'Completed';
     }
     public function getStatusColorAttribute(): string
     {
         return $this->status == self::STATUS_PENDING ? 'Green' : 'success';
     }
     public function getStatusBtnLabelAttribute(): string
     {
         return $this->status == self::STATUS_PENDING ? 'Pending' : 'Completed' ;
     }
     public function getStatusBtnColorAttribute(): string
     {
         return $this->status == self::STATUS_PENDING ? 'btn-warning' : 'btn-success';
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

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

   
    // public function repostRequest()
    // {
    //     return $this->belongsTo(RepostRequest::class);
    // }

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
            self::CALCULATION_TYPE_DEBIT => 'Debit / Addition',
            self::CALCULATION_TYPE_CREDIT => 'Credit / Subtraction',
        ];
    }

    // Scope 
    public function scopeAddition()
    {
        return $this->where('calculation_type', '=', self::CALCULATION_TYPE_DEBIT);
    }

    public function scopeSubtraction()
    {
        return $this->where('calculation_type', '=', self::CALCULATION_TYPE_CREDIT);
    }

    public function getCalculationTypeNameAttribute(): string
    {
        return self::getCalculationTypes()[$this->calculation_type];
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
        ];
    }
    public function getTypeNameAttribute(): string
    {
        return self::getTypes()[$this->transaction_type];
    }


    #####################################
    ########### Type Scopes #############
    #####################################
    public function scopeEarn()
    {
        return $this->where('transaction_type', '=', self::TYPE_EARN);
    }

    public function scopeSpend()
    {
        return $this->where('transaction_type', '=', self::TYPE_SPEND);
    }

    public function scopeRefund()
    {
        return $this->where('transaction_type', '=', self::TYPE_REFUND);
    }

    public function scopePurchase()
    {
        return $this->where('transaction_type', '=', self::TYPE_PURCHASE);
    }
}
