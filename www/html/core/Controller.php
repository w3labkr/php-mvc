<?php

class Controller {
    protected function loadModel($model): object {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }
    protected function loadView($view, $data = []): void {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}