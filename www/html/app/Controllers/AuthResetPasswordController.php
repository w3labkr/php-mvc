<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\UserModel;

class AuthResetPasswordController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        $token = form()->get('token', '');

        // Instantiate UserModel and find the user by the reset token
        $userModel = new UserModel();
        $user = $userModel->findByResetPasswordToken($token);

        if ($user) {
            // If user exists, render reset password form with CSRF token and reset token
            return View::render('auth/reset-password', [
                'csrf_token' => session()->get('csrf_token'),
                'token' => $token
            ]);
        } else {
            // If the reset token is invalid or expired, show error message
            $this->response->json(400, 'Invalid or expired reset token');
            return;
        }
    }

    public function post()
    {
        // Retrieve form data
        $csrf_token = form()->post('csrf_token', '');
        $token = form()->post('token', '');
        $password = form()->post('password', '');
        $confirmPassword = form()->post('confirm_password', '');

        // CSRF token validation
        if (!verify_csrf_token($csrf_token)) {
            $this->response->json(400, 'Invalid CSRF token');
            return;
        }

        // Password match validation
        if ($password !== $confirmPassword) {
            $this->response->json(400, 'Passwords do not match');
            return;
        }

        // Instantiate UserModel and find the user by the reset token
        $userModel = new UserModel();
        $user = $userModel->findByResetPasswordToken($token);

        // Check if user exists and token is valid
        if ($user) {
            if ($userModel->resetPassword($token, $password)) {
                $this->response->json(200, 'Password successfully reset');
                return;
            } else {
                $this->response->json(500, 'An error occurred while resetting the password');
                return;
            }
        } else {
            $this->response->json(400, 'Invalid or expired reset token');
            return;
        }
    }

    public function put()
    {
        // ...
    }

    public function delete()
    {
        // ...
    }
}
