<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class UserAnalytics extends BaseModel
{
    protected $fillable = [
        'user_urn',
        'track_urn',
        'action_id',
        'action_type',
        'date',
        'genre',

        'total_requests',
        'total_plays',
        'total_followers',
        'total_likes',
        'total_reposts',
        'total_comments',
        'total_views',

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
        'total_requests' => 'integer',
        'total_plays' => 'integer',
        'total_followers' => 'integer',
        'total_likes' => 'integer',
        'total_reposts' => 'integer',
        'total_comments' => 'integer',
        'total_views' => 'integer',
        'creater_id' => 'integer',
        'updater_id' => 'integer',
        'deleter_id' => 'integer',
        'date' => 'date',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    /**
     * User relationship with optimized select
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn')
            ->select(['urn', 'name', 'email']);
    }

    /**
     * Track relationship with optimized select for analytics
     */
    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class, 'track_urn', 'urn')
            ->select(['urn', 'title', 'genre', 'created_at', 'user_urn']);
    }

    /**
     * Polymorphic action relationship
     */
    public function action(): MorphTo
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
     * Scope for specific user
     */
    public function scopeForUser($query, string $userUrn)
    {
        return $query->where('user_urn', $userUrn);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate);
    }

    /**
     * Scope for specific track
     */
    public function scopeForTrack($query, string $trackUrn)
    {
        return $query->where('track_urn', $trackUrn);
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
     * Scope for aggregated track analytics
     */
    public function scopeTrackAggregated($query, $startDate, $endDate)
    {
        return $query->select([
            'track_urn',
            DB::raw('SUM(total_views) as total_views'),
            DB::raw('SUM(total_plays) as total_streams'),
            DB::raw('SUM(total_likes) as total_likes'),
            DB::raw('SUM(total_reposts) as total_reposts'),
            DB::raw('SUM(total_comments) as total_comments')
        ])
            ->dateRange($startDate, $endDate)
            ->groupBy('track_urn');
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of SCOPES
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of ACCESSORS & MUTATORS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    /**
     * Get formatted date attribute
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('M d, Y');
    }

    /**
     * Get total engagement (likes + reposts + comments)
     */
    public function getTotalEngagementAttribute(): int
    {
        return $this->total_likes + $this->total_reposts + $this->total_comments;
    }

    /**
     * Get engagement rate percentage
     */
    public function getEngagementRateAttribute(): float
    {
        if ($this->total_views == 0) {
            return 0.0;
        }

        return round(($this->total_engagement / $this->total_views) * 100, 2);
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of ACCESSORS & MUTATORS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    /**
     * Boot method for model events
     */
    public static function boot()
    {
        parent::boot();

        // Auto-set date if not provided
        static::creating(function ($model) {
            if (!$model->date) {
                $model->date = now()->toDateString();
            }
        });
    }

    /**
     * Get metrics summary for a collection
     */
    public static function getMetricsSummary($collection): array
    {
        if ($collection->isEmpty()) {
            return [
                'total_views' => 0,
                'total_plays' => 0,
                'total_likes' => 0,
                'total_reposts' => 0,
                'total_comments' => 0,
                'total_requests' => 0,
                'total_followers' => 0,
            ];
        }

        return [
            'total_views' => $collection->sum('total_views'),
            'total_plays' => $collection->sum('total_plays'),
            'total_likes' => $collection->sum('total_likes'),
            'total_reposts' => $collection->sum('total_reposts'),
            'total_comments' => $collection->sum('total_comments'),
            'total_requests' => $collection->sum('total_requests'),
            'total_followers' => $collection->sum('total_followers'),
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'formatted_date',
            'total_engagement',
            'engagement_rate'
        ]);
    }
}
