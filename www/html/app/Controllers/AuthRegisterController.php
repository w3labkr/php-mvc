<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\UserModel;

class AuthRegisterController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get() {
        if (session()->noexists('csrf_token')) {
            session()->set('csrf_token', generate_csrf_token());
        }
        return View::render('auth/register', [
            'csrf_token' => session()->get('csrf_token'),
        ]);
    }

    public function post() {
        // Retrieve POST data using form() helper.
        $csrf_token      = form()->post('csrf_token', '');
        $name            = form()->post('name', '');
        $email           = form()->post('email', '');
        $password        = form()->post('password', '');
        $confirmPassword = form()->post('confirm_password', '');
    
        // Validate CSRF token.
        if (!verify_csrf_token($csrf_token)) {
            $this->response->json(400, 'Invalid CSRF token');
            return;
        }
    
        // Check if passwords match.
        if ($password !== $confirmPassword) {
            $this->response->json(400, 'Passwords do not match');
            return;
        }
    
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);
    
        // Check if the email is already registered.
        if ($user) {
            $this->response->json(400, 'Email already registered');
            return;
        }
    
        // Hash the password and create the user.
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel->createUser($name, $email, $hashedPassword);
    
        // Return a success JSON response.
        $this->response->json(200, 'OK');
        return;
    }

    public function put() {
        // ...
    }

    public function delete() {
        // ...
    }
}
