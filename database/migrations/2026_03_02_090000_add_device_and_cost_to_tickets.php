<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add whatsapp to customers
        Schema::table('customers', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('nama');
        });

        // Add device & cost columns to tickets
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('jenis_device')->default('LAPTOP')->after('description');
            $table->string('merk_device')->nullable()->after('jenis_device');
            $table->unsignedBigInteger('estimasi')->default(0)->after('merk_device');
            $table->unsignedBigInteger('biaya_final')->default(0)->after('estimasi');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['jenis_device', 'merk_device', 'estimasi', 'biaya_final']);
        });
    }
};
