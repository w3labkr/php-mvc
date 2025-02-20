<?php

class Router {
    public function run(): void {
        $url = $_GET['url'] ?? 'home/index';
        $url = rtrim(string: $url, characters: '/');
        $url = explode(separator: '/', string: $url);
        
        $controllerName = ucfirst(string: $url[0]) . 'Controller';
        $methodName = $url[1] ?? 'index';
        
        require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
        $controller = new $controllerName();
        if (method_exists(object_or_class: $controller, method: $methodName)) {
            $controller->$methodName();
        } else {
            echo "Method not found!";
        }
    }
}