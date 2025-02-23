<?php

use App\Middlewares\AuthMiddleware;
use App\Core\MiddlewareRunner;

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ApiController;

$router = new AltoRouter();
$router->setBasePath(''); // DocumentRoot가 public 폴더라면 빈 문자열

// 홈 페이지
$router->map('GET', '/', function() {
    $homeController = new HomeController();
    return $homeController->index();
});

// 인증 관련 라우트
$router->map('GET', '/auth/login', function() {
    $authController = new AuthController();
    return $authController->showLogin();
});
$router->map('POST', '/auth/login', function() {
    $authController = new AuthController();
    return $authController->processLogin();
});
$router->map('GET', '/auth/forgot-password', function() {
    $authController = new AuthController();
    return $authController->showForgotPassword();
});
$router->map('POST', '/auth/forgot-password', function() {
    $authController = new AuthController();
    return $authController->processForgotPassword();
});
$router->map('GET', '/auth/register', function() {
    $authController = new AuthController();
    return $authController->showRegister();
});
$router->map('POST', '/auth/register', function() {
    $authController = new AuthController();
    return $authController->processRegister();
});
$router->map('GET', '/auth/logout', function() {
    $authController = new AuthController();
    return $authController->logout();
});

// 대시보드 라우트
$router->map('GET', '/dashboard', function() {
    $request = [];
    $middlewares = [
        new AuthMiddleware()
    ];
    $controller = function($req) {
        $homeController = new DashboardController();
        return $homeController->index();
    };
    return MiddlewareRunner::run($middlewares, $controller, $request);
});

// API 관련 라우트
$router->map('GET', '/api/v1', function() {
    $apiController = new ApiController();
    return $apiController->index();
});

$router->map('GET', '/api/v1/user/[i:id]', function($id) {
    $apiController = new ApiController();
    return $apiController->user($id);
});

$match = $router->match();

if ($match && is_callable($match['target'])) {
    echo call_user_func_array($match['target'], $match['params']);
} else {
    header("HTTP/1.0 404 Not Found");
    require VIEW_PATH . '/404.php';
}
