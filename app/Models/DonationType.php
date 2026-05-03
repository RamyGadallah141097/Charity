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

    public const IN_KIND_CODE = 'in_kind';
    public const CASH_CODE = 'cash';
    public const GOOD_LOAN_CODE = 'good_loan';
    public const ASSOCIATION_CODE = 'association';
    public const ZAKAT_MONEY_CODE = 'zakat_money';
    public const SADAQAT_CODE = 'sadaqat';
    public const ORPHAN_SPONSORSHIP_CODE = 'orphan_sponsorship';
    public const ONGOING_CHARITY_CODE = 'ongoing_charity';
    public const QURAN_CODE = 'quran';
    public const ZAKAT_FITR_CODE = 'zakat_fitr';
    public const FEEDING_CODE = 'feeding';
    public const WATER_CODE = 'water';
    public const GENERAL_CHARITY_CODE = 'general_charity';

    public const PROTECTED_CODES = [
        self::CASH_CODE,
        self::GOOD_LOAN_CODE,
        self::ASSOCIATION_CODE,
    ];

    public const DONATION_FORM_CODES = [
        self::ZAKAT_MONEY_CODE,
        self::SADAQAT_CODE,
        self::ORPHAN_SPONSORSHIP_CODE,
        self::ONGOING_CHARITY_CODE,
        self::QURAN_CODE,
        self::GOOD_LOAN_CODE,
        self::ZAKAT_FITR_CODE,
        self::FEEDING_CODE,
        self::WATER_CODE,
        self::GENERAL_CHARITY_CODE,
        self::IN_KIND_CODE,
    ];

    public const LOCKER_CODES = [
        self::ZAKAT_MONEY_CODE,
        self::SADAQAT_CODE,
        self::ORPHAN_SPONSORSHIP_CODE,
        self::ONGOING_CHARITY_CODE,
        self::QURAN_CODE,
        self::GOOD_LOAN_CODE,
        self::ZAKAT_FITR_CODE,
        self::FEEDING_CODE,
        self::WATER_CODE,
        self::GENERAL_CHARITY_CODE,
        self::IN_KIND_CODE,
        self::ASSOCIATION_CODE,
    ];

    public function units()
    {
        return $this->hasMany(DonationUnit::class);
    }

    public function scopeCashLockerTypes(Builder $query): Builder
    {
        return $query->whereIn('code', self::PROTECTED_CODES);
    }

    public function scopeDonationFormTypes(Builder $query): Builder
    {
        return $query->whereIn('code', self::DONATION_FORM_CODES);
    }

    public function scopeLockerTypes(Builder $query): Builder
    {
        return $query->whereIn('code', self::LOCKER_CODES);
    }

    public function isProtectedType(): bool
    {
        return in_array($this->code, self::PROTECTED_CODES, true);
    }

    public function isCashLockerType(): bool
    {
        return in_array($this->code, self::PROTECTED_CODES, true);
    }

    public function isInKindType(): bool
    {
        return $this->code === self::IN_KIND_CODE;
    }

    public function requiresDonationUnitSelection(): bool
    {
        return $this->isInKindType();
    }

    public function donationFormLabel(): string
    {
        return $this->isInKindType() ? $this->name : 'تبرع ' . $this->name;
    }

    public function lockerMoneyType(): ?string
    {
        return match ($this->code) {
            self::CASH_CODE => LockerLog::moneyTypeSadaka,
            self::SADAQAT_CODE => LockerLog::moneyTypeSadaka,
            self::ZAKAT_MONEY_CODE => LockerLog::moneyTypeZakat,
            self::GOOD_LOAN_CODE => LockerLog::moneyTypeLoans,
            self::ASSOCIATION_CODE => LockerLog::moneyTypeAssociation,
            default => null,
        };
    }
}
