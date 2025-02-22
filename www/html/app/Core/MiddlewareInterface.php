<?php

namespace App\Core;

interface MiddlewareInterface {
    /**
     * 미들웨어 핸들러
     *
     * @param mixed $request 요청 데이터
     * @param callable $next 다음 미들웨어 또는 최종 컨트롤러 콜백
     */
    public function handle($request, callable $next);
}
