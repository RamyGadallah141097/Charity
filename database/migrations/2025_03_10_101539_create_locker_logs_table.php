<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locker_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("admin_id")->constrained("Admins")->cascadeOnDelete();
            $table->enum("moneyType" , ["zakat","sadaka","loan"]);
            $table->integer("amount")->nullable();
            $table->enum("type" , ["plus","minus"]);
            $table->string("comment");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locker_logs');
    }
}
