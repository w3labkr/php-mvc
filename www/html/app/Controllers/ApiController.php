<?php

namespace App\Controllers;

class ApiController {
    public function index() {
        header('Content-Type: application/json');
        echo json_encode([
            'status'  => 'fail',
            'success'  => false,
            'message' => 'Bad Request',
            'data' => []
        ]);
    }

    public function user($id) {
        header('Content-Type: application/json');
        echo json_encode([
            'status'  => 'success',
            'success'  => false,
            'message' => 'OK',
            'data'    => [
                'id'    => $id,
                'name'  => 'User Name',        // 예시 데이터
                'email' => 'user@example.com'    // 예시 데이터
            ]
        ]);
    }
}
