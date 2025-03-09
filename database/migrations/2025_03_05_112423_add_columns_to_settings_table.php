<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer("maxSubvention")->nullable();
            $table->integer("maxLoan")->nullable();
            $table->string("branch")->nullable();
            $table->string("section")->nullable();
            $table->string("sub_address")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer("maxSubvention")->nullable();
            $table->integer("maxLoan")->nullable();
            $table->string("branch")->nullable();
            $table->string("section")->nullable();
            $table->string("sub_address")->nullable();
        });
    }
}
