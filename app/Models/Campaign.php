<?php

namespace App\Models;

use App\Models\BaseModel;

class Campaign extends BaseModel
{

    protected $fillable = [
        'user_urn',
        'music_id',
        'music_type',
        'title',
        'description',
        'target_reposts',
        'completed_reposts',
        'credits_per_repost',
        'total_credits_budget',
        'credits_spent',
        'min_followers_required',
        'max_followers_limit',
        'status',
        'start_date',
        'end_date',
        'auto_approve',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function music()
    {
        return $this->morphTo();
    }

    /**
     * Set the appends property for the model.
     *
     * @param  array  $attributes
     */
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
        return $this->where('status', '!=', self::STATUS_CANCELLED, )->where('status', '!=', self::STATUS_PAUSED, );
    }
}
