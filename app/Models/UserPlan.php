<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPlan extends BaseModel
{
    //

    protected $fillable = [
        'sort_order',
        'user_urn',
        'plan_id',
        'order_id',
        'start_date',
        'end_date',
        'price',
        'status',
        'notes',
        'duration',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creator_type',
        'updater_type',
        'deleter_type',
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'status_label',
            'status_color',
        ]);
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public const STATUS_PENDING = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;
    public const STATUS_CANCELED = 3;
    public const STATUS_EXPIRED = 4;

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }

    public static function getStatusColorList(): array
    {
        return [
            self::STATUS_PENDING => 'info',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_INACTIVE => 'warning',
            self::STATUS_CANCELED => 'error',
            self::STATUS_EXPIRED => 'secondary',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return $this->status
            ? self::getStatusList()[$this->status]
            : self::getStatusList()[self::STATUS_INACTIVE];
    }

    public function getStatusColorAttribute()
    {
        return $this->status
            ? 'badge-' . self::getStatusColorList()[$this->status]
            : 'badge-' . self::getStatusColorList()[self::STATUS_INACTIVE];
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_PENDING
            ? self::getStatusList()[self::STATUS_ACTIVE]
            : self::getStatusList()[self::STATUS_INACTIVE];
    }

    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE
            ? 'btn-error'
            : 'btn-success';
    }

    public function getStatusBtnClassAttribute()
    {
        return $this->status == self::STATUS_INACTIVE
            ? 'btn-error'
            : 'btn-primary';
    }

    /**
     * Full badge HTML ready for Blade
     */
    public function getStatusBadgeAttribute()
    {
        return '<span class="badge ' . $this->status_color . '">' . $this->status_label . '</span>';
    }





    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE)->whereDate('end_date', '>=', now());
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeCanceled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CANCELED);
    }


    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public static function markExpiredAsInactive(): int
    {
        return self::where('status', self::STATUS_ACTIVE)
            ->whereDate('end_date', '<', now())
            ->update(['status' => self::STATUS_INACTIVE]);
    }

}
