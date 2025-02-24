<?php

use App\Middlewares\AuthMiddleware;
use App\Core\MiddlewareRunner;

// Create a new AltoRouter instance.
$router = new AltoRouter();

// Set the base path for the router (empty string if the document root is the 'public' folder).
$router->setBasePath('');

// Landing page route (maps the root URL to the HomeController).
$router->map('GET', '/', 'App\Controllers\HomeController#get');

// Authentication-related routes.
$router->map('GET', '/auth/register', 'App\Controllers\AuthRegisterController#get');
$router->map('GET', '/auth/login', 'App\Controllers\AuthLoginController#get');
$router->map('GET', '/auth/logout', 'App\Controllers\AuthLogoutController#get');
$router->map('GET', '/auth/forgot-password', 'App\Controllers\AuthForgotPasswordController#get');
$router->map('GET', '/auth/reset-password', 'App\Controllers\AuthResetPasswordController#get');

// POST routes for authentication-related actions.
$router->map('POST', '/api/v1/auth/register', 'App\Controllers\AuthRegisterController#post');
$router->map('POST', '/api/v1/auth/login', 'App\Controllers\AuthLoginController#post');
$router->map('POST', '/api/v1/auth/forgot-password', 'App\Controllers\AuthForgotPasswordController#post');
$router->map('POST', '/api/v1/auth/reset-password', 'App\Controllers\AuthResetPasswordController#post');

// Dashboard route with middleware for authentication.
$router->map('GET', '/dashboard', function () {
    $request = [];

    // Define middleware to ensure user is authenticated.
    $middlewares = [
        new AuthMiddleware()
    ];

    // Define the controller to handle the dashboard page.
    $controller = function ($req) {
        $homeController = new App\Controllers\DashboardController();
        return $homeController->index();
    };

    // Run middleware and then call the controller.
    return MiddlewareRunner::run($middlewares, $controller, $request);
});

// API-related routes.
$router->map('GET', '/api/v1', 'App\Controllers\ApiController#get');
$router->map('GET', '/api/v1/user/[i:id]', 'App\Controllers\ApiUserController#get');

// Attempt to match the current request URL against the defined routes in AltoRouter.
// This method returns an array containing details of the matched route (such as target, params, and name)
// or false if no route matches the current request.
$match = $router->match();

if ($match) {
    // If the target is a string with a '#' (e.g., "Controller#method"), split and call it.
    if (is_string($match['target']) && strpos($match['target'], '#') !== false) {
        list($controller, $method) = explode('#', $match['target']);
        $controllerInstance = new $controller();
        // Call the controller method with the matched parameters.
        echo call_user_func_array([$controllerInstance, $method], $match['params']);
    } elseif (is_callable($match['target'])) {
        // If the target is a callable function, execute it.
        echo call_user_func_array($match['target'], $match['params']);
    } else {
        // If no matching route, show 404 error page.
        header("HTTP/1.0 404 Not Found");
        require VIEW_PATH . '/404.php';
    }
} else {
    // If no match found, show 404 error page.
    header("HTTP/1.0 404 Not Found");
    require VIEW_PATH . '/404.php';
}
