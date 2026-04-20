<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSubscription extends Model
{
    protected $fillable = ["admin_id" , "months_count" , "amount"];

    use HasFactory;

    protected $casts = [
        'months_count' => 'int',
        'amount' => 'float',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class , "admin_id");
    }
}
