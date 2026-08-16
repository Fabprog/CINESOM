<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RapidApiLimiter
{
    public const MAX_REQUESTS  = 240;
    public const MAX_BANDWIDTH = 10 * 1024 * 1024; // 10 MB

    public function handle(Request $request, Closure $next): mixed
    {
        $today  = now()->toDateString();
        $reqKey = "rapidapi_requests_{$today}";
        $bwKey  = "rapidapi_bandwidth_{$today}";
        $ttl    = now()->secondsUntilEndOfDay();

        // increment() é atômico — elimina race condition do get/put anterior
        $requests = Cache::increment($reqKey);
        Cache::add($reqKey, 0, $ttl);

        if ($requests > self::MAX_REQUESTS) {
            return response()->json([
                'error' => 'Limite diário de requisições atingido.',
                'reset' => 'Tente novamente amanhã.',
            ], 429);
        }

        if ((int) Cache::get($bwKey, 0) >= self::MAX_BANDWIDTH) {
            return response()->json([
                'error' => 'Limite diário de largura de banda atingido.',
                'reset' => 'Tente novamente amanhã.',
            ], 429);
        }

        $response = $next($request);

        if ($response->getStatusCode() < 400) {
            $bw = Cache::increment($bwKey, strlen($response->getContent()));
            if ($bw === strlen($response->getContent())) {
                Cache::add($bwKey, 0, $ttl);
            }
            if ($bw > self::MAX_BANDWIDTH) {
                Log::warning('RapidAPI bandwidth limit exceeded', ['bytes' => $bw, 'date' => $today]);
            }
        }

        return $response;
    }
}
