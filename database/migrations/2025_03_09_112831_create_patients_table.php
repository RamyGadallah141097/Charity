<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('patients_user_id_foreign');
            $table->string('patient_name')->nullable();
            $table->text('treatment')->nullable();
            $table->text('treatment_pay_by')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0 means women and 1 means man');
            $table->text('doctor_name')->nullable();
            $table->enum('is_insurance', ['0', '1'])->default('0')->comment('0 means he hasn\'t an insurance');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('patients');
    }
};
