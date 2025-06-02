<?php

namespace App\Models;

use App\Models\BaseModel;

class Credit extends BaseModel
{

    protected $fillable = [
        'sort_order',
        'name',
        'price',
        'credits',
        'status',
        'notes',

        'created_by',
        'updated_by',
        'deleted_by',
    ];



  // 👇 Add this line to auto-append status_text
    protected $appends = ['status_text'];

    // 👇 Hide raw status if needed (optional)
    // protected $hidden = ['status'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function scopeDeactive($query)
    {
        return $query->where('status', self::STATUS_DEACTIVE);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // ================= Status Constants =================
    public const STATUS_ACTIVE = 1;
    public const STATUS_DEACTIVE = 0;

    // ================= Appended Attribute =================
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Deactive',
            self::STATUS_DEACTIVE => 'Active',
        };
    }
}
