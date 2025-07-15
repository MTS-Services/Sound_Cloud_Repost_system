<?php

namespace App\Models;

use App\Models\AuthBaseModel;
use App\Notifications\AdminResetPasswordNotification;
use App\Notifications\AdminVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends AuthBaseModel implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable;

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
     public function sendEmailVerificationNotification()
    {
        $this->notify(new AdminVerifyEmail($this));
    }
    protected $guard = 'admin';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'email_verified_at',
        'image',

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
