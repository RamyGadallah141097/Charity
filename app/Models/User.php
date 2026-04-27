<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded=[];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'attachments' => 'array',
    ];


   public function childrens(){
       return $this->hasMany(Children::class,'user_id');
   }

   public function patient(){
        return $this->hasOne(Patient::class,'user_id')->latestOfMany();
    }

   public function patients(){
        return $this->hasMany(Patient::class,'user_id');
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

    public function beneficiaryCategory()
    {
        return $this->belongsTo(BeneficiaryCategory::class);
    }

    public function subvention(){
        return $this->hasOne(Subvention::class,'user_id');
    }
    
    public function subventions(){
        return $this->hasMany(Subvention::class,'user_id');
    }

    public function caseResearchFiles()
    {
        return $this->hasMany(CaseResearchFile::class);
    }

    public function latestCaseResearchFile()
    {
        return $this->hasOne(CaseResearchFile::class)->latestOfMany();
    }


}
