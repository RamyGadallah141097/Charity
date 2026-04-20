<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationUnit extends Model
{
    use HasFactory, HasActiveScope;

    protected $guarded = [];

    public function donationCategory()
    {
        return $this->belongsTo(DonationCategory::class);
    }

    public function donationType()
    {
        return $this->belongsTo(DonationType::class);
    }
}
