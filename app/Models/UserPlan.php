<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPlan extends BaseModel
{
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

        'stripe_subscription_id',
        'paypal_subscription_id',
        'auto_renew',
        'next_billing_date',
        'billing_cycle',
        'canceled_at',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creator_type',
        'updater_type',
        'deleter_type',
    ];

    protected $casts = [
        'auto_renew' => 'boolean',
        'next_billing_date' => 'datetime',
        'canceled_at' => 'datetime',

        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'status_label',
            'status_color',

            'created_at_label',
            'updated_at_label',
            'deleted_at_label',

            'start_date_formatted',
            'end_date_formatted',
            'next_billing_date_formatted',
            'canceled_at_formatted',
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

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusList()[$this->status]
            ?? self::getStatusList()[self::STATUS_INACTIVE];
    }

    public function getStatusColorAttribute(): string
    {
        return 'badge-' . (
            self::getStatusColorList()[$this->status]
            ?? self::getStatusColorList()[self::STATUS_INACTIVE]
        );
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
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now());
            });
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

    public function getStartDateFormattedAttribute(): ?string
    {
        return $this->start_date?->format('M d, Y');
    }

    public function getEndDateFormattedAttribute(): ?string
    {
        return $this->end_date?->format('M d, Y');
    }

    public function getNextBillingDateFormattedAttribute(): ?string
    {
        return $this->next_billing_date?->format('M d, Y');
    }

    public function getCanceledAtFormattedAttribute(): ?string
    {
        return $this->canceled_at?->format('M d, Y');
    }
}
