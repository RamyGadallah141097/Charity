<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    protected $fillable = ['name', 'nationalID', 'phone', 'address', 'job'];
    use HasFactory;


    public function guarantors()
    {
        return $this->hasMany(Guarantor::class , "borrower_id");
    }
}
