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
        Schema::create('nas', function (Blueprint $table) {
            $table->id();
            $table->string('nasname', 128)->unique(); // Typically IP address or resolvable FQDN
            $table->string('shortname', 32)->nullable();
            $table->string('type', 30)->default('other');
            $table->integer('ports')->nullable()->comment('Physical ports on the NAS (mostly informational)');
            $table->string('secret', 60); // Shared secret with the NAS (MUST be strong)
            $table->string('server', 64)->nullable()->comment('DNS name of RADIUS server to use for CoA/DM if different');
            $table->string('community', 50)->nullable()->comment('SNMP community string if used');
            $table->string('description', 200)->nullable()->comment('Human-readable description');
            $table->timestamps(); // Optional: for when the NAS entry was created/updated in this table by your app
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nas');
    }
};