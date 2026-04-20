<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('governorates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('governorate_id')->constrained('governorates')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_id')->constrained('centers')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('beneficiary_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('donation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('donation_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('expense_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('revenue_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('disbursement_frequencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedInteger('months_interval')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        DB::table('beneficiary_categories')->insert([
            ['name' => 'أرملة', 'code' => 'widow', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'يتيم', 'code' => 'orphan', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'مريض', 'code' => 'medical', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ذو إعاقة', 'code' => 'disability', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'أسرة أولى بالرعاية', 'code' => 'priority_family', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('donation_types')->insert([
            ['name' => 'تبرع مالي', 'code' => 'cash', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'تبرع عيني', 'code' => 'in_kind', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'تبرع غذائي', 'code' => 'food', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'تبرع طبي', 'code' => 'medical', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'تبرع تعليمي', 'code' => 'education', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('donation_units')->insert([
            ['name' => 'جنيه', 'code' => 'egp', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'كيلو', 'code' => 'kg', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'كرتونة', 'code' => 'carton', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'شنطة', 'code' => 'bag', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'قطعة', 'code' => 'piece', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('expense_types')->insert([
            ['name' => 'مصروفات تشغيلية', 'code' => 'operational', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'مصروفات إدارية', 'code' => 'administrative', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'مساعدات مباشرة', 'code' => 'direct_aid', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'مصروفات طبية', 'code' => 'medical', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'مصروفات تعليمية', 'code' => 'educational', 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('revenue_types')->insert([
            ['name' => 'اشتراكات', 'code' => 'subscriptions', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'عوائد استثمار', 'code' => 'investment', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'إيرادات متنوعة', 'code' => 'misc', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('disbursement_frequencies')->insert([
            ['name' => 'شهري', 'code' => 'monthly', 'months_interval' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ربع سنوي', 'code' => 'quarterly', 'months_interval' => 3, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'نصف سنوي', 'code' => 'semi_annual', 'months_interval' => 6, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'سنوي', 'code' => 'annual', 'months_interval' => 12, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'غير دوري', 'code' => 'ad_hoc', 'months_interval' => null, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('disbursement_frequencies');
        Schema::dropIfExists('revenue_types');
        Schema::dropIfExists('expense_types');
        Schema::dropIfExists('donation_units');
        Schema::dropIfExists('donation_types');
        Schema::dropIfExists('beneficiary_categories');
        Schema::dropIfExists('villages');
        Schema::dropIfExists('centers');
        Schema::dropIfExists('governorates');
    }
};
