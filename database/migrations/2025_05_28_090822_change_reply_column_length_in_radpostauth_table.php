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
        Schema::table('radpostauth', function (Blueprint $table) {
            // Change VARCHAR(32) to VARCHAR(255)
            $table->string('reply', 255)->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radpostauth', function (Blueprint $table) {
            // Revert VARCHAR(255) back to VARCHAR(32)
            // Ensure this matches the original definition accurately
            $table->string('reply', 32)->default('')->change();
        });
    }
};