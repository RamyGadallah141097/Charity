<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_type_id',
        'admin_id',
        'amount',
        'transaction_date',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount' => 'float',
        'transaction_date' => 'date',
    ];

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
