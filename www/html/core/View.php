<?php

class View {
    public static function render($view, $data = []): void {
        extract(array: $data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}