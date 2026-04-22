<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'beneficiary_code')) {
                $table->string('beneficiary_code')->nullable()->unique()->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'beneficiary_code')) {
                $table->dropUnique(['beneficiary_code']);
                $table->dropColumn('beneficiary_code');
            }
        });
    }
};
