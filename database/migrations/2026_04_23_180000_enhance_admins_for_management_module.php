<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('name');
            $table->string('phone')->nullable()->after('job_title');
            $table->string('national_id', 14)->nullable()->after('phone');
            $table->text('address')->nullable()->after('national_id');
            $table->foreignId('governorate_id')->nullable()->after('address')->constrained('governorates')->nullOnDelete();
            $table->foreignId('center_id')->nullable()->after('governorate_id')->constrained('centers')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->after('center_id')->constrained('villages')->nullOnDelete();
            $table->boolean('is_system_user')->default(true)->after('village_id');
            $table->json('documents')->nullable()->after('image');
            $table->text('notes')->nullable()->after('documents');
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropConstrainedForeignId('village_id');
            $table->dropConstrainedForeignId('center_id');
            $table->dropConstrainedForeignId('governorate_id');
            $table->dropColumn([
                'job_title',
                'phone',
                'national_id',
                'address',
                'is_system_user',
                'documents',
                'notes',
            ]);
        });
    }
};
