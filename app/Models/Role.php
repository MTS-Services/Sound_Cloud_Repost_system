<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 'sort_order',
        "name",
        "guard_name",

        "created_by",
        "updated_by",
        "deleted_by",
    ];
}
