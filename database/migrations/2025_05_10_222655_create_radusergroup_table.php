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
        Schema::create('radusergroup', function (Blueprint $table) {
            // Using username and groupname as composite primary key is common,
            // but an auto-incrementing id is also fine.
            // $table->id();
            $table->string('username', 64)->default('');
            $table->string('groupname', 64)->default('');
            $table->integer('priority')->default(1);

            $table->primary(['username', 'groupname', 'priority']); // Composite primary key
            $table->index('username'); // Index for username lookups
            // No need for a separate id column if using composite primary key.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radusergroup');
    }
};