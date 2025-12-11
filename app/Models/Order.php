<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;

class Order extends BaseModel
{
    //

    protected $fillable = [
        'sort_order',
        'user_urn',
        'source_id',
        'source_type',
        'order_id',
        'credits',
        'notes',
        'amount',
        'status',
        'type',

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

    public function source(): MorphTo
    {
        return $this->morphTo();
    }


    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'status_label',
            'status_color',
            'type_label',
            'type_color',
        ]);
    }

    // Constants for order status
    public const STATUS_PENDING = 0;
    public const STATUS_COMPLETED = 1;
    public const STATUS_FAILED = 2;
    public const STATUS_REFUNDED = 3;
    public const STATUS_CANCELED = 4;

    public const TYPE_CREDIT = 1;
    public const TYPE_PLAN = 2;

    public static function getTypeList(): array
    {
        return [
            self::TYPE_CREDIT => 'Credit',
            self::TYPE_PLAN => 'Plan',
        ];
    }
    public static function getTypeColorList(): array
    {
        return [
            self::TYPE_CREDIT => 'primary',
            self::TYPE_PLAN => 'info',
        ];
    }

    /**
     * List of status codes with their labels.
     */

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_REFUNDED => 'Refunded',
            self::STATUS_CANCELED => 'Canceled',
        ];
    }

    public function getTypeLabelAttribute()
    {
        return isset($this->type) ? self::getTypeList()[$this->type] : 'Unknown';
    }

    public function getTypeColorAttribute()
    {
        return isset($this->type) ? self::getTypeColorList()[$this->type] : 'primary';
    }
    public static function getStatusColorList(): array
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED => 'secondary',
            self::STATUS_REFUNDED => 'info',
            self::STATUS_CANCELED => 'error',
        ];
    }
    public function getStatusLabelAttribute()
    {
        return isset($this->status) ? self::getStatusList()[$this->status] : 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        return isset($this->status) ? self::getStatusColorList()[$this->status] : 'primary';
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
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
    public function scopeTypeCredit(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    public function scopeTypePlan(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_PLAN);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(CreditTransaction::class, 'source');
    }

    public function transaction(): MorphOne
    {
        return $this->morphOne(CreditTransaction::class, 'source');
    }

    public function userPlan(): HasOne
    {
        return $this->hasOne(UserPlan::class, 'order_id', 'id');
    }


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->status == self::STATUS_COMPLETED) {
                if ($model->transaction) {
                    $model->transaction?->update([
                        'status' => CreditTransaction::STATUS_SUCCEEDED
                    ]);
                }

                if ($model->userPlan) {
                    UserPlan::where('user_urn', $model->user_urn)->where('status', UserPlan::STATUS_ACTIVE)->update([
                        'status' => UserPlan::STATUS_INACTIVE,
                        'notes' => $model->notes
                    ]);
                    $model->userPlan?->update([
                        'status' => UserPlan::STATUS_ACTIVE,
                        'start_date' => now(),
                        'end_date' => now()->addDays($model->userPlan->duration)
                    ]);
                }
            }
        });

        // When updating
        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status == self::STATUS_COMPLETED) {
                if ($model->transaction) {
                    $model->transaction?->update([
                        'status' => CreditTransaction::STATUS_SUCCEEDED
                    ]);
                }
                if ($model->userPlan) {
                    UserPlan::where('user_urn', $model->user_urn)->where('status', UserPlan::STATUS_ACTIVE)->update([
                        'status' => UserPlan::STATUS_INACTIVE,
                        'notes' => $model->notes
                    ]);
                    $model->userPlan?->update([
                        'status' => UserPlan::STATUS_ACTIVE,
                        'start_date' => now(),
                        'end_date' => now()->addDays($model->userPlan->duration)
                    ]);
                }
            }
            if ($model->isDirty('status') && $model->status == self::STATUS_FAILED) {
                if ($model->transaction) {
                    $model->transaction?->update([
                        'status' => CreditTransaction::STATUS_FAILED
                    ]);
                }
            }
            if ($model->isDirty('status') && $model->status == self::STATUS_CANCELED) {
                if ($model->transaction) {
                    $model->transaction?->update([
                        'status' => CreditTransaction::STATUS_CANCELED
                    ]);
                }
                if ($model->userPlan) {
                    $model->userPlan?->update([
                        'status' => UserPlan::STATUS_CANCELED
                    ]);
                }
            }
        });
    }




    /**
     * Get the plan if this order is for a plan
     */
    public function plan()
    {
        if ($this->source_type === Plan::class) {
            return $this->source;
        }
        return null;
    }

    /**
     * Get the credit package if this order is for credits
     */
    public function credit()
    {
        if ($this->source_type === Credit::class) {
            return $this->source;
        }
        return null;
    }

    /**
     * Check if this order is for a plan subscription
     */
    public function isPlanOrder(): bool
    {
        return $this->source_type === Plan::class || $this->type === self::TYPE_PLAN;
    }

    /**
     * Check if this order is for credit purchase
     */
    public function isCreditOrder(): bool
    {
        return $this->source_type === Credit::class || $this->type === self::TYPE_CREDIT;
    }

    /**
     * Accessor to get plan_id for backward compatibility
     */
    public function getPlanIdAttribute(): ?int
    {
        if ($this->source_type === Plan::class) {
            return $this->source_id;
        }
        return null;
    }
}
