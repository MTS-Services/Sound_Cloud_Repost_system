<?php

namespace App\Models;

use App\Models\AuthBaseModel;
use Spatie\Permission\Traits\HasRoles;

class Admin extends AuthBaseModel
{
    use HasRoles;
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'email_verified_at',
        'image',
        'description',
        'video',

        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->select(['name', 'id']);
    }
}
