<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{
    use HasFactory, HasActiveScope;

    protected $guarded = [];

    public function units()
    {
        return $this->belongsToMany(DonationUnit::class, 'donation_category_unit')->withTimestamps();
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'donation_category_id');
    }
}
