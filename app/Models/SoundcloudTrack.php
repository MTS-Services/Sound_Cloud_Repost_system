<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class SoundcloudTrack extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_urn',
        'soundcloud_track_id',
        'title',
        'description',
        'permalink',
        'permalink_url',
        'artwork_url',
        'duration',
        'genre',
        'tag_list',
        'playback_count',
        'likes_count',
        'reposts_count',
        'comment_count',
        'track_data',
        'is_active',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'track_data' => 'array',
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_urn', 'urn', 'id');
    }

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '0:00';
        }

        $minutes = floor($this->duration / 60000);
        $seconds = floor(($this->duration % 60000) / 1000);

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getArtworkUrlAttribute($value): string
    {
        return $value ?: 'https://via.placeholder.com/300x300?text=No+Artwork';
    }
}
