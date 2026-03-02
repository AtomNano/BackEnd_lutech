<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // PENJELASAN: Mengapa Enum? Karena sistem ini akan tumbuh. 
            // Besok-besok mungkin ada role 'finance' atau 'customer_support'.
            // Default wajib 'customer' agar jika ada celah keamanan registrasi, 
            // hacker tidak otomatis jadi admin.
            $table->enum('role', ['admin', 'technician', 'customer'])
                  ->default('customer')
                  ->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
