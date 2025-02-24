<?php

namespace App\Core;

/**
 * MiddlewareRunner is responsible for running the provided middleware stack
 * before calling the controller or request handler.
 */
class MiddlewareRunner
{
    /**
     * Run the middleware stack and pass the request to the controller.
     *
     * This method iterates through the middlewares in reverse order
     * and passes the request through each middleware before calling
     * the provided controller.
     *
     * @param array $middlewares An array of middleware instances to be executed in order.
     * @param callable $controller The final controller or request handler that processes the request.
     * @param mixed $request The incoming request to be processed by middleware and controller.
     *
     * @return mixed The response returned by the controller or the last middleware.
     */
    public static function run(array $middlewares, callable $controller, $request)
    {

        // Create the initial "next" callable to pass the request to the controller.
        $next = function ($req) use ($controller) {
            return $controller($req);
        };

        // Loop through the middleware stack in reverse order and chain them.
        foreach (array_reverse($middlewares) as $middleware) {
            // Wrap the current middleware in a "next" function to ensure the flow continues
            // after the middleware handles the request.
            $next = function ($req) use ($middleware, $next) {
                return $middleware->handle($req, $next); // Pass the request to the middleware.
            };
        }

        // Run the final middleware (which is the controller) and return the result.
        return $next($request);
    }
}
