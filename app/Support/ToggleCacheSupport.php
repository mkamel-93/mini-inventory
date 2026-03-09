<?php

declare(strict_types=1);

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ToggleCacheSupport
{
    /**
     * Get the TTL from the dedicated config file.
     */
    private function getTtlFromConfig(): int
    {
        return (int) Config::get('toggle_cache.default_ttl', 60);
    }

    /**
     * Determine if the cache is globally enabled.
     */
    private function isEnabled(): bool
    {
        return (bool) Config::get('toggle_cache.enabled', true);
    }

    /**
     * Remember the value if caching is enabled.
     *
     * @template T
     *
     * @param  Closure(): T  $callback
     * @return T
     */
    public function remember(string $key, Closure $callback, ?int $seconds = null): mixed
    {
        if (! $this->isEnabled()) {
            Log::debug("ToggleCache: Bypassed for key [{$key}] (Cache Disabled)");

            return $callback();
        }

        $ttl = $seconds ?? $this->getTtlFromConfig();

        if (Cache::has($key)) {
            Log::info("ToggleCache: Retrieved value for key [{$key}]");
        } else {
            Log::info("ToggleCache: Stored new value for key [{$key}] for {$ttl} seconds");
        }

        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Remove an item from the cache.
     */
    public function forget(string $key): bool
    {
        Log::info("ToggleCache: Forgot key [{$key}]");

        return Cache::forget($key);
    }
}
