<?php

namespace App\Controllers;

use App\Core\View;

class DashboardController
{
    public function index()
    {
        return View::render('dashboard/index', [
            'user' => session()->get('user')
        ]);
    }
}
