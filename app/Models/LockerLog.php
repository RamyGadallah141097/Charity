<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockerLog extends Model
{
    use HasFactory;

    protected $fillable = [
        "moneyType",
        "type",
        "admin_id",
        "donation_id",
        "subvention_id",
        "loan_id",
        "comment",
        "amount",
        "asset_id",
        "asset_count",
        "donor_id",
    ];

    protected $casts = [
        'amount' => 'float',
        'asset_count' => 'int',
    ];



    const TYPE_PLUS = 'plus';
    const TYPE_MINUS = 'minus';
    const moneyTypeZakat  ="zakat";
    const moneyTypeSadaka  ="sadaka";
    const moneyTypeLoans  ="loan";
    const moneyTypeSubvention ="subvention";


    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
