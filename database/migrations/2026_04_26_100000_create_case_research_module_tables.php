<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('social_researchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id')->nullable()->unique();
            $table->unsignedBigInteger('supervisor_admin_id')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->nullOnDelete();
            $table->foreign('supervisor_admin_id')->references('id')->on('admins')->nullOnDelete();
        });

        Schema::create('case_research_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_number')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('social_researcher_id')->nullable();
            $table->date('started_at')->nullable();
            $table->date('expected_end_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->enum('status', ['new', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('new');
            $table->string('delay_reason')->nullable();
            $table->enum('final_result', ['eligible', 'not_eligible', 'needs_follow_up', 'needs_documents'])->nullable();
            $table->longText('summary')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('social_researcher_id')->references('id')->on('social_researchers')->nullOnDelete();
        });

        Schema::create('case_research_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('case_research_file_id');
            $table->date('visited_at');
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();

            $table->foreign('case_research_file_id')->references('id')->on('case_research_files')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_research_visits');
        Schema::dropIfExists('case_research_files');
        Schema::dropIfExists('social_researchers');
    }
};
