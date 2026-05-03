<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $timestamp = now();

        $categories = [
            ['name' => 'ملابس جديدة', 'code' => 'new_clothes', 'sort_order' => 5, 'unit_codes' => ['bag']],
            ['name' => 'ملابس مستعملة', 'code' => 'used_clothes', 'sort_order' => 6, 'unit_codes' => ['bag']],
            ['name' => 'شنط', 'code' => 'bags', 'sort_order' => 7, 'unit_codes' => ['piece']],
            ['name' => 'لحوم', 'code' => 'meat', 'sort_order' => 8, 'unit_codes' => ['kg']],
            ['name' => 'أثاث جديد', 'code' => 'new_furniture', 'sort_order' => 9, 'unit_codes' => ['piece']],
            ['name' => 'أثاث مستعمل', 'code' => 'used_furniture', 'sort_order' => 10, 'unit_codes' => ['piece']],
            ['name' => 'بطاطين', 'code' => 'blankets', 'sort_order' => 11, 'unit_codes' => ['piece']],
            ['name' => 'أجهزة كهربائية', 'code' => 'electrical_devices', 'sort_order' => 12, 'unit_codes' => ['piece']],
        ];

        foreach ($categories as $category) {
            $existingCategoryId = DB::table('donation_categories')
                ->where('code', $category['code'])
                ->value('id');

            if ($existingCategoryId) {
                DB::table('donation_categories')
                    ->where('id', $existingCategoryId)
                    ->update([
                        'name' => $category['name'],
                        'sort_order' => $category['sort_order'],
                        'is_active' => true,
                        'updated_at' => $timestamp,
                    ]);
            } else {
                DB::table('donation_categories')->insert([
                    'name' => $category['name'],
                    'code' => $category['code'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

            $categoryId = DB::table('donation_categories')
                ->where('code', $category['code'])
                ->value('id');

            if (! $categoryId) {
                continue;
            }

            foreach ($category['unit_codes'] as $unitCode) {
                $unitId = DB::table('donation_units')
                    ->where('code', $unitCode)
                    ->value('id');

                if (! $unitId) {
                    continue;
                }

                DB::table('donation_category_unit')->updateOrInsert(
                    [
                        'donation_category_id' => $categoryId,
                        'donation_unit_id' => $unitId,
                    ],
                    [
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        $codes = [
            'new_clothes',
            'used_clothes',
            'bags',
            'meat',
            'new_furniture',
            'used_furniture',
            'blankets',
            'electrical_devices',
        ];

        $categoryIds = DB::table('donation_categories')
            ->whereIn('code', $codes)
            ->pluck('id');

        if ($categoryIds->isNotEmpty()) {
            DB::table('donation_category_unit')
                ->whereIn('donation_category_id', $categoryIds)
                ->delete();
        }

        DB::table('donation_categories')
            ->whereIn('code', $codes)
            ->delete();
    }
};
