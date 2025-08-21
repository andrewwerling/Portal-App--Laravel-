<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates test users with different account levels and populates all fields in the users table.
     * 
     * - 2025-05-01 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function run(): void
    {
        // Create a super admin user
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'phone' => '+1234567890',
            'birthday' => '1980-01-01',
            'gender' => 'prefer-not-to-say',
            'bio' => 'Super administrator account with full system access',
            'social_media' => json_encode([
                'twitter' => '@superadmin',
                'linkedin' => 'linkedin.com/in/superadmin',
                'facebook' => 'facebook.com/superadmin',
                'instagram' => 'instagram.com/superadmin'
            ]),
            'occupation' => 'System Administrator',
            'mailing_address' => json_encode([
                'street' => '123 Admin St',
                'city' => 'Admin City',
                'state' => 'AS',
                'zip' => '12345',
                'country' => 'USA'
            ]),
            'billing_address' => json_encode([
                'street' => '123 Admin St',
                'city' => 'Admin City',
                'state' => 'AS',
                'zip' => '12345',
                'country' => 'USA'
            ]),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'account_level' => 'super-admin',
            'provider' => null,
            'provider_id' => null,
            'provider_token' => null,
            'provider_refresh_token' => null,
            'provider_avatar' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create an admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone' => '+1987654321',
            'birthday' => '1985-02-15',
            'gender' => 'male',
            'bio' => 'Administrator account with elevated privileges',
            'social_media' => json_encode([
                'twitter' => '@admin',
                'linkedin' => 'linkedin.com/in/admin',
                'facebook' => 'facebook.com/admin',
                'instagram' => 'instagram.com/admin'
            ]),
            'occupation' => 'Administrator',
            'mailing_address' => json_encode([
                'street' => '456 Admin Ave',
                'city' => 'Admin Town',
                'state' => 'AT',
                'zip' => '54321',
                'country' => 'USA'
            ]),
            'billing_address' => json_encode([
                'street' => '456 Admin Ave',
                'city' => 'Admin Town',
                'state' => 'AT',
                'zip' => '54321',
                'country' => 'USA'
            ]),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'account_level' => 'admin',
            'provider' => null,
            'provider_id' => null,
            'provider_token' => null,
            'provider_refresh_token' => null,
            'provider_avatar' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a manager user
        User::create([
            'first_name' => 'Manager',
            'last_name' => 'User',
            'email' => 'manager@example.com',
            'phone' => '+1122334455',
            'birthday' => '1990-05-20',
            'gender' => 'female',
            'bio' => 'Manager account with team management capabilities',
            'social_media' => json_encode([
                'twitter' => '@manager',
                'linkedin' => 'linkedin.com/in/manager',
                'facebook' => 'facebook.com/manager',
                'instagram' => 'instagram.com/manager'
            ]),
            'occupation' => 'Team Manager',
            'mailing_address' => json_encode([
                'street' => '789 Manager Blvd',
                'city' => 'Manager City',
                'state' => 'MC',
                'zip' => '67890',
                'country' => 'USA'
            ]),
            'billing_address' => json_encode([
                'street' => '789 Manager Blvd',
                'city' => 'Manager City',
                'state' => 'MC',
                'zip' => '67890',
                'country' => 'USA'
            ]),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'account_level' => 'manager',
            'provider' => 'google',
            'provider_id' => 'google123456',
            'provider_token' => 'google_token_example',
            'provider_refresh_token' => 'google_refresh_token_example',
            'provider_avatar' => 'https://example.com/avatar/manager.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a regular user
        User::create([
            'first_name' => 'Regular',
            'last_name' => 'User',
            'email' => 'user@example.com',
            'phone' => '+1555666777',
            'birthday' => '1995-08-10',
            'gender' => 'non-binary',
            'bio' => 'Standard user account',
            'social_media' => json_encode([
                'twitter' => '@user',
                'linkedin' => 'linkedin.com/in/user',
                'facebook' => 'facebook.com/user',
                'instagram' => 'instagram.com/user'
            ]),
            'occupation' => 'Software Developer',
            'mailing_address' => json_encode([
                'street' => '101 User St',
                'city' => 'User City',
                'state' => 'UC',
                'zip' => '10101',
                'country' => 'USA'
            ]),
            'billing_address' => json_encode([
                'street' => '101 User St',
                'city' => 'User City',
                'state' => 'UC',
                'zip' => '10101',
                'country' => 'USA'
            ]),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'account_level' => 'user',
            'provider' => 'facebook',
            'provider_id' => 'facebook123456',
            'provider_token' => 'facebook_token_example',
            'provider_refresh_token' => 'facebook_refresh_token_example',
            'provider_avatar' => 'https://example.com/avatar/user.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a guest user
        User::create([
            'first_name' => 'Guest',
            'last_name' => 'User',
            'email' => 'guest@example.com',
            'phone' => '+1999888777',
            'birthday' => '2000-12-25',
            'gender' => 'other',
            'bio' => 'Guest account with limited access',
            'social_media' => json_encode([
                'twitter' => '@guest',
                'linkedin' => 'linkedin.com/in/guest',
                'facebook' => 'facebook.com/guest',
                'instagram' => 'instagram.com/guest'
            ]),
            'occupation' => 'Visitor',
            'mailing_address' => json_encode([
                'street' => '202 Guest Ave',
                'city' => 'Guest Town',
                'state' => 'GT',
                'zip' => '20202',
                'country' => 'USA'
            ]),
            'billing_address' => json_encode([
                'street' => '202 Guest Ave',
                'city' => 'Guest Town',
                'state' => 'GT',
                'zip' => '20202',
                'country' => 'USA'
            ]),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'account_level' => 'guest',
            'provider' => null,
            'provider_id' => null,
            'provider_token' => null,
            'provider_refresh_token' => null,
            'provider_avatar' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create additional users with factory
        User::factory()->count(500)->create();
    }
}
