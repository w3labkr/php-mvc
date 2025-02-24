<?php

namespace App\Middlewares;

use App\Core\MiddlewareInterface;

/**
 * AuthMiddleware class checks if the user is authenticated.
 * If not, it redirects the user to the login page.
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Handles the request by checking if the user is authenticated.
     *
     * If the user is not authenticated (session does not have 'user'),
     * the user is redirected to the login page.
     * Otherwise, the request is passed to the next middleware/controller.
     *
     * @param mixed $request The incoming request.
     * @param callable $next The next middleware or controller to handle the request.
     * @return mixed The result of the next middleware/controller.
     */
    public function handle($request, callable $next)
    {
        // Check if the 'user' session variable does not exist.
        if (session()->noexists('user')) {
            // If no 'user' session, redirect to the login page.
            header("Location: /auth/login");
            exit();
        }
        // If user is authenticated, pass the request to the next handler.
        return $next($request);
    }
}
