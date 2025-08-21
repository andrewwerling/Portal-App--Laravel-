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
        Schema::create('radacct', function (Blueprint $table) {
            $table->bigIncrements('radacctid');
            $table->string('acctsessionid', 64)->default('')->index();
            $table->string('acctuniqueid', 32)->default('')->unique()->index(); // Unique ID for the accounting record
            $table->string('username', 64)->default('')->index();
            $table->string('realm', 64)->default('')->nullable();
            $table->string('nasipaddress', 15)->default('')->index(); // IPv4
            $table->string('nasportid', 32)->nullable();
            $table->string('nasporttype', 32)->nullable();
            $table->dateTime('acctstarttime')->nullable()->index();
            $table->dateTime('acctupdatetime')->nullable(); // Some dbs update this on every interim
            $table->dateTime('acctstoptime')->nullable()->index();
            $table->integer('acctinterval')->nullable()->comment('The interval in seconds for interim updates');
            $table->unsignedBigInteger('acctsessiontime')->nullable()->index()->comment('Seconds the session was active');
            $table->string('acctauthentic', 32)->nullable();
            $table->string('connectinfo_start', 128)->nullable();
            $table->string('connectinfo_stop', 128)->nullable();
            $table->unsignedBigInteger('acctinputoctets')->nullable();
            $table->unsignedBigInteger('acctoutputoctets')->nullable();
            $table->string('calledstationid', 50)->default('');
            $table->string('callingstationid', 50)->default('')->index(); // Client's MAC address or other ID
            $table->string('acctterminatecause', 32)->default('');
            $table->string('servicetype', 32)->nullable();
            $table->string('framedprotocol', 32)->nullable();
            $table->string('framedipaddress', 15)->default('')->index(); // IPv4 assigned to user
            // Optional IPv6 fields, if you plan to support IPv6
            $table->string('framedipv6address', 45)->nullable()->index();
            $table->string('framedipv6prefix', 45)->nullable();
            $table->string('framedinterfaceid', 44)->nullable(); // For PPPoE or other tunnel interfaces
            $table->string('delegatedipv6prefix', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radacct');
    }
};