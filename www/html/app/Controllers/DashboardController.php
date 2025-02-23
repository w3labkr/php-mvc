<?php

namespace App\Controllers;

use App\Core\View;

class DashboardController {
    // 대시보드 페이지 출력 (로그인한 사용자만 접근)
    public function index() {
        return View::render('dashboard/index', [
            'user' => session()->get('user')
        ]);
    }
}
