<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Track extends BaseModel
{
    protected $fillable = [
        'user_urn',

        'kind',
        'soundcloud_track_id',
        'urn',
        'duration',
        'commentable',
        'comment_count',
        'sharing',
        'tag_list',
        'streamable',
        'embeddable_by',
        'purchase_url',
        'purchase_title',
        'genre',
        'title',
        'description',
        'label_name',
        'release',
        'key_signature',
        'isrc',
        'bpm',
        'release_year',
        'release_month',
        'release_day',
        'license',
        'uri',
        'permalink_url',
        'artwork_url',
        'stream_url',
        'download_url',
        'waveform_url',
        'available_country_codes',
        'secret_uri',
        'user_favorite',
        'user_playback_count',
        'playback_count',
        'download_count',
        'favoritings_count',
        'reposts_count',
        'downloadable',
        'access',
        'policy',
        'monetization_model',
        'metadata_artist',
        'created_at_soundcloud',
        'type',

        'author_username',
        'author_soundcloud_id',
        'author_soundcloud_urn',
        'author_soundcloud_kind',
        'author_soundcloud_permalink_url',
        'author_soundcloud_permalink',
        'author_soundcloud_uri',
        
        'last_sync_at',

        'creater_id',
        'creater_type',
        'updater_id',
        'updater_type',
        'deleter_id',
        'deleter_type',
    ];

    protected $casts = [
        'duration' => 'integer',
        'commentable' => 'boolean',
        'comment_count' => 'integer',
        'likes_count' => 'integer',
        'streamable' => 'boolean',
        'user_favorite' => 'boolean',
        'user_playback_count' => 'integer',
        'playback_count' => 'integer',
        'download_count' => 'integer',
        'favoritings_count' => 'integer',
        'reposts_count' => 'integer',
        'downloadable' => 'boolean',
        'created_at_soundcloud' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                Start of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class, 'playlist_urn', 'urn');
    }

    public function campaigns(): MorphMany
    {
        return $this->morphMany(Campaign::class, 'music');
    }

    public function requests(): MorphMany
    {
        return $this->morphMany(RepostRequest::class, 'track_urn', 'urn');
    }

    /* =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#=
                End of RELATIONSHIPS
     =#=#=#=#=#=#=#=#=#=#==#=#=#=#= =#=#=#=#=#=#=#=#=#=#==#=#=#=#= */


    // Month format
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
