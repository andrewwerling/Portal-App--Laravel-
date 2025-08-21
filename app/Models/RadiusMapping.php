<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RadUser_Legacy extends Model
{
    protected $table = 'radcheck';
    
    public static function findByUsername($username)
    {
        return DB::table('radcheck')
            ->where('username', $username)
            ->where('attribute', 'Cleartext-Password')
            ->first();
    }

    public static function authenticate($username, $password)
    {
        $user = self::findByUsername($username);
        
        if (!$user || !Hash::check($password, $user->value)) {
            return null;
        }

        $userGroup = DB::table('radusergroup')
            ->where('username', $username)
            ->first();

        $accountLevel = DB::table('radcheck')
            ->where('username', $username)
            ->where('attribute', 'Account-Level')
            ->first();

        return (object)[
            'username' => $username,
            'group' => $userGroup->groupname ?? 'guest',
            'account_level' => $accountLevel->value ?? 'guest'
        ];
    }

    public static function createOrUpdateOAuthUser($provider, $socialiteUser)
    {
        $username = $socialiteUser->getEmail();
        
        // Check if user exists by email in radcheck
        $existingUser = DB::table('radcheck')
            ->where('username', $username)
            ->first();

        // Prepare user data for RADIUS tables
        $password = Hash::make(Str::random(24)); // Random secure password
        $oauthData = json_encode([
            'provider' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'provider_token' => $socialiteUser->token,
            'provider_refresh_token' => $socialiteUser->refreshToken ?? null,
            'provider_avatar' => $socialiteUser->getAvatar(),
        ]);

        if ($existingUser) {
            // Update OAuth details in the 'OAuth-Details' attribute
            DB::table('radcheck')->updateOrInsert(
                [
                    'username' => $username,
                    'attribute' => 'OAuth-Details'
                ],
                [
                    'op' => ':=',
                    'value' => $oauthData
                ]
            );
        } else {
            // Create new user in RADIUS tables
            DB::table('radcheck')->insert([
                [
                    'username' => $username,
                    'attribute' => 'Cleartext-Password',
                    'op' => ':=',
                    'value' => $password
                ],
                [
                    'username' => $username,
                    'attribute' => 'OAuth-Details',
                    'op' => ':=',
                    'value' => $oauthData
                ],
                [
                    'username' => $username,
                    'attribute' => 'Account-Level',
                    'op' => ':=',
                    'value' => 'user'
                ]
            ]);

            // Assign to default user group
            DB::table('radusergroup')->insert([
                'username' => $username,
                'groupname' => 'user',
                'priority' => 1
            ]);
        }

        return self::findByUsername($username);
    }

    public static function getOAuthDetails($username)
    {
        $oauthDetails = DB::table('radcheck')
            ->where('username', $username)
            ->where('attribute', 'OAuth-Details')
            ->first();

        return $oauthDetails ? json_decode($oauthDetails->value, true) : null;
    }

    public static function createUser(array $data)
    {
        $username = $data['email'];
        $password = Hash::make($data['password']);
        $accountLevel = $data['account_level'] ?? 'user';

        DB::table('radcheck')->insert([
            [
                'username' => $username,
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $password
            ],
            [
                'username' => $username,
                'attribute' => 'Account-Level',
                'op' => ':=',
                'value' => $accountLevel
            ]
        ]);

        DB::table('radusergroup')->insert([
            'username' => $username,
            'groupname' => 'user',
            'priority' => 1
        ]);

        return self::findByUsername($username);
    }
}