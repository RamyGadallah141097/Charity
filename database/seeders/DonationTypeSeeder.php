<?php

namespace Database\Seeders;

use App\Models\DonationType;
use Illuminate\Database\Seeder;

class DonationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $types = [
            [
                'name' => 'تبرع مالي',
                'code' => DonationType::CASH_CODE,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'تبرع قرض حسن',
                'code' => DonationType::GOOD_LOAN_CODE,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'تبرع عيني',
                'code' => 'in_kind',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'تبرع غذائي',
                'code' => 'food',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'تبرع طبي',
                'code' => 'medical',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'تبرع تعليمي',
                'code' => 'education',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            DonationType::updateOrCreate(
                ['code' => $type['code']],
                $type + ['updated_at' => $now, 'created_at' => $now]
            );
        }
    }
}
