<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends BaseModel
{
    //

    protected $fillable = [
        'user_urn',
        'credits',
        'amount',
        'status',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function creditTransaction(): BelongsTo
    {
        return $this->belongsTo(CreditTransaction::class);
    }


    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            //
        ]);
    }

    // Constants for order status
public const STATUS_PENDING   = 0;
public const STATUS_SUCCESS   = 1;
public const STATUS_CANCELLED = 2;

/**
 * List of status codes with their labels.
 */

    public static function statusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SUCCESS => 'Success',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
    public function getStatusLabelAttribute()
    {
        return self::statusList()[$this->status];
    }

    public function getStatusColorAttribute()
    {
        return $this->status == self::STATUS_PENDING ? 'badge-success' : 'badge-error';
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_PENDING ? self::statusList()[self::STATUS_SUCCESS] : self::statusList()[self::STATUS_CANCELLED];
    }

    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_PENDING ? 'btn-error' : 'btn-success';
    }
    public function getStatusBtnClassAttribute()
    {
        return $this->status == self::STATUS_CANCELLED ? 'btn-error' : 'btn-success';
    }
    


}
