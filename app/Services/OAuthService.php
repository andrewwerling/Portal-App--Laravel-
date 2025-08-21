<?php
namespace App\Services;

use App\Models\User;
use App\Models\RadUser; // Added
// use App\Models\RadiusMapping; // Removed
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OAuthService
{
    public function handleOAuthRedirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleOAuthCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            if (empty($socialUser->getEmail())) {
                // Handle missing email
                return redirect()->route('login')->with('error', 'Email not provided by ' . $provider);
            }

            // 1. Create/Update RADIUS entries using RadUser
            // This handles [Provider]-OAuth-Details and a Cleartext-Password for the email
            RadUser::createOrUpdateOAuthUser($provider, $socialUser);

            // 2. Explicitly set Account-Level in radcheck for the email
            // (This logic needs to be robust, perhaps part of RadUser or called consistently)
            DB::table('radcheck')->updateOrInsert(
                ['username' => $socialUser->getEmail(), 'attribute' => 'Account-Level'],
                ['op' => ':=', 'value' => 'user'] // Or determine actual level
            );

            // 3. Find or Create local Laravel User (profile data only)
            // Ensure User model's $fillable doesn't include the deprecated OAuth columns
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'first_name' => $socialUser->getName() ? explode(' ', $socialUser->getName())[0] : 'OAuth',
                    'last_name' => $socialUser->getName() ? (count(explode(' ', $socialUser->getName())) > 1 ? explode(' ', $socialUser->getName())[1] : 'User') : 'User',
                    'password' => Hash::make(Str::random(24)), // For Laravel's own auth
                    'email_verified_at' => now(),
                    // 'account_level' => 'user', // If you also store account_level in users table
                ]
            );
            
            Auth::login($user);
            return redirect()->intended('dashboard');

        } catch (\Exception $e) {
            Log::error('OAuth Login Error in OAuthService: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'OAuth login failed');
        }
    }
}