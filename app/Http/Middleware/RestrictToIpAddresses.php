<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RestrictToIpAddresses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$ips
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function handle(Request $request, Closure $next, string ...$ips): Response
    {
        $clientIp = $request->ip();
        
        // Check if the client IP is in the allowed list
        if (!in_array($clientIp, $ips)) {
            Log::warning('Unauthorized access attempt to restricted endpoint', [
                'ip' => $clientIp,
                'endpoint' => $request->path(),
                'allowed_ips' => $ips
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Your IP address is not authorized.'
            ], 403);
        }
        
        return $next($request);
    }
}