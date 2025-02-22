<?php

namespace App\Controllers;

use App\Models\HomeModel;
use App\Core\View;

class HomeController {
    public function index() {
        $model = new HomeModel();
        $message = $model->getWelcomeMessage();

        return View::render('home', ['message' => $message]);
    }
}
