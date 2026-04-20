<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_units', function (Blueprint $table) {
            $table->foreignId('asset_id')
                ->nullable()
                ->after('donation_type_id')
                ->constrained('assets')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('donation_units', function (Blueprint $table) {
            $table->dropConstrainedForeignId('asset_id');
        });
    }
};
