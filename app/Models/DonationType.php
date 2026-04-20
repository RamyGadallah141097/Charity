<?php

namespace App\Models;

use App\Models\Concerns\HasActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DonationType extends Model
{
    use HasFactory, HasActiveScope;

    protected $guarded = [];

    public const CASH_CODE = 'cash';
    public const GOOD_LOAN_CODE = 'good_loan';

    public const PROTECTED_CODES = [
        self::CASH_CODE,
        self::GOOD_LOAN_CODE,
    ];

    public function scopeCashLockerTypes(Builder $query): Builder
    {
        return $query->whereIn('code', self::PROTECTED_CODES);
    }

    public function isProtectedType(): bool
    {
        return in_array($this->code, self::PROTECTED_CODES, true);
    }

    public function isCashLockerType(): bool
    {
        return in_array($this->code, self::PROTECTED_CODES, true);
    }

    public function lockerMoneyType(): ?string
    {
        return match ($this->code) {
            self::CASH_CODE => LockerLog::moneyTypeSadaka,
            self::GOOD_LOAN_CODE => LockerLog::moneyTypeLoans,
            default => null,
        };
    }
}
