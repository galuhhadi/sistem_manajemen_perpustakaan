<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;

class LogActivity
{
    public function handle($request, Closure $next) {
        $response = $next($request);

        // Hanya catat aksi perubahan data
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $request->method(),
                'resource' => $request->path(),
                'description' => "User " . (auth()->user()->name ?? 'Guest') . " melakukan " . $request->method() . " pada " . $request->path(),
                'ip_address' => $request->ip()
            ]);
        }
        return $response;
    }
}

