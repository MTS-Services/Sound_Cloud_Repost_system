<?php

namespace App\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sort_order',
        'user_urn',
        'duration',
        'label_id',
        'genre',
        'release_day',
        'permalink',
        'permalink_url',
        'release_month',
        'release_year',
        'description',
        'uri',
        'label_name',
        'label',
        'tag_list',
        'track_count',
        'last_modified',
        'license',
        'playlist_type',
        'type',
        'soundcloud_id',
        'soundcloud_urn',
        'downloadable',
        'likes_count',
        'sharing',
        'soundcloud_created_at',
        'release',
        'tags',
        'soundcloud_kind',
        'title',
        'purchase_title',
        'ean',
        'streamable',
        'embeddable_by',
        'artwork_url',
        'purchase_url',
        'tracks_uri',
        'secret_token',
        'secret_uri',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $casts = [
        'downloadable' => 'boolean',
        'streamable' => 'boolean',
        'last_modified' => 'datetime',
        'soundcloud_created_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends = array_merge(parent::getAppends(), [
            'release_month_formatted',
        ]);
    }


    /**
     * Relationship: Playlist belongs to a user (via urn)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function tracks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Track::class,
            PlaylistTrack::class,
            'playlist_urn',
            'urn',
            'soundcloud_urn',
            'track_urn'
        );
    }

    // Month format
    // public function getReleaseMonthFormattedAttribute()
    // {
    //     $months = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    //     if (array_key_exists($this->release_month, $months)) {
    //         return $months[$this->release_month];
    //     }
    //     return 'Invalid';
    // }
    public function getReleaseMonthFormattedAttribute()
    {
        $monthString = str_pad($this->release_month, 2, '0', STR_PAD_LEFT);

        $date = DateTime::createFromFormat('Y-m-d', '2000-' . $monthString . '-01');

        if ($date && $date->format('n') == (int) $this->release_month) {
            return $date->format('M'); // Formats to 'Jan', 'Feb', etc.
        }
        return 'Invalid';
    }

    public function scopeSelf(Builder $query): Builder
    {
        return $query->where('user_urn', user()->urn);
    }
}
