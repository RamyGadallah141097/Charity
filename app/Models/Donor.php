<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donor extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'burn_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function donation()
    {
        return $this->hasMany(Donation::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function preferredDonationTypes()
    {
        return $this->belongsToMany(DonationType::class, 'donor_donation_type')->withTimestamps();
    }

    public function histories()
    {
        return $this->hasMany(DonorHistory::class)->latest('event_date');
    }

    public function lockerLogs()
    {
        return $this->hasMany(LockerLog::class);
    }

    public function getFullAddressAttribute()
    {
        $parts = [
            optional($this->governorate)->name,
            optional($this->center)->name,
            optional($this->village)->name,
            $this->detailed_address ?: $this->address,
        ];

        return implode(' - ', array_filter($parts, fn ($value) => filled($value)));
    }

    public function getPreferredDonationTypesTextAttribute()
    {
        return $this->preferredDonationTypes->pluck('name')->filter()->implode('، ');
    }

    public function getFirstDonationDateAttribute()
    {
        return optional($this->donation->sortBy('created_at')->first())->created_at;
    }

    public function getLastDonationDateAttribute()
    {
        return optional($this->donation->sortByDesc('created_at')->first())->created_at;
    }

    public function getDonationsCountAttribute()
    {
        return $this->donation->count();
    }

    public function getTotalDonationsAmountAttribute()
    {
        return $this->donation
            ->filter(function ($donation) {
                $unit = $donation->unit;
                $type = $donation->referenceDonationType;

                return $unit
                    && ($unit->code === 'egp' || $unit->name === 'جنيه')
                    && $type
                    && $type->isCashLockerType();
            })
            ->where('donation_type', '!=', 3)
            ->sum(function ($donation) {
                return (float) ($donation->amount_value ?? $donation->donation_amount ?? 0);
            });
    }

    public static function logHistory(int $donorId, string $eventType, string $title, ?string $description = null, array $meta = [], $eventDate = null): void
    {
        DonorHistory::create([
            'donor_id' => $donorId,
            'admin_id' => auth()->id(),
            'event_type' => $eventType,
            'title' => Str::limit($title, 255, ''),
            'description' => $description,
            'meta' => $meta ?: null,
            'event_date' => $eventDate ?? now(),
        ]);
    }
}
