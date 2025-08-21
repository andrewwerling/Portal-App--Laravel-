<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure radcheck is set up for account-level attributes
        if (!Schema::hasColumn('radcheck', 'created_at')) {
            Schema::table('radcheck', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // Ensure radusergroup supports custom priority and timestamps
        Schema::table('radusergroup', function (Blueprint $table) {
            if (!Schema::hasColumn('radusergroup', 'priority')) {
                $table->integer('priority')->default(1)->after('groupname');
            }
            if (!Schema::hasColumn('radusergroup', 'created_at')) {
                $table->timestamps();
            }
        });

        // Enhance radpostauth with more detailed logging
        if (!Schema::hasColumn('radpostauth', 'ip_address')) {
            Schema::table('radpostauth', function (Blueprint $table) {
                $table->ipAddress('ip_address')->nullable()->after('reply');
                $table->string('user_agent')->nullable()->after('ip_address');
            });
        }

        // Enhance radacct with more session tracking
        if (!Schema::hasColumn('radacct', 'groupname')) {
            Schema::table('radacct', function (Blueprint $table) {
                $table->string('groupname')->nullable()->after('acctterminatecause');
                $table->string('device_identifier')->nullable()->after('groupname');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('radcheck', 'created_at')) {
            Schema::table('radcheck', function (Blueprint $table) {
                $table->dropColumn(['created_at', 'updated_at']);
            });
        }

        if (Schema::hasColumn('radusergroup', 'priority')) {
            Schema::table('radusergroup', function (Blueprint $table) {
                $table->dropColumn(['priority', 'created_at', 'updated_at']);
            });
        }

        if (Schema::hasColumn('radpostauth', 'ip_address')) {
            Schema::table('radpostauth', function (Blueprint $table) {
                $table->dropColumn(['ip_address', 'user_agent']);
            });
        }

        if (Schema::hasColumn('radacct', 'groupname')) {
            Schema::table('radacct', function (Blueprint $table) {
                $table->dropColumn(['groupname', 'device_identifier']);
            });
        }
    }
};