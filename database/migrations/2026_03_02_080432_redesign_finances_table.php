<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->foreignId('workspace_id')->after('id')->constrained('workspaces')->cascadeOnDelete();
            $table->foreignId('user_id')->after('workspace_id')->constrained();
            $table->enum('type', ['income', 'expense'])->after('user_id')->index();
            $table->string('category')->after('type');          // "Servis", "Sparepart", "Gaji"
            $table->decimal('amount', 15, 2)->after('category');
            $table->string('description')->nullable()->after('amount');
            $table->date('transaction_date')->after('description');
            $table->softDeletes();

            $table->index(['workspace_id', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['workspace_id', 'user_id', 'type', 'category', 'amount', 'description', 'transaction_date', 'deleted_at']);
        });
    }
};
