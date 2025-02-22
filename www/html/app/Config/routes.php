<?php

use App\Middleware\AuthMiddleware;
use App\Core\MiddlewareRunner;

$router = new AltoRouter();
$router->setBasePath(''); // DocumentRoot가 public 폴더라면 빈 문자열

// 홈 페이지
$router->map('GET', '/', function() {
    $homeController = new App\Controllers\HomeController();
    return $homeController->index();
});

// 인증 관련 라우트
$router->map('GET', '/auth/login', function() {
    $authController = new App\Controllers\AuthController();
    return $authController->showLogin();
});
$router->map('POST', '/auth/login', function() {
    $authController = new App\Controllers\AuthController();
    return $authController->processLogin();
});
$router->map('GET', '/auth/register', function() {
    $authController = new App\Controllers\AuthController();
    return $authController->showRegister();
});
$router->map('POST', '/auth/register', function() {
    $authController = new App\Controllers\AuthController();
    return $authController->processRegister();
});
$router->map('GET', '/auth/logout', function() {
    $authController = new App\Controllers\AuthController();
    return $authController->logout();
});

// 대시보드 라우트
$router->map('GET', '/dashboard', function() {
    $request = [];
    $middlewares = [
        new AuthMiddleware()
    ];
    $controller = function($req) {
        $homeController = new App\Controllers\DashboardController();
        return $homeController->index();
    };
    return MiddlewareRunner::run($middlewares, $controller, $request);
});

// API 관련 라우트
$router->map('GET', '/api/v1', function() {
    $apiController = new App\Controllers\ApiController();
    return $apiController->index();
});

$router->map('GET', '/api/v1/user/[i:id]', function($id) {
    $apiController = new App\Controllers\ApiController();
    return $apiController->user($id);
});

$match = $router->match();

if ($match && is_callable($match['target'])) {
    echo call_user_func_array($match['target'], $match['params']);
} else {
    header("HTTP/1.0 404 Not Found");
    require VIEWS_PATH . '/404.php';
}
