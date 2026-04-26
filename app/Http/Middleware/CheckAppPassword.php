<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckAppPassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Setting::get('require_password', '0') || Setting::get('require_password') === '0' || Setting::get('require_password') === 'false') {
            return $next($request);
        }

        $storedHash = Setting::get('app_password');
        if (empty($storedHash)) {
            return $next($request);
        }

        $sessionKey = 'app_unlocked';
        if ($request->session()->get($sessionKey)) {
            return $next($request);
        }

        if ($request->hasHeader('X-App-Unlocked') && $request->header('X-App-Unlocked') === $storedHash) {
            $request->session()->put($sessionKey, true);
            return $next($request);
        }

        return response()->view('auth.lock', [], 403);
    }
}