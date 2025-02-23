<?php

namespace App\Middlewares;

use App\Core\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface {
    public function handle($request, callable $next) {
        // 세션에 사용자 정보가 없으면 로그인 페이지로 리다이렉트
        if (!isset($_SESSION['user'])) {
            header("Location: /auth/login");
            exit();
        }
        return $next($request);
    }
}
