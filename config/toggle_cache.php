<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Toggle Cache Settings
    |--------------------------------------------------------------------------
    */

    'enabled' => (bool) env('TOGGLE_CACHE_ENABLED', true),

    'default_ttl' => (int) env('TOGGLE_CACHE_TTL', 60),
];
