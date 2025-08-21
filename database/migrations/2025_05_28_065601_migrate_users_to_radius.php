<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing users to RADIUS tables
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            // Generate a FreeRADIUS compatible password hash
            $radiusPasswordHash = md5($user->email . $user->password);

            // Insert user into radcheck for authentication
            DB::table('radcheck')->insert([
                'username' => $user->email,
                'attribute' => 'MD5-Password',
                'op' => ':=',
                'value' => $radiusPasswordHash,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Add account level as an attribute
            DB::table('radcheck')->insert([
                'username' => $user->email,
                'attribute' => 'Account-Level',
                'op' => ':=',
                'value' => $user->account_level ?? 'user',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Determine group name based on account level
            $groupName = 'guest'; // Default group
            if ($user->account_level === 'super-admin') {
                $groupName = 'super-admin';
            } elseif ($user->account_level === 'admin') {
                $groupName = 'admin';
            } elseif ($user->account_level === 'manager') {
                $groupName = 'manager';
            } elseif ($user->account_level === 'user') {
                $groupName = 'user';
            }

            // Determine priority based on group name
            $priority = 1; // Default priority
            if ($groupName === 'super-admin') {
                $priority = 5;
            } elseif ($groupName === 'admin') {
                $priority = 4;
            } elseif ($groupName === 'manager') {
                $priority = 3;
            } elseif ($groupName === 'user') {
                $priority = 2;
            }

            // Add user to appropriate RADIUS group
            DB::table('radusergroup')->insert([
                'username' => $user->email,
                'groupname' => $groupName,
                'priority' => $priority,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Add additional user details to radreply
            DB::table('radreply')->insert([
                'username' => $user->email,
                'attribute' => 'User-FirstName',
                'op' => ':=',
                'value' => $user->first_name ?? '',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('radreply')->insert([
                'username' => $user->email,
                'attribute' => 'User-LastName',
                'op' => ':=',
                'value' => $user->last_name ?? '',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove migrated users from RADIUS tables
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            DB::table('radcheck')
                ->where('username', $user->email)
                ->delete();

            DB::table('radusergroup')
                ->where('username', $user->email)
                ->delete();

            DB::table('radreply')
                ->where('username', $user->email)
                ->delete();
        }
    }
};