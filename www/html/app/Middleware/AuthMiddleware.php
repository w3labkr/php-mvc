<?php

namespace App\Middleware;

use App\Core\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface {
    public function handle($request, callable $next) {
        // 간단한 예제: 항상 인증된 것으로 처리 (실제 구현 시 세션/토큰 등 사용)
        $isAuthenticated = true;

        if (!$isAuthenticated) {
            header('HTTP/1.1 401 Unauthorized');
            echo "Unauthorized Access";
            exit();
        }
        return $next($request);
    }
}