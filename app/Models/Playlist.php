<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class  Playlist extends BaseModel
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

   
    /**
     * Relationship: Playlist belongs to a user (via urn)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'id');
    }
   
}