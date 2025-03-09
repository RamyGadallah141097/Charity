<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [ "donor_id", "donation_type" , "donation_amount" , "asset_id" , "asset_count"];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
