<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialResearcher extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Admin::class, 'supervisor_admin_id');
    }

    public function caseResearchFiles()
    {
        return $this->hasMany(CaseResearchFile::class);
    }
}
