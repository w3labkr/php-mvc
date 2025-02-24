<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Core\View;

class AuthForgotPasswordController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get() {
        if (session()->noexists('csrf_token')) {
            session()->set('csrf_token', generate_csrf_token());
        }
        return View::render('auth/forgot-password', [
            'csrf_token' => session()->get('csrf_token'),
        ]);
    }

    public function post() {
        $csrf_token = form()->post('csrf_token', '');
        $email = form()->post('email', '');

        if (!verify_csrf_token($csrf_token)) {
            $this->response->error('Invalid CSRF token');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response->error('Invalid email address');
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        // For security, if the email is not found, pretend that the email was sent.
        if (!$user) {
            $this->response->success('An email has been sent with instructions to reset your password.');
            return;
        }

        // Generate a secure token for password reset.
        $token = bin2hex(random_bytes(16));
        $resetLink = config('app.url') . "/reset-password?token=" . urlencode($token);

        // Create a PHPMailer instance using the mailer() helper.
        $mailer = mailer()->smtp();

        try {
            $mailer->setFrom(config('mail.from.address'), config('mail.from.name'));
            $mailer->addAddress($email);
            $mailer->Subject = "Password Reset Request";
            $mailer->Body = "Dear User,\n\nWe received a request to reset your password.\n" .
                            "Please click the link below to reset your password:\n\n" .
                            "{$resetLink}\n\n" .
                            "If you did not request a password reset, please ignore this email.";
            $mailer->isHTML(false);
            $mailer->send();

            $this->response->success('An email has been sent with instructions to reset your password.');
            return;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $this->logger->error('Mailer Error: ' . $mailer->ErrorInfo, ['exception' => $e]);
            $this->response->error('Failed to send email. Please try again later.');
            return;
        }
    }

    public function put() {
        // ...
    }

    public function delete() {
        // ... 
    }
}
