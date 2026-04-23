<?php

namespace Database\Seeders;

use App\Models\DonationCategory;
use App\Models\DonationType;
use App\Models\DonationUnit;
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
            ]
        ];

        foreach ($types as $type) {
            DonationType::updateOrCreate(
                ['code' => $type['code']],
                $type + ['updated_at' => $now, 'created_at' => $now]
            );
        }

        $pieceUnit = DonationUnit::updateOrCreate(
            ['code' => 'piece'],
            [
                'name' => 'قطعة',
                'sort_order' => 5,
                'is_active' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $kgUnit = DonationUnit::updateOrCreate(
            ['code' => 'kg'],
            [
                'name' => 'كيلو',
                'sort_order' => 2,
                'is_active' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $categories = [
            ['name' => 'ملابس جديدة', 'code' => 'new_clothes', 'sort_order' => 1, 'unit_ids' => [$pieceUnit->id]],
            ['name' => 'ملابس مستعملة', 'code' => 'used_clothes', 'sort_order' => 2, 'unit_ids' => [$pieceUnit->id]],
            ['name' => 'بطاطين', 'code' => 'blankets', 'sort_order' => 3, 'unit_ids' => [$pieceUnit->id]],
            ['name' => 'لحوم', 'code' => 'meat', 'sort_order' => 4, 'unit_ids' => [$kgUnit->id]],
            ['name' => 'أثاث جديد', 'code' => 'new_furniture', 'sort_order' => 5, 'unit_ids' => [$pieceUnit->id]],
            ['name' => 'أثاث مستعمل', 'code' => 'used_furniture', 'sort_order' => 6, 'unit_ids' => [$pieceUnit->id]],
            ['name' => 'أجهزة كهربائية', 'code' => 'electrical_appliances', 'sort_order' => 7, 'unit_ids' => [$pieceUnit->id]],
        ];

        foreach ($categories as $categoryData) {
            $unitIds = $categoryData['unit_ids'];
            unset($categoryData['unit_ids']);

            $category = DonationCategory::updateOrCreate(
                ['code' => $categoryData['code']],
                $categoryData + [
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );

            $category->units()->syncWithoutDetaching($unitIds);
        }
    }
}
