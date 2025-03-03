<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Media extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("path");
            $table->enum("type", [1, 0]); // 0 for $borrowers , // 1 for $guarantors
            $table->foreignId("borrower_id")->nullable()->constrained('borrowers')->cascadeOnDelete();
            $table->foreignId("guarantor_id")->nullable()->constrained('guarantors')->cascadeOnDelete();
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
        //
    }
}
