<?php

namespace Database\Seeders;

use App\Models\BeneficiaryCategory;
use App\Models\Center;
use App\Models\DonationType;
use App\Models\Donor;
use App\Models\Governorate;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;

class BeneficiariesAndDonorsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBeneficiaries();
        $this->seedDonors();
    }

    protected function seedBeneficiaries(): void
    {
        $beneficiaryRows = [
            ['husband_name' => 'أحمد محمد علي', 'wife_name' => 'فاطمة حسن عبد الله'],
            ['husband_name' => 'محمود السيد إبراهيم', 'wife_name' => 'سعاد عبد الحميد'],
            ['husband_name' => 'محمد عبد الرحمن', 'wife_name' => 'أمل فتحي'],
            ['husband_name' => 'سيد رجب محمود', 'wife_name' => 'نجلاء محمد'],
            ['husband_name' => 'إبراهيم صابر', 'wife_name' => 'هدى علي'],
            ['husband_name' => 'خالد أنور', 'wife_name' => 'منى عبد التواب'],
            ['husband_name' => 'عبد الله حسن', 'wife_name' => 'رحاب شوقي'],
            ['husband_name' => 'رمضان عطية', 'wife_name' => 'نجوى كامل'],
            ['husband_name' => 'حسن يوسف', 'wife_name' => 'ابتسام فاروق'],
            ['husband_name' => 'طارق عبد الله', 'wife_name' => 'إيمان زكي'],
        ];

        foreach ($beneficiaryRows as $index => $row) {
            $location = $this->randomLocation();
            $category = BeneficiaryCategory::query()->inRandomOrder()->first();
            $salary = 1500 + ($index * 120);
            $pension = $index % 3 === 0 ? 600 : 0;
            $dignity = $index % 2 === 0 ? 450 : 0;
            $trade = $index % 4 === 0 ? 300 : 0;
            $other = $index % 5 === 0 ? 250 : 100;
            $grossIncome = $salary + $pension + $dignity + $trade + $other;
            $rent = 400 + ($index * 40);
            $gas = 120;
            $debt = 200 + ($index * 30);
            $water = 70;
            $electricity = 160 + ($index * 15);
            $association = 100;
            $food = 900 + ($index * 50);
            $study = $index % 2 === 0 ? 250 : 100;
            $medicalExpenses = 150 + ($index * 20);
            $grossExpenses = $rent + $gas + $debt + $water + $electricity + $association + $food + $study + $medicalExpenses;

            User::query()->updateOrCreate(
                ['beneficiary_code' => 'BEN-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'husband_name' => $row['husband_name'],
                    'wife_name' => $row['wife_name'],
                    'husband_national_id' => 29001010000000 + $index,
                    'wife_national_id' => 29101010000000 + $index,
                    'age_husband' => 38 + $index,
                    'age_wife' => 31 + $index,
                    'address' => 'عنوان تفصيلي للمستفيد رقم ' . ($index + 1),
                    'governorate_id' => $location['governorate_id'],
                    'center_id' => $location['center_id'],
                    'village_id' => $location['village_id'],
                    'social_status' => (string) ($index % 4),
                    'work_type' => $index % 2 === 0 ? 'عامل يومية' : 'أعمال حرة',
                    'nearest_phone' => '0100000' . str_pad((string) ($index + 1000), 4, '0', STR_PAD_LEFT),
                    'beneficiary_category_id' => $category?->id,
                    'salary' => $salary,
                    'pension' => $pension,
                    'has_monthly_subvention' => $index % 2 === 0,
                    'monthly_subvention_amount' => $index % 2 === 0 ? 500 + ($index * 50) : 0,
                    'dignity' => $dignity,
                    'trade' => $trade,
                    'pillows' => 0,
                    'other' => $other,
                    'gross_income' => $grossIncome,
                    'rent' => $rent,
                    'gas' => $gas,
                    'debt' => $debt,
                    'water' => $water,
                    'treatment' => $medicalExpenses,
                    'electricity' => $electricity,
                    'association' => $association,
                    'food' => $food,
                    'study' => $study,
                    'medical_expenses' => (string) $medicalExpenses,
                    'gross_expenses' => $grossExpenses,
                    'standard_living' => $grossIncome - $grossExpenses,
                    'Case_evaluation' => 'حالة تجريبية مضافة عبر Seeder للمستفيد رقم ' . ($index + 1),
                    'status' => ['new', 'accepted', 'preparing', 'refused'][$index % 4],
                ]
            );
        }
    }

    protected function seedDonors(): void
    {
        $donorRows = [
            'محمد سامي',
            'أحمد فؤاد',
            'مصطفى كامل',
            'مروة حسن',
            'دعاء إبراهيم',
            'هشام سعد',
            'ياسر عبد العظيم',
            'نهى محمود',
            'شيماء علي',
            'وليد زكريا',
        ];

        $donationTypeIds = DonationType::query()->pluck('id')->all();

        foreach ($donorRows as $index => $name) {
            $location = $this->randomLocation();

            $donor = Donor::query()->updateOrCreate(
                ['phone' => '0110000' . str_pad((string) ($index + 2000), 4, '0', STR_PAD_LEFT)],
                [
                    'name' => $name,
                    'phone_second' => '0120000' . str_pad((string) ($index + 2000), 4, '0', STR_PAD_LEFT),
                    'relative_phone' => '0150000' . str_pad((string) ($index + 2000), 4, '0', STR_PAD_LEFT),
                    'governorate_id' => $location['governorate_id'],
                    'center_id' => $location['center_id'],
                    'village_id' => $location['village_id'],
                    'detailed_address' => 'عنوان تفصيلي للمتبرع رقم ' . ($index + 1),
                    'address' => 'عنوان مختصر للمتبرع رقم ' . ($index + 1),
                    'burn_date' => now()->subYears(25 + $index)->format('Y-m-d'),
                    'notes' => 'متبرع تجريبي مضاف عبر Seeder رقم ' . ($index + 1),
                ]
            );

            if (!empty($donationTypeIds)) {
                shuffle($donationTypeIds);
                $donor->preferredDonationTypes()->sync(array_slice($donationTypeIds, 0, min(2, count($donationTypeIds))));
            }
        }
    }

    protected function randomLocation(): array
    {
        $governorate = Governorate::query()->inRandomOrder()->first();
        $center = null;
        $village = null;

        if ($governorate) {
            $center = Center::query()
                ->where('governorate_id', $governorate->id)
                ->inRandomOrder()
                ->first();
        }

        if ($center) {
            $village = Village::query()
                ->where('center_id', $center->id)
                ->inRandomOrder()
                ->first();
        }

        return [
            'governorate_id' => $governorate?->id,
            'center_id' => $center?->id,
            'village_id' => $village?->id,
        ];
    }
}
