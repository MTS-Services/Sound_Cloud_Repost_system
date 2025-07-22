<?php

namespace App\Models;

use App\Models\BaseModel;

class UserInformation extends BaseModel
{
    protected $fillable = [
        'sort_order',

        'user_urn',
        'first_name',
        'last_name',
        'full_name',
        'username',
        'soundcloud_id',
        'soundcloud_urn',
        'soundcloud_kind',
        'soundcloud_permalink_url',
        'soundcloud_permalink',
        'soundcloud_uri',
        'soundcloud_created_at',
        'soundcloud_last_modified',
        'description',
        'country',
        'city',
        'track_count',
        'public_favorites_count',
        'reposts_count',
        'followers_count',
        'following_count',
        'plan',
        'myspace_name',
        'discogs_name',
        'website_title',
        'website',
        'online',
        'comments_count',
        'like_count',
        'playlist_count',
        'private_playlist_count',
        'private_tracks_count',
        'primary_email_confirmed',
        'local',
        'upload_seconds_left',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',
    ];

    protected $casts = [
        'id' => 'integer',
        'sort_order' => 'integer',
        'user_urn' => 'integer',
        'soundcloud_id' => 'integer',
        'soundcloud_created_at' => 'datetime',
        'soundcloud_last_modified' => 'datetime',
        'track_count' => 'integer',
        'public_favorites_count' => 'integer',
        'reposts_count' => 'integer',
        'followers_count' => 'integer',
        'following_count' => 'integer',
        'online' => 'boolean',
        'comments_count' => 'integer',
        'like_count' => 'integer',
        'playlist_count' => 'integer',
        'private_playlist_count' => 'integer',
        'private_tracks_count' => 'integer',
        'primary_email_confirmed' => 'boolean',
        'upload_seconds_left' => 'integer',
        'creater_id' => 'integer',
        'updater_id' => 'integer',
        'deleter_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    /* %%%%%%%%% ######## ******** ======== RELATIONSHIPS  ======== ******** ######## %%%%%%%%% */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn');
    }
}
