<?php

use App\Middleware\AuthMiddleware;
use App\Core\MiddlewareRunner;
use App\Controllers\HomeController;
use App\Controllers\ApiController;

$router = new AltoRouter();
$router->setBasePath('');

$router->map('GET', '/', function() {
    $request = [];

    $middlewares = [
        new AuthMiddleware()
    ];

    $controller = function($req) {
        $homeController = new HomeController();
        return $homeController->index();
    };

    return MiddlewareRunner::run($middlewares, $controller, $request);
});

$router->map('GET', '/about', function() {
    echo 'About Page';
});

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
    echo '404 Not Found';
}
