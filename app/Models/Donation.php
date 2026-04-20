<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'received_at',
        'donation_kind',
        'donation_type_id',
        'donation_category_id',
        'donation_type',
        'donation_amount',
        'amount_value',
        'donation_unit_id',
        'receipt_number',
        'received_by_admin_id',
        'donation_month',
        'occasion',
        'asset_id',
        'asset_count',
        'created_at',
    ];

    protected $casts = [
        'received_at' => 'date',
        'created_at' => 'datetime',
        'amount_value' => 'float',
        'donation_amount' => 'float',
        'asset_count' => 'int',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function referenceDonationType()
    {
        return $this->belongsTo(DonationType::class, 'donation_type_id');
    }

    public function unit()
    {
        return $this->belongsTo(DonationUnit::class, 'donation_unit_id');
    }

    public function donationCategory()
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(Admin::class, 'received_by_admin_id');
    }

    public function getDonationKindLabelAttribute()
    {
        return match ($this->donation_kind) {
            'financial' => 'مالية',
            'in_kind' => 'عينية',
            'mixed' => 'مركبة',
            default => 'غير محدد',
        };
    }

    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'يناير',
            2 => 'فبراير',
            3 => 'مارس',
            4 => 'أبريل',
            5 => 'مايو',
            6 => 'يونيو',
            7 => 'يوليو',
            8 => 'أغسطس',
            9 => 'سبتمبر',
            10 => 'أكتوبر',
            11 => 'نوفمبر',
            12 => 'ديسمبر',
        ];

        return $months[(int) $this->donation_month] ?? '--';
    }

    public function getDisplayTypeNameAttribute()
    {
        if ($this->referenceDonationType?->name) {
            return $this->referenceDonationType->name;
        }

        return match ((int) $this->donation_type) {
            0 => 'زكاة مال',
            1 => 'صدقات',
            2 => 'قرض حسن',
            3 => 'تبرع عيني',
            default => 'غير محدد',
        };
    }

    public function getDisplayValueAttribute()
    {
        if (!is_null($this->amount_value)) {
            $value = rtrim(rtrim(number_format((float) $this->amount_value, 2, '.', ''), '0'), '.');
            $unit = optional($this->unit)->name;

            return trim($value . ' ' . $unit);
        }

        if (!is_null($this->asset_count)) {
            return (string) $this->asset_count;
        }

        return (string) ($this->donation_amount ?? '--');
    }

    public function getDisplayUnitNameAttribute(): string
    {
        return optional($this->unit)->name ?? '--';
    }
}
