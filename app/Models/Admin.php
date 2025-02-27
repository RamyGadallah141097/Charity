<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles , HasPermissions;


    // Define the guard name for Spatie Roles
    protected $guard_name = 'admin';

    // Protect against mass assignment issues
    protected $fillable = ['name', 'email', 'password' , "image"];

    // Hide sensitive attributes when converting to JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Auto-cast attributes to specific types
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Encrypt password automatically when setting it
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

}
