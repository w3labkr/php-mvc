<?php

namespace App\Controllers;

use App\Core\Controller;

class ApiController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function get() {
        $this->response->json(400, 'Bad Request');
        return;
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
