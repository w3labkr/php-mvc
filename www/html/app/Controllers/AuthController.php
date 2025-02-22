<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Core\View;

class AuthController {
    // 로그인 폼 출력
    public function showLogin() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return View::render('auth/login', ['csrf_token' => $_SESSION['csrf_token']]);
    }
    
    // 로그인 처리: 비밀번호 검증 후 대시보드로 이동
    public function processLogin() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Invalid CSRF token.");
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'email' => $user['email'],
                'name'  => $user['name']
            ];
            header("Location: /dashboard");
            exit();
        } else {
            return View::render('auth/login', [
                'error'      => 'Invalid email or password',
                'csrf_token' => $_SESSION['csrf_token']
            ]);
        }
    }
    
    // 회원가입 폼 출력
    public function showRegister() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return View::render('auth/register', ['csrf_token' => $_SESSION['csrf_token']]);
    }
    
    // 회원가입 처리: 비밀번호 암호화 후 사용자 생성
    public function processRegister() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Invalid CSRF token.");
        }
        
        $name            = $_POST['name'] ?? '';
        $email           = $_POST['email'] ?? '';
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if ($password !== $confirmPassword) {
            return View::render('auth/register', [
                'error'      => 'Passwords do not match',
                'csrf_token' => $_SESSION['csrf_token']
            ]);
        }
        
        $userModel = new UserModel();
        if ($userModel->findByEmail($email)) {
            return View::render('auth/register', [
                'error'      => 'Email already registered',
                'csrf_token' => $_SESSION['csrf_token']
            ]);
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel->createUser($name, $email, $hashedPassword);
        
        header("Location: /auth/login");
        exit();
    }
    
    // 로그아웃 처리
    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
        header("Location: /auth/login");
        exit();
    }
}
