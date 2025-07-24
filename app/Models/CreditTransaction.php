<?php

namespace App\Models;

use App\Models\BaseModel;

class CreditTransaction extends BaseModel
{
    protected $fillable = [
        'receiver_id',
        'sender_id',
        'campaign_id',
        'repost_request_id',
        'transaction_type',
        'amount',
        'credits',
        'balance_before',
        'balance_after',
        'description',
        'metadata',

        
        'creater_id',
        'creater_type',
        'updater_id',
        'updater_type',
        'deleter_id',
        'deleter_type',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'urn');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'urn');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // public function repostRequest()
    // {
    //     return $this->belongsTo(RepostRequest::class);
    // }



    public const TYPE_EARN = 0;
    public const TYPE_SPEND = 1;
    public const TYPE_REFUND = 2;
    public const TYPE_PURCHASE = 3;
    public const TYPE_PENALTY = 4;
    public const TYPE_BONUS = 5;

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
}
