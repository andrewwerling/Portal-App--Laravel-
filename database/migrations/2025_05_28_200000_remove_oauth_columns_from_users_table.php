<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOAuthColumnsFromUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop OAuth-related columns if they exist
            if (Schema::hasColumn('users', 'provider')) {
                $table->dropColumn('provider');
            }
            if (Schema::hasColumn('users', 'provider_id')) {
                $table->dropColumn('provider_id');
            }
            if (Schema::hasColumn('users', 'provider_token')) {
                $table->dropColumn('provider_token');
            }
            if (Schema::hasColumn('users', 'provider_refresh_token')) {
                $table->dropColumn('provider_refresh_token');
            }
            if (Schema::hasColumn('users', 'provider_avatar')) {
                $table->dropColumn('provider_avatar');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Optional: Restore OAuth columns if needed
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('provider_token')->nullable();
            $table->string('provider_refresh_token')->nullable();
            $table->string('provider_avatar')->nullable();
        });
    }
}