<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->string('phone_second')->nullable()->after('phone');
            $table->string('relative_phone')->nullable()->after('phone_second');
            $table->foreignId('governorate_id')->nullable()->after('relative_phone')->constrained('governorates')->nullOnDelete();
            $table->foreignId('center_id')->nullable()->after('governorate_id')->constrained('centers')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->after('center_id')->constrained('villages')->nullOnDelete();
            $table->text('detailed_address')->nullable()->after('village_id');
        });

        Schema::create('donor_donation_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->cascadeOnDelete();
            $table->foreignId('donation_type_id')->constrained('donation_types')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['donor_id', 'donation_type_id']);
        });

        Schema::create('donor_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('Admins')->nullOnDelete();
            $table->string('event_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('event_date')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donor_histories');
        Schema::dropIfExists('donor_donation_type');

        Schema::table('donors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('village_id');
            $table->dropConstrainedForeignId('center_id');
            $table->dropConstrainedForeignId('governorate_id');
            $table->dropColumn(['phone_second', 'relative_phone', 'detailed_address']);
        });
    }
};
