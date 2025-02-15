<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('husband_name');
            $table->string('wife_name')->nullable();
            $table->date('husband_birthday');
            $table->date('wife_birthday')->nullable();
            $table->enum('status',['new','preparing','accepted','refused'])->default('new');
            $table->enum('social_status',['single','married','divorced','widow'])->default('married');
            $table->string('nearest_phone');
            $table->string('work_type');
            $table->text('address');
            $table->double('salary')->nullable()->default(0);
            $table->double('pension')->nullable()->default(0);
            $table->double('insurance')->nullable()->default(0);
            $table->double('dignity')->nullable()->default(0);
            $table->double('trade')->nullable()->default(0);
            $table->double('pillows')->nullable()->default(0);
            $table->double('other')->nullable()->default(0);
            $table->double('gross_income')->nullable()->default(0);
            $table->double('rent')->nullable()->default(0);
            $table->double('gas')->nullable()->default(0);
            $table->double('debt')->nullable()->default(0);
            $table->double('water')->nullable()->default(0);
            $table->double('treatment')->nullable()->default(0);
            $table->double('electricity')->nullable()->default(0);
            $table->double('association')->nullable()->default(0);
            $table->double('food')->nullable()->default(0);
            $table->double('study')->nullable()->default(0);
            $table->double('total_expenses')->nullable()->default(0);
            $table->enum('has_property',['0','1'])->default(0)->comment("0 means he hasn't any property");
            $table->enum('has_savings_book',['0','1'])->default(0)->comment("0 means he hasn't a saving book");
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
}
