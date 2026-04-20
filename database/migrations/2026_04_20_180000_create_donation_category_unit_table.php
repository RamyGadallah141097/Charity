<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_category_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_category_id')->constrained('donation_categories')->cascadeOnDelete();
            $table->foreignId('donation_unit_id')->constrained('donation_units')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['donation_category_id', 'donation_unit_id'], 'donation_category_unit_unique');
        });

        $existingLinks = DB::table('donation_units')
            ->whereNotNull('donation_category_id')
            ->get(['donation_category_id', 'id']);

        foreach ($existingLinks as $link) {
            DB::table('donation_category_unit')->updateOrInsert(
                [
                    'donation_category_id' => $link->donation_category_id,
                    'donation_unit_id' => $link->id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_category_unit');
    }
};
