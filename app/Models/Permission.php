<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{

    use HasFactory, SoftDeletes;

    protected $fillables = [
        // 'sort_order',
        'name',
        'prefix',
        'guard_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'created_at_human',
        'updated_at_human',
        'deleted_at_human',

        'created_at_formatted',
        'updated_at_formatted',
        'deleted_at_formatted',
    ];

    public function creater_admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->select(['name', 'id']);
    }

    public function updater_admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by', 'id')->select(['name', 'id']);
    }

    public function deleter_admin()
    {
        return $this->belongsTo(Admin::class, 'deleted_by', 'id')->select(['name', 'id']);
    }
    // Accessor for created time
    public function getCreatedAtFormattedAttribute()
    {
        return timeFormat($this->created_at);
    }

    // Accessor for updated time
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->created_at != $this->updated_at ? timeFormat($this->updated_at) : 'N/A';
    }

    // Accessor for deleted time
    public function getDeletedAtFormattedAttribute()
    {
        return $this->deleted_at ? timeFormat($this->deleted_at) : 'N/A';
    }

    // Accessor for created time human readable
    public function getCreatedAtHumanAttribute()
    {
        return timeFormatHuman($this->created_at);
    }

    // Accessor for updated time human readable
    public function getUpdatedAtHumanAttribute()
    {
        return $this->created_at != $this->updated_at ? timeFormatHuman($this->updated_at) : 'N/A';
    }

    // Accessor for deleted time human readable
    public function getDeletedAtHumanAttribute()
    {
        return $this->deleted_at ? timeFormatHuman($this->deleted_at) : 'N/A';
    }
}
