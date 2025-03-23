<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
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
            $table->integer("adminSubscription")->nullable();
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('logo');
            $table->string('vat_number');
            $table->string('address');
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
        Schema::table('settings', function (Blueprint $table) {
            $table->integer("maxSubvention");
            $table->integer("maxLoan");
            $table->string("branch");
            $table->string("section");
            $table->string("sub_address");
            $table->integer("adminSubscription");
        });
    }
}


// if error
//CREATE TABLE `settings` (
//`id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//    `title` VARCHAR(255) NOT NULL,
//    `logo` VARCHAR(255) NOT NULL,
//    `vat_number` VARCHAR(255) NOT NULL,
//    `address` VARCHAR(255) NOT NULL,
//    `maxSubvention` INT NULL,
//    `maxLoan` INT NULL,
//    `branch` VARCHAR(255) NULL,
//    `section` VARCHAR(255) NULL,
//    `sub_address` VARCHAR(255) NULL,
//    `adminSubscription` INT NULL,
//    `created_at` TIMESTAMP NULL DEFAULT NULL,
//    `updated_at` TIMESTAMP NULL DEFAULT NULL
//) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
