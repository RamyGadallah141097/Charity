<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalLoan extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'loan_id', 'borrower_id', 'month'];

    protected $casts = [
        'amount' => 'float',
        'month' => 'int',
    ];

    public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'borrower_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
