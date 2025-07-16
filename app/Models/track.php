<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\BaseModel;

class track extends BaseModel
{
    protected $fillable = [
        'user_id',
        'kind',
        'soundcloud_track_id',
        'urn',
        'duration',
        'commentable',
        'comment_count',
        'likes_count',
        'sharing',
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
        'is_public',
        'is_streamable',
        'is_downloadable',
        'is_monetized',
        'release_date',
        'is_promotable',
        'promotion_priority',
        'created_at_soundcloud',
        'last_sync_at'
    ];

    protected $casts = [
        'commentable' => 'boolean',
        'streamable' => 'boolean',
        'user_favorite' => 'boolean',
        'downloadable' => 'boolean',
        'is_public' => 'boolean',
        'is_streamable' => 'boolean',
        'is_downloadable' => 'boolean',
        'is_monetized' => 'boolean',
        'is_promotable' => 'boolean',
        'release_date' => 'date',
        'created_at_soundcloud' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    /* %%%%%%%%% ######## ******** ======== RELATIONSHIPS  ======== ******** ######## %%%%%%%%% */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
