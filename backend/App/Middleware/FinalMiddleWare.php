<?php

namespace App\Middleware;

class FinalMiddleware {
    public function handle($request, $next) {
        writeLog('FinalMiddleware', 'FinalMiddleware called');
        // No specific logic, just pass to the next handler
        return $next($request);
    }
}
