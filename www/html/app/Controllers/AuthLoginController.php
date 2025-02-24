<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\UserModel;

class AuthLoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        if (session()->noexists('csrf_token')) {
            session()->set('csrf_token', generate_csrf_token());
        }
        return View::render('auth/login', [
            'csrf_token' => session()->get('csrf_token'),
        ]);
    }

    public function post()
    {
        $csrf_token = form()->post('csrf_token', '');
        $email = form()->post('email', '');
        $password = form()->post('password', '');

        if (!verify_csrf_token($csrf_token)) {
            $this->response->json(400, 'Invalid CSRF token');
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // If "remember" is not checked, set the session cookie lifetime to 1 hour.
            if (form()->post('remember') != '1') {
                $params = session_get_cookie_params();
                setcookie(session_name(), session_id(), time() + 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            }
            session()->set('user', [
                'id'    => $user['id'],
                'email' => $user['email'],
                'name'  => $user['name']
            ]);
            $this->response->json(200, 'OK');
            return;
        } else {
            $this->logger->warning("Login failed", ['email'  => $email, 'reason' => 'Invalid email or password']);
            $this->response->json(400, 'Invalid email or password');
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
