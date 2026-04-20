<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->date('received_at')->nullable()->after('donor_id');
            $table->enum('donation_kind', ['financial', 'in_kind', 'mixed'])->default('financial')->after('received_at');
            $table->foreignId('donation_type_id')->nullable()->after('donation_kind')->constrained('donation_types')->nullOnDelete();
            $table->decimal('amount_value', 12, 2)->nullable()->after('donation_type_id');
            $table->foreignId('donation_unit_id')->nullable()->after('amount_value')->constrained('donation_units')->nullOnDelete();
            $table->string('receipt_number')->nullable()->after('donation_unit_id');
            $table->foreignId('received_by_admin_id')->nullable()->after('receipt_number')->constrained('Admins')->nullOnDelete();
            $table->unsignedTinyInteger('donation_month')->nullable()->after('received_by_admin_id');
            $table->string('occasion')->nullable()->after('donation_month');
            $table->unique('receipt_number');
        });
    }

    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique(['receipt_number']);
            $table->dropConstrainedForeignId('received_by_admin_id');
            $table->dropConstrainedForeignId('donation_unit_id');
            $table->dropConstrainedForeignId('donation_type_id');
            $table->dropColumn([
                'received_at',
                'donation_kind',
                'amount_value',
                'receipt_number',
                'donation_month',
                'occasion',
            ]);
        });
    }
};
