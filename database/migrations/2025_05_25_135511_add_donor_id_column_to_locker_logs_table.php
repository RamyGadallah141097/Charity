<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDonorIdColumnToLockerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locker_logs', function (Blueprint $table) {
            $table->foreignId('donor_id')->nullable()->constrained('donors')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locker_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('donor_id');
        });
    }
}
