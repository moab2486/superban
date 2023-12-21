<?php
    return [
        'cache_driver' => env('SUPERBAN_CACHE_DRIVER', 'redis'),
        'request_threshold' => 200,
        'ban_duration_hours' => 24,
        'ban_window_minutes' => 2,
        'routes' => [
            'enabled' => [
                // Specify routes and middleware here
                // 'route.name' => 'superban',
                // 'another.route' => 'superban',
            ],
        ],
    ];