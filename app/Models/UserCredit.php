<?php

namespace App\Models;

use App\Models\BaseModel;

class UserCredit extends BaseModel
{
    protected $fillable = [
        'sort_order',
        'user_urn',
        'transaction_id',
        'status',
        'amount',
        'credits',

        'creater_id',
        'creater_type',
        'updater_id',
        'updater_type',
        'deleter_id',
        'deleter_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function transaction()
    {
        return $this->belongsTo(CreditTransaction::class, 'transaction_id');
    }


    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_REJECTED = 2;

    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusList()[$this->status];
    }
}
