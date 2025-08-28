<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;

class CustomNotificationStatus extends BaseModel
{
    protected $fillable = [
        'user_id',
        'user_type',
        'notification_id',
        'read_at',
    ];
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    // The notification this status belongs to
    public function notification(): BelongsTo
    {
        return $this->belongsTo(CustomNotification::class, 'notification_id');
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'read_at_formated',
        ]);
    }

    public function scopeForCurrentUser(Builder $query): Builder
    {
        return $query->where('user_id', user()->id)
            ->where('user_type', get_class(user()));
    }
    public function scopeForCurrentAdmin(Builder $query): Builder
    {
        return $query->where('user_id', admin()->id)
            ->where('user_type', get_class(admin()));
    }

    public function getReadAtFormattedAttribute()
    {
        return $this->read_at ? timeFormat($this->read_at) : 'N/A';
    }
}
