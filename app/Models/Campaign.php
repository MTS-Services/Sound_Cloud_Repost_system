<?php

namespace App\Models;

use App\Models\BaseModel;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;

class Campaign extends BaseModel
{

    protected $fillable = [
        'user_urn',
        'track_urn',
        'title',
        'description',

        'target_reposts',
        'completed_reposts',
        'cost_per_repost',
        'budget_credits',
        'credits_spent',
        'min_followers',
        'max_followers',

        'start_date',
        'end_date',

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
        'target_reposts' => 'integer',
        'completed_reposts' => 'integer',
        'cost_per_repost' => 'decimal',
        'budget_credits' => 'decimal',
        'credits_spent' => 'decimal',
        'min_followers' => 'integer',
        'max_followers' => 'integer',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function track()
    {
        return $this->belongsTo(Track::class, 'track_urn', 'urn');
    }

    public function requests()
    {
        return $this->hasMany(RepostRequest::class, 'campaign_id', 'id');
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class, 'campaign_id', 'id');
    }

    public function creditTransactions()
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
            // 'status_btn_label',
            'status_btn_color',
            'start_date_formatted',
            'end_date_formatted',
            'auto_approve_label',
            'auto_approve_color',

        ]);
    }
    public const AUTO_APPROVE_NO = 0;
    public const AUTO_APPROVE_YES = 1;

    public static function getAutoApproveList(): array
    {
        return [
            self::AUTO_APPROVE_NO => 'No',
            self::AUTO_APPROVE_YES => 'Yes',
        ];
    }
    public function getAutoApproveLabelAttribute()
    {
        return self::getAutoApproveList()[$this->auto_approve];
    }
    public function getAutoApproveColorAttribute()
    {
        return [
            self::AUTO_APPROVE_NO => 'badge-error',
            self::AUTO_APPROVE_YES => 'badge-success',
        ][$this->auto_approve];
    }

    public const STATUS_OPEN = 1;
    public const STATUS_PAUSED = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_CANCELLED = 4;

    public static function getStatusList(): array
    {
        return [
            self::STATUS_OPEN => 'Active',
            self::STATUS_PAUSED => 'Paused',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusList()[$this->status];
    }
    // public function getStatusBtnLabelAttribute()
    // {
    //     return $this->status == self::STATUS_OPEN ? self::getStatuses()[self::STATUS_PAUSED] : self::getStatuses()[self::STATUS_OPEN];
    // }

    public function getStatusColorAttribute()
    {
        return [
            self::STATUS_OPEN => 'badge-success',
            self::STATUS_PAUSED => 'badge-warning',
            self::STATUS_COMPLETED => 'badge-info',
            self::STATUS_CANCELLED => 'badge-error',
        ][$this->status];
    }

    public function getStatusBtnColorAttribute()
    {
        return [
            self::STATUS_OPEN => 'btn-success',
            self::STATUS_PAUSED => 'btn-warning',
            self::STATUS_COMPLETED => 'btn-info',
            self::STATUS_CANCELLED => 'btn-error',
        ][$this->status];
    }

    public function getStartDateFormattedAttribute()
    {
        return timeFormat($this->start_date);
    }

    public function getEndDateFormattedAttribute()
    {
        return timeFormat($this->end_date);
    }
    // active_completed scope 
    public function scopeActive_completed()
    {
        return $this->where('status', '!=', self::STATUS_CANCELLED,)->where('status', '!=', self::STATUS_PAUSED);
    }

    public const FEATURE_TRUE = 1;
    public const FEATURE_FALSE = 0;

    public static function getFeatureList(): array
    {
        return [
            self::FEATURE_TRUE => 'Yes',
            self::FEATURE_FALSE => 'No',
        ];
    }
    public function getFeatureLabelAttribute()
    {
        return self::getFeatureList()[$this->is_featured];
    }
}
