<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_units', function (Blueprint $table) {
            $table->foreignId('donation_category_id')
                ->nullable()
                ->after('donation_type_id')
                ->constrained('donation_categories')
                ->nullOnDelete();
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('donation_category_id')
                ->nullable()
                ->after('donation_type_id')
                ->constrained('donation_categories')
                ->nullOnDelete();
        });

        $map = [
            'bag' => 'clothes',
            'piece' => 'furniture',
            'kg' => 'meals',
            'carton' => 'meals',
        ];

        foreach ($map as $unitCode => $categoryCode) {
            $categoryId = DB::table('donation_categories')->where('code', $categoryCode)->value('id');

            if ($categoryId) {
                DB::table('donation_units')
                    ->where('code', $unitCode)
                    ->update(['donation_category_id' => $categoryId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('donation_category_id');
        });

        Schema::table('donation_units', function (Blueprint $table) {
            $table->dropConstrainedForeignId('donation_category_id');
        });
    }
};
