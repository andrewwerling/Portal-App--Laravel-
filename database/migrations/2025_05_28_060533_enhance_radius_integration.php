<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Ensure radcheck table has user_id
        if (!Schema::hasColumn('radcheck', 'user_id')) {
            Schema::table('radcheck', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
                $table->index('user_id');
            });
        }

        // Enhance radpostauth for comprehensive logging
        if (!Schema::hasColumns('radpostauth', ['extra'])) {
            Schema::table('radpostauth', function (Blueprint $table) {
                $table->text('extra')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback logic
        Schema::table('radcheck', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('radpostauth', function (Blueprint $table) {
            $table->dropColumn('extra');
        });

        // Recreate dropped tables if needed
    }
};