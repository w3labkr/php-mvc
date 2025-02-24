<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\HomeModel;

class HomeController {
    public function get() {
        $model = new HomeModel();
        $message = $model->getWelcomeMessage();

        return View::render('home', ['message' => $message]);
    }
}
