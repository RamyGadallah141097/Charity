<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    
    use HasFactory;

    protected $fillable = ['name', 'nationalID', 'phone', 'address', 'job' , "borrower_age" , "rate" , "review"];

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class, "borrower_id");
    }
    public function media()
    {
        return $this->hasMany(Media::class, "borrower_id");
    }

    public function loans()
    {
        return $this->hasOne(Loan::class, "borrower_id");
    }
}
