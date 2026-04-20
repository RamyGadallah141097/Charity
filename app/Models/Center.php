<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory, HasActiveScope;

    protected $guarded = [];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
