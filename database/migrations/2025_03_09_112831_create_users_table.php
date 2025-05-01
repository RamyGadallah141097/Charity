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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('husband_name')->nullable()->default(null);
            $table->string('wife_name')->nullable()->default(null);
            $table->bigInteger('husband_national_id')->nullable()->default(null);
            $table->bigInteger('wife_national_id')->nullable()->default(null);
            $table->integer('age_husband')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->integer('age_wife')->nullable()->default(null);
            $table->enum('social_status', ['0', '1', '2', '3'])->nullable()->default('1');
            $table->string('work_type')->nullable()->default(null);
            $table->string('nearest_phone')->nullable()->default(null);
            $table->double('salary')->nullable()->default(0);
            $table->double('pension')->nullable()->default(0);
            $table->double('insurance')->nullable()->default(0);
            $table->double('dignity')->nullable()->default(0);
            $table->double('trade')->nullable()->default(0);
            $table->double('pillows')->nullable()->default(0);
            $table->double('other')->nullable()->default(0);
            $table->double('gross_income')->nullable()->default(null);
            $table->double('rent')->nullable()->default(0);
            $table->double('gas')->nullable()->default(0);
            $table->double('debt')->nullable()->default(0);
            $table->double('water')->nullable()->default(0);
            $table->double('treatment')->nullable()->default(0);
            $table->double('electricity')->nullable()->default(0);
            $table->double('association')->nullable()->default(0);
            $table->double('food')->nullable()->default(0);
            $table->double('study')->nullable()->default(0);
            $table->double('gross_expenses')->nullable()->default(null);
            $table->double('standard_living')->nullable()->default(null);
            $table->longText('Case_evaluation')->nullable()->default(null);
            $table->longText('attachments')->nullable()->default(null);
            $table->enum('status', ['new', 'preparing', 'accepted', 'refused'])->default('new');
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
        Schema::dropIfExists('users');
    }
};
