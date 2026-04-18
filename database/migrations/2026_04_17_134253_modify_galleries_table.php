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
        Schema::table('galleries', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['image_path', 'title']);
            
            // Add new columns
            $table->string('type')->default('image'); // image, video, instagram, youtube
            $table->text('url'); // can be storage path or external URL
            $table->text('caption')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['type', 'url', 'caption']);
            $table->string('title');
            $table->string('image_path');
        });
    }
};
