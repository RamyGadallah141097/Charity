<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subventions', function (Blueprint $table) {
            $table->foreignId('donation_category_id')
                ->nullable()
                ->after('asset_count')
                ->constrained('donation_categories')
                ->nullOnDelete();

            $table->foreignId('donation_unit_id')
                ->nullable()
                ->after('donation_category_id')
                ->constrained('donation_units')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('subventions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('donation_unit_id');
            $table->dropConstrainedForeignId('donation_category_id');
        });
    }
};
