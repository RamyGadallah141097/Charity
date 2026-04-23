<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subvention extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function asset(){
        return $this->belongsTo(Asset::class,'asset_id');
    }

    public function donationCategory()
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id');
    }

    public function donationUnit()
    {
        return $this->belongsTo(DonationUnit::class, 'donation_unit_id');
    }
}
