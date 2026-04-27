<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles , HasPermissions;


    // Define the guard name for Spatie Roles
    protected $guard_name = 'admin';

    // Protect against mass assignment issues
    protected $fillable = [
        'name',
        'job_title',
        'phone',
        'national_id',
        'address',
        'governorate_id',
        'center_id',
        'village_id',
        'is_system_user',
        'email',
        'password',
        'image',
        'documents',
        'notes',
    ];

    // Hide sensitive attributes when converting to JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Auto-cast attributes to specific types
    protected $casts = [
        'email_verified_at' => 'datetime',
        'documents' => 'array',
        'is_system_user' => 'boolean',
    ];

    // Encrypt password automatically when setting it
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function socialResearcherProfile()
    {
        return $this->hasOne(SocialResearcher::class);
    }

}
