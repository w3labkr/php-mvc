<?php

use App\Middlewares\AuthMiddleware;
use App\Core\MiddlewareRunner;

$router = new AltoRouter();
$router->setBasePath(''); // DocumentRoot가 public 폴더라면 빈 문자열

// 랜딩 페이지
$router->map('GET', '/', 'App\Controllers\HomeController#get');

// 인증 관련 라우트
$router->map('GET', '/auth/login', 'App\Controllers\AuthLoginController#get');
$router->map('GET', '/auth/logout', 'App\Controllers\AuthLogoutController#get');
$router->map('GET', '/auth/forgot-password', 'App\Controllers\AuthForgotPasswordController#get');
$router->map('GET', '/auth/register', 'App\Controllers\AuthRegisterController#get');

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
$router->map('GET', '/api/v1', 'App\Controllers\ApiController#get');
$router->map('GET', '/api/v1/user/[i:id]', 'App\Controllers\ApiUserController#get');

$router->map('POST', '/api/v1/auth/login', 'App\Controllers\AuthLoginController#post');
$router->map('POST', '/api/v1/auth/register', 'App\Controllers\AuthRegisterController#post');
$router->map('POST', '/api/v1/auth/forgot-password', 'App\Controllers\AuthForgotPasswordController#post');

// Attempt to match the current request URL against the defined routes in AltoRouter.
// This method returns an array containing details of the matched route (such as target, params, and name)
// or false if no route matches the current request.
$match = $router->match();

if ($match) {
    // If target is a string with a '#' (e.g. "Controller#method"), split and call it.
    if (is_string($match['target']) && strpos($match['target'], '#') !== false) {
        list($controller, $method) = explode('#', $match['target']);
        $controllerInstance = new $controller();
        echo call_user_func_array([$controllerInstance, $method], $match['params']);
    } elseif (is_callable($match['target'])) {
        echo call_user_func_array($match['target'], $match['params']);
    } else {
        header("HTTP/1.0 404 Not Found");
        require VIEW_PATH . '/404.php';
    }
} else {
    header("HTTP/1.0 404 Not Found");
    require VIEW_PATH . '/404.php';
}