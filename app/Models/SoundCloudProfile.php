<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoundCloudProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'soundcloud_url',
        'description',
        'country',
        'city',
        'genres',
        'is_pro',
        'is_verified',

        'creater_id',
        'updater_id',
        'deleter_id',
        'creater_type',
        'updater_type',
        'deleter_type',

    ];

    protected $casts = [
        'genres' => 'array',
        'is_pro' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
