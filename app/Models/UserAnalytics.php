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

    protected $with = ['ownerUser', 'actUser', 'track'];

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
    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class, 'track_urn', 'urn');
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

    /**
     * Get analytics summary for a collection grouped by type
     */
    public static function getAnalyticsSummary($collection): array
    {
        if ($collection->isEmpty()) {
            return [
                'total_views' => 0,
                'total_likes' => 0,
                'total_reposts' => 0,
                'total_comments' => 0,
                'total_requests' => 0,
                'total_followers' => 0,
                'unique_users' => 0,
            ];
        }

        $grouped = $collection->groupBy('type');

        return [
            'total_views' => $grouped->get(self::TYPE_VIEW, collect())->count(),
            'total_likes' => $grouped->get(self::TYPE_LIKE, collect())->count(),
            'total_reposts' => $grouped->get(self::TYPE_REPOST, collect())->count(),
            'total_comments' => $grouped->get(self::TYPE_COMMENT, collect())->count(),
            'total_requests' => $grouped->get(self::TYPE_REQUEST, collect())->count(),
            'total_followers' => $grouped->get(self::TYPE_FOLLOW, collect())->count(),
            'unique_users' => $collection->pluck('act_user_urn')->unique()->count(),
        ];
    }

    /**
     * Check if a specific action already exists for the user and track
     */
    public static function actionExists(string $actUserUrn, string $trackUrn, int $type, string $ipAddress = null): bool
    {
        $query = self::where('act_user_urn', $actUserUrn)
            ->where('track_urn', $trackUrn)
            ->where('type', $type);

        if ($ipAddress) {
            $query->where('ip_address', $ipAddress);
        }

        return $query->exists();
    }

    /**
     * Create or update analytics record
     */
    public static function recordAction(
        string $ownerUserUrn,
        string $actUserUrn,
        string $trackUrn,
        int $type,
        $actionableId = null,
        string $actionableType = null,
        string $ipAddress = null,
        string $genre = null
    ): ?self {
        // For unique actions, check if already exists
        if (in_array($type, [self::TYPE_LIKE, self::TYPE_REPOST, self::TYPE_FOLLOW])) {
            if (self::actionExists($actUserUrn, $trackUrn, $type, $ipAddress)) {
                return null; // Action already recorded
            }
        }

        return self::create([
            'owner_user_urn' => $ownerUserUrn,
            'act_user_urn' => $actUserUrn,
            'track_urn' => $trackUrn,
            'actionable_id' => $actionableId,
            'actionable_type' => $actionableType,
            'type' => $type,
            'ip_address' => $ipAddress,
            'genre' => $genre,
        ]);
    }

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
