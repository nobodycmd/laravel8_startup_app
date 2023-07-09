<?php

namespace App\Http\Middleware;

use App\Models\AdminRole;
use App\Services\RedisService;
use App\Services\WebLogServer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TrafficCounter
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        RedisService::incrValue('TrafficCounter'.date('YmdHi'),1);
        return $next($request);
    }
}
