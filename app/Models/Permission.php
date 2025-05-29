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
}
