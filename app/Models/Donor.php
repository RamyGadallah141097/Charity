<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function donations()
    {
        return $this->belongsTo(Donation::class);
    }
}
