<?php

use App\Models\DonationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_units', function (Blueprint $table) {
            $table->foreignId('donation_type_id')
                ->nullable()
                ->after('id')
                ->constrained('donation_types')
                ->nullOnDelete();
        });

        $inKindTypeId = DB::table('donation_types')->where('code', 'in_kind')->value('id');

        if ($inKindTypeId) {
            DB::table('donation_units')
                ->whereNull('donation_type_id')
                ->where('code', '!=', 'egp')
                ->update(['donation_type_id' => $inKindTypeId]);
        }

        $unitsMap = [
            'kg' => 'food',
            'carton' => 'food',
            'bag' => 'in_kind',
            'piece' => 'in_kind',
        ];

        foreach ($unitsMap as $unitCode => $typeCode) {
            $typeId = DB::table('donation_types')->where('code', $typeCode)->value('id');

            if ($typeId) {
                DB::table('donation_units')
                    ->where('code', $unitCode)
                    ->update(['donation_type_id' => $typeId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('donation_units', function (Blueprint $table) {
            $table->dropConstrainedForeignId('donation_type_id');
        });
    }
};
