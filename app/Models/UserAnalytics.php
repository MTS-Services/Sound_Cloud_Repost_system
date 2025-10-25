<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class UserAnalytics extends BaseModel
{
    use SoftDeletes;

    // Action type constants
    public const TYPE_VIEW = 0;
    public const TYPE_PLAY = 1;
    public const TYPE_LIKE = 2;
    public const TYPE_COMMENT = 3;
    public const TYPE_REPOST = 4;
    public const TYPE_REQUEST = 5;
    public const TYPE_FOLLOW = 6;

    // Type labels for display
    public const TYPE_LABELS = [
        self::TYPE_PLAY => 'Play',
        self::TYPE_REPOST => 'Repost',
        self::TYPE_LIKE => 'Like',
        self::TYPE_COMMENT => 'Comment',
        self::TYPE_VIEW => 'View',
        self::TYPE_REQUEST => 'Request',
        self::TYPE_FOLLOW => 'Follow',
    ];

    // REMOVED: protected $with = ['ownerUser', 'actUser', 'source', 'actionable'];
    // Eager loading should be handled explicitly in the service method (getTopSources) 
    // to avoid conflicts with custom queries.

    protected $fillable = [
        'owner_user_urn',
        'act_user_urn',
        'source_id',
        'source_type',
        'actionable_id',
        'actionable_type',
        'type',
        'ip_address',
        'genre',
        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $hidden = [
        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $casts = [
        'type' => 'integer',
        'actionable_id' => 'integer',
        'creater_id' => 'integer',
        'updater_id' => 'integer',
        'deleter_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
      =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    /**
     * Owner user relationship (track owner)
     */
    public function ownerUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_urn', 'urn')
            ->select(['urn', 'name', 'email']);
    }

    /**
     * Acting user relationship (user who performed the action)
     */
    public function actUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'act_user_urn', 'urn')
            ->select(['urn', 'name', 'email']);
    }

    /**
     * Track relationship
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Polymorphic actionable relationship
     */
    public function actionable(): MorphTo
    {
        return $this->morphTo();
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
      =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */



      

      /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of SCOPES
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    /**
     * Scope for specific owner user
     */
    public function scopeForOwnerUser($query, string $userUrn)
    {
        return $query->where('owner_user_urn', $userUrn);
    }

    /**
     * Scope for specific acting user
     */
    public function scopeForActUser($query, string $userUrn)
    {
        return $query->where('act_user_urn', $userUrn);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);
    }

    /**
     * Scope for specific track
     */
    public function scopeForTrack($query, string $trackUrn)
    {
        return $query->where('track_urn', $trackUrn);
    }

    /**
     * Scope for specific action type
     */
    public function scopeForType($query, int $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for specific genres
     */
    public function scopeForGenres($query, array $genres)
    {
        $filteredGenres = array_filter($genres, function ($genre) {
            return $genre !== 'Any Genre';
        });

        if (!empty($filteredGenres)) {
            return $query->whereIn('genre', $filteredGenres);
        }

        return $query;
    }

    /**
     * Scope for aggregated analytics by type
     */
    public function scopeAggregatedByType($query, $startDate, $endDate)
    {
        return $query->select([
            'type',
            'track_urn',
            DB::raw('COUNT(*) as total_count'),
            DB::raw('COUNT(DISTINCT act_user_urn) as unique_users'),
        ])
            ->dateRange($startDate, $endDate)
            ->groupBy(['type', 'track_urn']);
    }

    /**
     * Scope for daily aggregation
     */
    public function scopeDailyAggregation($query, $startDate, $endDate)
    {
        return $query->select([
            DB::raw('DATE(created_at) as date'),
            'type',
            'track_urn',
            DB::raw('COUNT(*) as total_count'),
            DB::raw('COUNT(DISTINCT act_user_urn) as unique_users'),
        ])
            ->dateRange($startDate, $endDate)
            ->groupBy([DB::raw('DATE(created_at)'), 'type', 'track_urn']);
    }

    public function scopeFollowed($query)
    {
        return $query->where('type', self::TYPE_FOLLOW);
    }
    public function scopeLiked($query)
    {
        return $query->where('type', self::TYPE_LIKE);
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of SCOPES
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */





    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of ACCESSORS & MUTATORS
      =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    /**
     * Get formatted created_at date attribute
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Get type label attribute
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? 'Unknown';
    }

    /**
     * Check if action is engagement type (like, comment, repost)
     */
    public function getIsEngagementAttribute(): bool
    {
        return in_array($this->type, [self::TYPE_LIKE, self::TYPE_COMMENT, self::TYPE_REPOST]);
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of ACCESSORS & MUTATORS
      =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'formatted_date',
            'type_label',
            'is_engagement'
        ]);
    }
}
