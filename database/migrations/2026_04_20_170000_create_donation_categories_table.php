<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        DB::table('donation_categories')->insert([
            ['name' => 'ملابس', 'code' => 'clothes', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'أثاث', 'code' => 'furniture', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'موبايلات', 'code' => 'mobiles', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'وجبات', 'code' => 'meals', 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_categories');
    }
};
