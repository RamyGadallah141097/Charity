<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSubventionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subventions', function (Blueprint $table) {
            $table->string("asset_id")->nullable();
            $table->integer("asset_count")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subventions', function (Blueprint $table) {
            $table->string("asset_id")->nullable();
            $table->integer("asset_count")->nullable();
        });
    }
}
