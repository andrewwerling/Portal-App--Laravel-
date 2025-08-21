<?php
namespace App\Services;

use App\Models\RadUser;
use Illuminate\Support\Facades\DB;

class RadiusAuthService
{
    /**
     * Authenticate a user using FreeRadius tables
     *
     * @param string $username
     * @param string $password
     * @return object|null
     */
    public function authenticate(string $username, string $password): ?object
    {
        // Find user in radcheck with Cleartext-Password
        $userRecord = DB::table('radcheck')
            ->where('username', $username)
            ->where('attribute', 'Cleartext-Password')
            ->first();

        if (!$userRecord) {
            return null;
        }

        // Verify password by direct comparison
        // $userRecord->value should be the plaintext password from the database
        // $password is the plaintext password from the form
        $isPasswordValid = ($userRecord->value === $password);

        if (!$isPasswordValid) {
            // Log failed authentication attempt
            $this->logAuthenticationAttempt($username, false);
            return null;
        }

        // Log successful authentication
        $this->logAuthenticationAttempt($username, true);

        // Retrieve user's group
        $userGroup = DB::table('radusergroup')
            ->where('username', $username)
            ->first();

        // Retrieve account level
        $accountLevelRecord = DB::table('radcheck')
            ->where('username', $username)
            ->where('attribute', 'Account-Level')
            ->first();

        // Create a RadUser-like object with necessary information
        return (object)[
            'username' => $username,
            'group' => $userGroup->groupname ?? 'guest',
            'account_level' => $accountLevelRecord->value ?? 'guest',
            'isInGroup' => function($groupName) use ($userGroup) {
                return $userGroup && $userGroup->groupname === $groupName;
            }
        ];
    }

    /**
     * Register a new user in RADIUS tables
     *
     * @param array $userData
     * @return object
     */
    public function register(array $userData): object
    {
        return RadUser::createUser([
            'username' => $userData['email'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'account_level' => $userData['account_level'] ?? RadUser::LEVEL_USER
        ]);
    }

    /**
     * Log authentication attempts in radpostauth
     *
     * @param string $username
     * @param bool $success
     */
    protected function logAuthenticationAttempt(string $username, bool $success)
    {
        DB::table('radpostauth')->insert([
            'username' => $username,
            'pass' => $success ? 'Accept' : 'Reject',
            'reply' => $success ? 'Authentication Successful' : 'Authentication Failed',
            'authdate' => now()
        ]);
    }

    /**
     * Start a network session for a user
     *
     * @param object $user An object with a ->username property
     * @param string $nasIdentifier
     * @return int
     */
    public function startNetworkSession(object $user, string $nasIdentifier): int
    {
        return DB::table('radacct')->insertGetId([
            'username' => $user->username,
            'nasipaddress' => request()->ip(),
            'nasidentifier' => $nasIdentifier,
            'acctstarttime' => now(),
            'acctstartdelay' => 0,
            'groupname' => collect(RadUser::getGroups($user->username))->first() ?? 'default'
        ]);
    }

    /**
     * End a network session
     *
     * @param int $sessionId
     */
    public function endNetworkSession(int $sessionId)
    {
        DB::table('radacct')
            ->where('radacctid', $sessionId)
            ->update([
                'acctstoptime' => now(),
                'acctsessiontime' => DB::raw('TIMESTAMPDIFF(SECOND, acctstarttime, NOW())'),
                'acctterminatecause' => 'User-Request'
            ]);
    }
}