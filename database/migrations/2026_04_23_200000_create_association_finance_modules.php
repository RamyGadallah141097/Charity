<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('association_revenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revenue_type_id')->constrained('revenue_types')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('transaction_date');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('association_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_type_id')->constrained('expense_types')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('transaction_date');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('locker_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('revenue_id')->nullable()->after('donor_id');
            $table->unsignedBigInteger('expense_id')->nullable()->after('revenue_id');
            $table->foreign('revenue_id')->references('id')->on('association_revenues')->cascadeOnDelete();
            $table->foreign('expense_id')->references('id')->on('association_expenses')->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE locker_logs MODIFY moneyType ENUM('zakat','sadaka','loan','association') NOT NULL");

        DB::table('donation_types')->updateOrInsert(
            ['code' => 'association'],
            [
                'name' => 'خزنة الجمعية',
                'is_active' => 1,
                'sort_order' => 99,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down()
    {
        Schema::table('locker_logs', function (Blueprint $table) {
            $table->dropForeign(['expense_id']);
            $table->dropForeign(['revenue_id']);
            $table->dropColumn(['expense_id', 'revenue_id']);
        });

        DB::statement("ALTER TABLE locker_logs MODIFY moneyType ENUM('zakat','sadaka','loan') NOT NULL");

        DB::table('donation_types')->where('code', 'association')->delete();

        Schema::dropIfExists('association_expenses');
        Schema::dropIfExists('association_revenues');
    }
};
