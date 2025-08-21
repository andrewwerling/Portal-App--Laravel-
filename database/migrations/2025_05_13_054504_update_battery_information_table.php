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
        Schema::table('battery_information', function (Blueprint $table) {
            // Add new columns
            $table->string('unit_id')->after('id'); // Assuming 'id' is your primary key
            $table->string('ip_address')->nullable()->after('unit_id');
            $table->float('battery_voltage')->nullable()->after('ip_address');
            $table->float('battery_current')->nullable()->after('battery_voltage');
            $table->float('battery_power')->nullable()->after('battery_current');
            $table->float('solar_voltage')->nullable()->after('battery_power');
            $table->float('solar_current')->nullable()->after('solar_voltage');
            $table->float('solar_power')->nullable()->after('solar_current');
            $table->float('battery_percent')->nullable()->after('solar_power');
            $table->float('temperature_f')->nullable()->after('battery_percent');
            $table->float('humidity_percent')->nullable()->after('temperature_f');
            $table->json('relays')->nullable()->after('humidity_percent');

            // Drop old columns
            $table->dropColumn('device_id');
            $table->dropColumn('battery_level');
            $table->dropColumn('voltage');
            $table->dropColumn('current');
            $table->dropColumn('temperature');
            $table->dropColumn('humidity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('battery_information', function (Blueprint $table) {
            // Re-add old columns
            $table->string('device_id')->nullable(); // Adjust 'nullable' as per your old schema
            $table->float('battery_level')->nullable();
            $table->float('voltage')->nullable();
            $table->float('current')->nullable();
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();

            // Drop new columns
            $table->dropColumn('unit_id');
            $table->dropColumn('ip_address');
            $table->dropColumn('battery_voltage');
            $table->dropColumn('battery_current');
            $table->dropColumn('battery_power');
            $table->dropColumn('solar_voltage');
            $table->dropColumn('solar_current');
            $table->dropColumn('solar_power');
            $table->dropColumn('battery_percent');
            $table->dropColumn('temperature_f');
            $table->dropColumn('humidity_percent');
            $table->dropColumn('relays');
        });
    }
};
