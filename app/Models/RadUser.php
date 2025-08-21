<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class RadUser
{
    const LEVEL_ADMIN = 'admin';
    const LEVEL_USER = 'user';
    const LEVEL_GUEST = 'guest';

    /**
     * Create or update an OAuth user in RADIUS tables
     *
     * @param string $provider
     * @param mixed $socialiteUser
     * @return object
     */
    public static function createOrUpdateOAuthUser($provider, $socialiteUser)
    {
        // Prepare OAuth details as a JSON string
        $oauthDetails = json_encode([
            'provider' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'email' => $socialiteUser->getEmail(),
            'name' => $socialiteUser->getName(),
            'avatar' => $socialiteUser->getAvatar(),
            'token' => $socialiteUser->token,
            'refresh_token' => $socialiteUser->refreshToken ?? null
        ]);

        $oauthAttribute = ucfirst($provider) . '-OAuth-Details';

        // Try to find existing OAuth record for this email and provider-specific attribute
        $existingRecord = DB::table('radcheck')
            ->where('username', $socialiteUser->getEmail())
            ->where('attribute', $oauthAttribute)
            ->first();

        if ($existingRecord) {
            // Update existing record
            DB::table('radcheck')
                ->where('username', $socialiteUser->getEmail())
                ->where('attribute', $oauthAttribute)
                ->update([
                    'value' => $oauthDetails,
                    'op' => ':='
                ]);
        } else {
            // Insert new record
            DB::table('radcheck')->insert([
                'username' => $socialiteUser->getEmail(),
                'attribute' => $oauthAttribute,
                'op' => ':=',
                'value' => $oauthDetails
            ]);
        }



        return (object)[
            'username' => $socialiteUser->getEmail(),
            'oauth_id' => (string) $socialiteUser->getId()
        ];
    }

    /**
     * Get OAuth details for a given provider ID
     *
     * @param string $email
     * @param string $provider
     * @return array|null
     */
    public static function getOAuthDetails(string $email, string $provider)
    {
        $oauthAttribute = ucfirst($provider) . '-OAuth-Details';
        $record = DB::table('radcheck')
            ->where('username', $email)
            ->where('attribute', $oauthAttribute)
            ->first();

        return $record ? json_decode($record->value, true) : null;
    }

    /**
     * Create a new user in RADIUS tables
     *
     * @param array $userData
     * @return object
     */
    public static function createUser(array $userData)
    {
        // Insert user credentials into radcheck
        DB::table('radcheck')->insert([
            'username' => $userData['email'],
            'attribute' => 'Cleartext-Password',
            'op' => ':=',
            'value' => $userData['password']
        ]);

        // Set account level 
        DB::table('radcheck')->insert([
            'username' => $userData['email'],
            'attribute' => 'Account-Level',
            'op' => ':=',
            'value' => $userData['account_level'] ?? self::LEVEL_USER
        ]);

        return (object)[
            'username' => $userData['email'],
            'email' => $userData['email'],
            'account_level' => $userData['account_level'] ?? self::LEVEL_USER
        ];
    }

    /**
     * Get user groups
     *
     * @param string $username
     * @return array
     */
    public static function getGroups(string $username): array
    {
        return DB::table('radusergroup')
            ->where('username', $username)
            ->pluck('groupname')
            ->toArray();
    }
}