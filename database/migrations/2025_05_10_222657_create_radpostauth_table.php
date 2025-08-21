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
        Schema::create('radpostauth', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->default('')->index();
            $table->string('pass', 64)->default('')->comment('Password sent by user (BE CAREFUL)');
            $table->string('reply', 32)->default(''); // e.g., Access-Accept, Access-Reject
            $table->string('calledstationid', 50)->default('');
            $table->string('callingstationid', 50)->default('');
            $table->string('nasipaddress', 15)->default('')->index(); // IPv4
            $table->timestamp('authdate')->useCurrent()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radpostauth');
    }
};