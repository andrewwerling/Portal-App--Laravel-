<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class MigrateUsersToRadius extends Command
{
    protected $signature = 'users:migrate-to-radius 
                            {--dry-run : Run without making changes}
                            {--details : Show detailed migration information}';

    protected $description = 'Migrate existing users to RADIUS tables';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $verbose = $this->option('details');

        // Explicitly show if this is a dry run
        $this->info($dryRun ? "RUNNING IN DRY RUN MODE - NO CHANGES WILL BE MADE" : "RUNNING LIVE MIGRATION");

        try {
            // Begin transaction if not a dry run
            if (!$dryRun) {
                DB::beginTransaction();
            }

            // Fetch all existing users with error handling
            $users = User::all();

            if ($users->isEmpty()) {
                $this->warn("No users found to migrate.");
                return Command::FAILURE;
            }

            $this->info("Found {$users->count()} users to migrate.");

            $migratedUsers = 0;
            $failedUsers = 0;
            $skippedUsers = 0;

            foreach ($users as $user) {
                try {
                    // Check if user already exists in radcheck
                    $existingRadUser = DB::table('radcheck')
                        ->where('username', $user->email)
                        ->where('attribute', 'MD5-Password')
                        ->first();

                    if ($existingRadUser) {
                        $skippedUsers++;
                        if ($verbose) {
                            $this->line("Skipped user (already migrated): {$user->email}");
                        }
                        continue;
                    }

                    if (!$dryRun) {
                        // Create RADIUS user
                        $this->createRadiusUser($user);
                        
                        // Add to user group
                        $this->addUserToGroup($user);
                    }

                    $migratedUsers++;

                    if ($verbose) {
                        $this->line("Migrated user: {$user->email}");
                    }
                } catch (\Exception $e) {
                    $failedUsers++;
                    $this->error("Failed to migrate user {$user->email}: " . $e->getMessage());
                    
                    // Log detailed error
                    Log::error('User Migration Error', [
                        'user_email' => $user->email,
                        'error_message' => $e->getMessage(),
                        'error_trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Commit transaction if not a dry run
            if (!$dryRun) {
                DB::commit();
            }

            // Display migration summary
            $this->info("Migration Summary:");
            $this->info("Total Users: {$users->count()}");
            $this->info("Migrated Users: {$migratedUsers}");
            $this->info("Skipped Users: {$skippedUsers}");
            $this->info("Failed Users: {$failedUsers}");

            // Verification
            $this->verifyMigration($dryRun);

            return $failedUsers > 0 ? Command::FAILURE : Command::SUCCESS;

        } catch (\Exception $mainException) {
            // Rollback transaction in case of main exception
            if (!$dryRun) {
                DB::rollBack();
            }

            $this->error("Migration process failed: " . $mainException->getMessage());
            
            // Log main process error
            Log::error('User Migration Process Error', [
                'error_message' => $mainException->getMessage(),
                'error_trace' => $mainException->getTraceAsString()
            ]);

            return Command::FAILURE;
        }
    }

    protected function createRadiusUser(User $user)
    {
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

        // Add additional user details to radreply
        DB::table('radreply')->insert([
            'username' => $user->email,
            'attribute' => 'User-FirstName',
            'op' => ':=',
            'value' => $user->first_name ?? ''
        ]);

        DB::table('radreply')->insert([
            'username' => $user->email,
            'attribute' => 'User-LastName',
            'op' => ':=',
            'value' => $user->last_name ?? ''
        ]);
    }

    protected function addUserToGroup(User $user)
    {
        // Determine group name based on account level
        $groupName = 'guest'; // Default group
        if ($user->account_level == 'super-admin') {
            $groupName = 'super-admin';
        } elseif ($user->account_level == 'admin') {
            $groupName = 'admin';
        } elseif ($user->account_level == 'manager') {
            $groupName = 'manager';
        } elseif ($user->account_level == 'user') {
            $groupName = 'user';
        }

        // Determine priority based on group name
        $priority = 1; // Default priority
        if ($groupName == 'super-admin') {
            $priority = 5;
        } elseif ($groupName == 'admin') {
            $priority = 4;
        } elseif ($groupName == 'manager') {
            $priority = 3;
        } elseif ($groupName == 'user') {
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
    }

    protected function verifyMigration($dryRun = false)
    {
        // Diagnostic checks
        $this->performDatabaseDiagnostics();

        if (!$dryRun) {
            // Count users in RADIUS tables
            $radCheckCount = DB::table('radcheck')
                ->where('attribute', 'MD5-Password')
                ->count();
            
            $radUserGroupCount = DB::table('radusergroup')->count();

            $this->info("Verification Results:");
            $this->info("Users in radcheck: {$radCheckCount}");
            $this->info("User group mappings: {$radUserGroupCount}");
        } else {
            $this->info("Verification skipped due to dry run mode.");
        }
    }

    protected function performDatabaseDiagnostics()
    {
        // Check database connection
        try {
            DB::connection()->getPdo();
            $this->info("Database connection: SUCCESSFUL");
        } catch (\Exception $e) {
            $this->error("Database connection failed: " . $e->getMessage());
            return;
        }

        // Check table existence
        $tables = ['radcheck', 'radusergroup', 'radreply'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("Table {$table}: EXISTS");
                
                // Check table structure
                $columns = Schema::getColumnListing($table);
                $this->info("Columns in {$table}: " . implode(', ', $columns));
            } else {
                $this->error("Table {$table}: DOES NOT EXIST");
            }
        }

        // Check user table
        $userCount = DB::table('users')->count();
        $this->info("Total users in users table: {$userCount}");

        // Database connection details
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");
        $database = config("database.connections.{$connection}.database");
        
        $this->info("Database Connection Details:");
        $this->info("Connection: {$connection}");
        $this->info("Driver: {$driver}");
        $this->info("Database: {$database}");
    }
}