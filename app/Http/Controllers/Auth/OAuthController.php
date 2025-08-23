<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RadUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        $configKey = $provider;
        if ($provider === 'twitter') {
            $configKey = 'twitter-oauth-2';
        }

        // Check if the provider is configured
        if (!config("services.{$configKey}.client_id") || !config("services.{$configKey}.client_secret")) {
            return redirect()->route('login')->withErrors([
                'email' => "OAuth provider '{$provider}' is not configured. Please check your .env file for {$configKey}_CLIENT_ID and {$configKey}_CLIENT_SECRET.",
            ]);
        }

        if ($provider === 'twitter') {
            // Log Twitter-specific configuration for debugging
            Log::debug('Twitter OAuth Redirect: Scopes - ' . json_encode(config('services.twitter-oauth-2.scopes')) . ', Client ID: ' . config('services.twitter-oauth-2.client_id'));
            return Socialite::driver('twitter-oauth-2')->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Error authenticating with ' . ucfirst($provider) . ': ' . $e->getMessage(),
            ]);
        }

        // Check if email is provided by Socialite
        if (empty($socialiteUser->getEmail())) {
            Log::warning('OAuth callback for ' . ucfirst($provider) . ': Email not provided. Redirecting to registration.');
            return redirect()->route('register')->withErrors([
                'email' => 'No email provided by ' . ucfirst($provider) . '. Please register with an email.'
            ]);
        }

        // Use RadUser to manage OAuth details in RADIUS tables
        $radUser = RadUser::createOrUpdateOAuthUser($provider, $socialiteUser);

        // Find or create corresponding auth user WITHOUT touching OAuth columns
        $user = User::firstOrCreate(
            ['email' => $socialiteUser->getEmail()],
            [
                'first_name' => $this->splitName($socialiteUser->getName())['first_name'],
                'last_name' => $this->splitName($socialiteUser->getName())['last_name'],
                'email' => $socialiteUser->getEmail(),
                'password' => Hash::make(Str::random(24)), // Random password
                'email_verified_at' => now(),
            ]
        );

        // Login the user
        Auth::login($user);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Split a name into first and last name.
     *
     * @param string $name
     * @return array
     */
    protected function splitName($name)
    {
        $name = trim($name);
        $parts = explode(' ', $name);

        $firstName = array_shift($parts);
        $lastName = implode(' ', $parts);

        return [
            'first_name' => $firstName,
            'last_name' => $lastName ?: '',
        ];
    }

    /**
     * Handle Facebook deauthorization callback.
     *
     * This endpoint is called by Facebook when a user removes your app's permissions.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookDeauthorize(Request $request)
    {
        // Log the deauthorization event
        Log::info('Facebook deauthorization callback received', [
            'signed_request' => $request->input('signed_request')
        ]);

        // Decode the signed request
        $signedRequest = $request->input('signed_request');
        if ($signedRequest) {
            list(, $payload) = explode('.', $signedRequest, 2); // Ignore the encoded signature
            $data = json_decode(base64_decode($payload), true);

            if (isset($data['user_id'])) {
                $facebookProviderId = $data['user_id'];
                $oauthAttributeToDelete = 'Facebook-OAuth-Details'; // Actual attribute name used by RadUser::createOrUpdateOAuthUser

                // Find all records for Facebook OAuth details to locate the user's email
                $allFacebookOAuthRecords = DB::table('radcheck')
                    ->where('attribute', $oauthAttributeToDelete)
                    ->get();

                $userEmailToDelete = null;

                foreach ($allFacebookOAuthRecords as $record) {
                    $details = json_decode($record->value, true);
                    // Check if the provider_id within the JSON matches Facebook's user_id
                    if (isset($details['provider_id']) && (string) $details['provider_id'] === (string) $facebookProviderId) {
                        $userEmailToDelete = $record->username; // This username is the email, as per RadUser::createOrUpdateOAuthUser
                        break;
                    }
                }

                if ($userEmailToDelete) {
                    // Clear the specific OAuth details from radcheck using the email and the correct attribute
                    DB::table('radcheck')
                        ->where('username', $userEmailToDelete)
                        ->where('attribute', $oauthAttributeToDelete)
                        ->delete();

                    Log::info('User deauthorized Facebook access and radcheck entry deleted', [
                        'email' => $userEmailToDelete,
                        'facebook_user_id' => $facebookProviderId
                    ]);
                } else {
                    Log::warning('Could not find radcheck entry to delete for Facebook deauthorization', [
                        'facebook_user_id' => $facebookProviderId
                    ]);
                }
            }
        }

        // Return a 200 OK response to Facebook
        return response()->json(['success' => true]);
    }
}