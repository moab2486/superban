<?php
    // src/Middleware/SuperbanMiddleware.php

namespace Abdulkadir\Superban\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class SuperbanMiddleware
{
    public function handle($request, Closure $next)
    {
        $routeName = Route::currentRouteName();

        $enabledRoutes = config('superban.routes.enabled', []);

        if (array_key_exists($routeName, $enabledRoutes)) {
            $threshold = config('superban.request_threshold', 200);
            $banDuration = config('superban.ban_duration_hours', 24);
            $banWindow = config('superban.ban_window_minutes', 2);

            $ipAddress = $request->ip();
            $cacheKey = 'superban:ip:' . $ipAddress . ':' . $routeName;

            $requestCount = Cache::get($cacheKey, 0);
            $requestCount++;

            Cache::put($cacheKey, $requestCount, now()->addMinutes($banWindow));

            if ($requestCount > $threshold) {
                // Ban the IP address
                $this->banUser($ipAddress, $banDuration);

                return response('You have been banned.', 403);
            }
        }

        return $next($request);
    }

    protected function banUser($ipAddress, $duration)
    {
        
        $userId = $request->user() ? $request->user()->id : null;
        $ipAddress = $request->ip();
        $email = $request->user() ? $request->user()->email : null;

        // Store ban information in the cache driver
        Cache::put('superban:user:' . $userId, true, now()->addHours($duration));
        Cache::put('superban:ip:' . $ipAddress, true, now()->addHours($duration));
        Cache::put('superban:email:' . $email, true, now()->addHours($duration));
    }
}