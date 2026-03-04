<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('amount');
            $table->string('source')->default('web')->after('status'); // 'web', 'n8n_telegram', 'n8n_whatsapp'
            $table->text('ai_metadata')->nullable()->after('source'); // Raw JSON for debugging
            $table->string('attachment_path')->nullable()->after('ai_metadata'); // receipt photo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->dropColumn(['status', 'source', 'ai_metadata', 'attachment_path']);
        });
    }
};
