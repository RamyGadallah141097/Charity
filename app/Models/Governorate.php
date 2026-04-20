<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory, HasActiveScope;

    protected $guarded = [];

    public function centers()
    {
        return $this->hasMany(Center::class);
    }
}
