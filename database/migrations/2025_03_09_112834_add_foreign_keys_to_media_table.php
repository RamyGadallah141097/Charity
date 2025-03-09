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
        Schema::table('media', function (Blueprint $table) {
            $table->foreign(['borrower_id'])->references(['id'])->on('borrowers')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['guarantor_id'])->references(['id'])->on('guarantors')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign('media_borrower_id_foreign');
            $table->dropForeign('media_guarantor_id_foreign');
        });
    }
};
