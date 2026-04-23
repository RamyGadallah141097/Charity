<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'revenue_type_id',
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

    public function revenueType()
    {
        return $this->belongsTo(RevenueType::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
