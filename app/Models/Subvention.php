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
}
