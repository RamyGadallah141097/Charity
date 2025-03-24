<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToLockerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locker_logs', function (Blueprint $table) {
            $table->unsignedBigInteger("donation_id")->nullable()->after("admin_id");
            $table->foreign("donation_id")->references("id")->on("donations")->cascadeOnDelete();

            $table->unsignedBigInteger("subvention_id")->nullable()->after("donation_id");
            $table->foreign("subvention_id")->references("id")->on("subventions")->cascadeOnDelete();

            $table->unsignedBigInteger("loan_id")->nullable()->after("subvention_id");
            $table->foreign("loan_id")->references("id")->on("loans")->cascadeOnDelete();

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
            //
        });
    }
}
