<?php

namespace App\Core;

class MiddlewareRunner {
    /**
     * 미들웨어 체인을 실행하여 컨트롤러 호출
     *
     * @param array $middlewares 미들웨어 객체 배열
     * @param callable $controller 최종 컨트롤러 액션
     * @param mixed $request 요청 데이터
     * @return mixed 컨트롤러의 반환값
     */
    public static function run(array $middlewares, callable $controller, $request) {
        $next = function($req) use ($controller) {
            return $controller($req);
        };

        foreach (array_reverse($middlewares) as $middleware) {
            $next = function($req) use ($middleware, $next) {
                return $middleware->handle($req, $next);
            };
        }
        return $next($request);
    }
}
