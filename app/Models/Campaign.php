<?php

namespace App\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Campaign extends BaseModel
{

    protected $fillable = [
        'user_urn',
        'music_id',
        'music_type',
        'status',
        'title',
        'description',
        'playback_count',
        'completed_reposts',
        'budget_credits',
        'credits_spent',
        'min_followers',
        'max_followers',
        'is_featured',
        'start_date',
        'end_date',
        'refund_credits',
        'pro_feature',
        'momentum_price',
        'commentable',
        'likeable',
        'max_repost_last_24_h',
        'max_repost_per_day',
        'target_genre',
        'like_count',
        'comment_count',
        'follow_count',
        'favorite_count',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'budget_credits' => 'decimal:2',
        'credits_spent' => 'decimal:2',
        'refund_credits' => 'decimal:2',
        'completed_reposts' => 'integer',
        'min_followers' => 'integer',
        'max_followers' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'integer',
        'playback_count' => 'integer',
        'follow_count' => 'integer',
        'like_count' => 'integer',
        'comment_count' => 'integer',
        'favorite_count' => 'integer',
        
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function music(): MorphTo
    {
        return $this->morphTo();
    }

    public function requests(): HasMany
    {
        return $this->hasMany(RepostRequest::class, 'campaign_id', 'id');
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(Repost::class, 'campaign_id', 'id');
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'campaign_id', 'id');
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
            'status_btn_label',
            'status_btn_color',
            'start_date_formatted',
            'end_date_formatted',

            'feature_label',
        ]);
    }

    public const STATUS_OPEN = 1;
    public const STATUS_PAUSED = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_CANCELLED = 4;

    public static function getStatusList(): array
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_PAUSED => 'Paused',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusList()[$this->status] ?? 'Unknown';
    }
    public function getStatusBtnLabelAttribute()
    {
        $status = self::getStatusList()[$this->status];

        if ($status === 'Open') {
            return 'Pause';
        } elseif ($status === 'Paused') {
            return 'Open';
        } else {
            return $status;
        }
    }

    public function getStatusColorAttribute()
    {
        return [
            self::STATUS_OPEN => 'badge-success',
            self::STATUS_PAUSED => 'badge-warning',
            self::STATUS_COMPLETED => 'badge-info',
            self::STATUS_CANCELLED => 'badge-error',
        ][$this->status] ?? 'badge-secondary';
    }

    public function getStatusBtnColorAttribute()
    {
        return [
            self::STATUS_OPEN => 'btn-success',
            self::STATUS_PAUSED => 'btn-warning',
            self::STATUS_COMPLETED => 'btn-info',
            self::STATUS_CANCELLED => 'btn-error',
        ][$this->status] ?? 'btn-secondary';
    }

    public function getStartDateFormattedAttribute()
    {
        return Carbon::parse($this->start_date)->format('d M Y');
    }

    public function getEndDateFormattedAttribute()
    {
        return Carbon::parse($this->end_date)->format('d M Y');
    }
    // active_completed scope
    public function scopeActive_completed()
    {
        return $this->where('status', '!=', self::STATUS_CANCELLED,)->where('status', '!=', self::STATUS_PAUSED);
    }

    public const FEATURED = 1;
    public const NOT_FEATURED = 0;
    public const PRO_FEATURED = 1;
    public const NOT_PRO_FEATURED = 0;

    public static function getFeatureList(): array
    {
        return [
            self::FEATURED => 'Yes',
            self::NOT_FEATURED => 'No',
        ];
    }
    public function getFeatureLabelAttribute()
    {
        return self::getFeatureList()[$this->is_featured] ?? 'Unknown';
    }

    public function scopeSelf(Builder $query): Builder
    {
        return $query->where('user_urn', user()->urn);
    }
    public function scopeWithoutSelf(Builder $query): Builder
    {
        return $query->where('user_urn', '!=', user()->urn);
    }


    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', self::FEATURED);
    }
    public function scopeNotFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', self::NOT_FEATURED);
    }
    public function scopeProFeatured(Builder $query): Builder
    {
        return $query->where('pro_feature', self::PRO_FEATURED);
    }
    public function scopeNotProFeatured(Builder $query): Builder
    {
        return $query->where('pro_feature', self::NOT_PRO_FEATURED);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopePaused(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PAUSED);
    }
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }
}
