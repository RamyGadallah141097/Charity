<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('case_research_files', function (Blueprint $table) {
            $table->json('provided_documents')->nullable()->after('summary');
            $table->json('missing_documents')->nullable()->after('provided_documents');
            $table->json('attachments')->nullable()->after('missing_documents');
            $table->date('actual_end_at')->nullable()->after('expected_end_at');
        });
    }

    public function down()
    {
        Schema::table('case_research_files', function (Blueprint $table) {
            $table->dropColumn(['provided_documents', 'missing_documents', 'attachments', 'actual_end_at']);
        });
    }
};
