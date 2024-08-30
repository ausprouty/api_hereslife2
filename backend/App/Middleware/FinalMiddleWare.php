<?php
class FinalMiddleware {
    public function handle($request, $next) {
        // No specific logic, just pass to the next handler
        return $next($request);
    }
}
