<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_monthly_subvention')->default(false)->after('pension');
            $table->double('monthly_subvention_amount')->nullable()->default(0)->after('has_monthly_subvention');
        });

        DB::table('users')
            ->whereNotNull('insurance')
            ->update([
                'monthly_subvention_amount' => DB::raw('insurance'),
                'has_monthly_subvention' => DB::raw('CASE WHEN insurance > 0 THEN 1 ELSE 0 END'),
            ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('insurance');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('is_insurance');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->double('insurance')->nullable()->default(0)->after('pension');
        });

        DB::table('users')->update([
            'insurance' => DB::raw('COALESCE(monthly_subvention_amount, 0)'),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['has_monthly_subvention', 'monthly_subvention_amount']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->enum('is_insurance', ['0', '1'])->default('0')->comment('0 means he hasn\'t an insurance')->after('doctor_name');
        });
    }
};
