<?php

namespace App\Models;

use App\Models\BaseModel;

class UserCredits extends BaseModel
{
    protected $table = 'user_credits';

    protected $fillable = [
        'sort_order' => 'integer',
        'user_urn' => 'string',
        'amount' => 'decimal:10,2',
    ];
    protected $casts = [
        'expires_in' => 'integer',
        'email_verified_at' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
