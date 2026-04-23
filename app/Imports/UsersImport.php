<?php

namespace App\Imports;

use App\Models\BeneficiaryCategory;
use App\Models\Center;
use App\Models\Governorate;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $row = collect($row)->map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            });

            if ($row->filter()->isEmpty()) {
                continue;
            }

            $beneficiaryCode = $row->get('beneficiary_code');
            $husbandNationalId = $this->nullableString($row->get('husband_national_id'));
            $wifeNationalId = $this->nullableString($row->get('wife_national_id'));

            $user = User::query()
                ->when($beneficiaryCode, fn ($query) => $query->orWhere('beneficiary_code', $beneficiaryCode))
                ->when($husbandNationalId, fn ($query) => $query->orWhere('husband_national_id', $husbandNationalId))
                ->when($wifeNationalId, fn ($query) => $query->orWhere('wife_national_id', $wifeNationalId))
                ->first();

            $governorate = $this->resolveGovernorate($row->get('governorate'));
            $center = $this->resolveCenter($row->get('center'), $governorate?->id);
            $village = $this->resolveVillage($row->get('village'), $center?->id);
            $category = $this->resolveCategory($row->get('beneficiary_category'));

            $payload = [
                'beneficiary_code' => $beneficiaryCode ?: null,
                'husband_name' => $row->get('husband_name') ?: null,
                'wife_name' => $row->get('wife_name') ?: null,
                'husband_national_id' => $husbandNationalId,
                'wife_national_id' => $wifeNationalId,
                'age_husband' => $this->nullableNumber($row->get('age_husband')),
                'age_wife' => $this->nullableNumber($row->get('age_wife')),
                'social_status' => $this->normalizeSocialStatus($row->get('social_status')),
                'beneficiary_category_id' => $category?->id,
                'governorate_id' => $governorate?->id,
                'center_id' => $center?->id,
                'village_id' => $village?->id,
                'address' => $row->get('address') ?: null,
                'work_type' => $row->get('work_type') ?: null,
                'nearest_phone' => $this->nullableString($row->get('nearest_phone')),
                'salary' => $this->nullableNumber($row->get('salary')),
                'pension' => $this->nullableNumber($row->get('pension')),
                'dignity' => $this->nullableNumber($row->get('dignity')),
                'trade' => $this->nullableNumber($row->get('trade')),
                'pillows' => $this->nullableNumber($row->get('pillows')),
                'other' => $this->nullableNumber($row->get('other')),
                'gross_income' => $this->nullableNumber($row->get('gross_income')),
                'rent' => $this->nullableNumber($row->get('rent')),
                'gas' => $this->nullableNumber($row->get('gas')),
                'water' => $this->nullableNumber($row->get('water')),
                'electricity' => $this->nullableNumber($row->get('electricity')),
                'food' => $this->nullableNumber($row->get('food')),
                'study' => $this->nullableNumber($row->get('study')),
                'medical_expenses' => $this->nullableNumber($row->get('medical_expenses')),
                'association' => $this->nullableNumber($row->get('association')),
                'debt' => $this->nullableNumber($row->get('debt')),
                'gross_expenses' => $this->nullableNumber($row->get('gross_expenses')),
                'standard_living' => $this->nullableNumber($row->get('standard_living')),
                'has_monthly_subvention' => $this->normalizeBoolean($row->get('has_monthly_subvention')),
                'monthly_subvention_amount' => $this->nullableNumber($row->get('monthly_subvention_amount')),
                'Case_evaluation' => $row->get('case_evaluation') ?: null,
                'status' => $this->normalizeStatus($row->get('status')),
            ];

            if ($user) {
                $user->update($payload);
            } else {
                User::create($payload);
            }
        }
    }

    private function resolveGovernorate($value): ?Governorate
    {
        if (blank($value)) {
            return null;
        }

        return Governorate::query()
            ->where('id', $value)
            ->orWhere('name', $value)
            ->first();
    }

    private function resolveCenter($value, $governorateId = null): ?Center
    {
        if (blank($value)) {
            return null;
        }

        return Center::query()
            ->when($governorateId, fn ($query) => $query->where('governorate_id', $governorateId))
            ->where(function ($query) use ($value) {
                $query->where('id', $value)->orWhere('name', $value);
            })
            ->first();
    }

    private function resolveVillage($value, $centerId = null): ?Village
    {
        if (blank($value)) {
            return null;
        }

        return Village::query()
            ->when($centerId, fn ($query) => $query->where('center_id', $centerId))
            ->where(function ($query) use ($value) {
                $query->where('id', $value)->orWhere('name', $value);
            })
            ->first();
    }

    private function resolveCategory($value): ?BeneficiaryCategory
    {
        if (blank($value)) {
            return null;
        }

        return BeneficiaryCategory::query()
            ->where('id', $value)
            ->orWhere('name', $value)
            ->first();
    }

    private function normalizeSocialStatus($value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return match ((string) $value) {
            '0', 'أعزب', 'اعزب' => '0',
            '1', 'متزوج' => '1',
            '2', 'مطلق' => '2',
            '3', 'أرمل', 'ارمل' => '3',
            default => null,
        };
    }

    private function normalizeBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(mb_strtolower((string) $value), ['1', 'true', 'yes', 'نعم'], true);
    }

    private function normalizeStatus($value): string
    {
        return in_array($value, ['new', 'accepted', 'preparing', 'refused'], true) ? $value : 'new';
    }

    private function nullableNumber($value)
    {
        return $value === '' || $value === null ? null : (float) $value;
    }

    private function nullableString($value): ?string
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return trim((string) $value);
    }
}
