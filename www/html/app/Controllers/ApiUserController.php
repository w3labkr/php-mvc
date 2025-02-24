<?php

namespace App\Controllers;

use App\Core\Controller;

class ApiUserController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get($id) {
        $this->response->success('OK', [
            'id'    => $id,
            'name'  => 'User Name',
            'email' => 'user@example.com'
        ]);
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
