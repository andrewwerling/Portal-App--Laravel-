<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * 
     * - 2023-08-01 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function handle(Request $request, Closure $next, string ...$levels): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admins can access everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if the user has one of the required account levels
        foreach ($levels as $level) {
            $checkMethod = 'is' . ucfirst($level);
            
            // If there's a method like isAdmin(), use it
            if (method_exists($user, $checkMethod) && $user->$checkMethod()) {
                return $next($request);
            }
            
            // Otherwise, check the account_level directly
            if ($user->account_level === $level) {
                return $next($request);
            }
        }

        // If we get here, the user doesn't have the required account level
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
    }
}
