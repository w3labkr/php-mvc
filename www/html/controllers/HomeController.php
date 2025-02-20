<?php

require_once 'core/Controller.php';
require_once 'models/HomeModel.php';

class HomeController extends Controller {
    public function index(): void {
        $model = new HomeModel();
        $message = $model->getMessage();
        $this->loadView(view: 'home', data: ['message' => $message]);
    }
}