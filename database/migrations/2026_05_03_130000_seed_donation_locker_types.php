<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $timestamp = now();

        $types = [
            ['name' => 'زكاة مال', 'code' => 'zakat_money', 'sort_order' => 1],
            ['name' => 'صدقات', 'code' => 'sadaqat', 'sort_order' => 2],
            ['name' => 'كفالة أيتام', 'code' => 'orphan_sponsorship', 'sort_order' => 3],
            ['name' => 'صدقة جارية', 'code' => 'ongoing_charity', 'sort_order' => 4],
            ['name' => 'قرآن كريم', 'code' => 'quran', 'sort_order' => 5],
            ['name' => 'قرض حسن', 'code' => 'good_loan', 'sort_order' => 6],
            ['name' => 'زكاة فطر', 'code' => 'zakat_fitr', 'sort_order' => 7],
            ['name' => 'إطعام', 'code' => 'feeding', 'sort_order' => 8],
            ['name' => 'سقيا ماء', 'code' => 'water', 'sort_order' => 9],
            ['name' => 'أوجه الخير', 'code' => 'general_charity', 'sort_order' => 10],
            ['name' => 'تبرع عيني', 'code' => 'in_kind', 'sort_order' => 11],
            ['name' => 'خزنة الجمعية', 'code' => 'association', 'sort_order' => 99],
        ];

        foreach ($types as $type) {
            DB::table('donation_types')->updateOrInsert(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'is_active' => 1,
                    'sort_order' => $type['sort_order'],
                    'updated_at' => $timestamp,
                    'created_at' => $timestamp,
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('donation_types')->whereIn('code', [
            'zakat_money',
            'sadaqat',
            'orphan_sponsorship',
            'ongoing_charity',
            'quran',
            'good_loan',
            'zakat_fitr',
            'feeding',
            'water',
            'general_charity',
        ])->delete();
    }
};
