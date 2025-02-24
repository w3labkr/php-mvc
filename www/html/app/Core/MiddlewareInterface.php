<?php

namespace App\Core;

/**
 * MiddlewareInterface defines the contract for middleware classes.
 * 
 * Middleware classes are used to filter or modify incoming requests
 * and outgoing responses, often used for authentication, logging, etc.
 */
interface MiddlewareInterface {
    
    /**
     * Handle the incoming request and pass it to the next middleware or request handler.
     *
     * @param mixed $request The incoming request object that the middleware will process.
     * @param callable $next A callable that represents the next middleware or request handler in the pipeline.
     * 
     * @return mixed The response from the next middleware or request handler.
     */
    public function handle($request, callable $next);
}
