<?php

namespace App\Controllers;

use App\Core\Controller;

class AuthLogoutController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get() {
        session()->del('user');
        session_destroy();
        header("Location: /auth/login");
        exit();
    }

    public function post() {
        // ...
    }

    public function put() {
        // ...
    }

    public function delete() {
        // ...
    }
}
