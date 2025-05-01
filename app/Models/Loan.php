<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['borrower_id', 'borrower_phone', 'loan_amount', 'loan_date' , "type" , "isStarted"];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    public function Personal_loan()
    {
        return $this->hasMany(PersonalLoan::class);
    }
}
