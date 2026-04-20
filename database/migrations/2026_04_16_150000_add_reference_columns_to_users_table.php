<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('governorate_id')->nullable()->after('address')->constrained('governorates')->nullOnDelete();
            $table->foreignId('center_id')->nullable()->after('governorate_id')->constrained('centers')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->after('center_id')->constrained('villages')->nullOnDelete();
            $table->foreignId('beneficiary_category_id')->nullable()->after('nearest_phone')->constrained('beneficiary_categories')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('beneficiary_category_id');
            $table->dropConstrainedForeignId('village_id');
            $table->dropConstrainedForeignId('center_id');
            $table->dropConstrainedForeignId('governorate_id');
        });
    }
};
