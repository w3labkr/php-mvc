<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use App\Models\UserModel;

use Monolog\Logger;
use App\Core\Log\PDOHandler;

class AuthController {
    private $db;
    private $logger;

    public function __construct() {
        // 데이터베이스 연결
        $this->db = Database::getInstance();

        // 로그 저장
        $this->logger = new Logger('AuthController');
        $this->logger->pushHandler(new PDOHandler($this->db, 'logs', Logger::INFO));
    }

    // 로그인 폼 출력
    public function showLogin() {
        if (session()->noexists('csrf_token')) {
            session()->set('csrf_token', generate_csrf_token());
        }
        return View::render('auth/login', ['csrf_token' => session()->get('csrf_token')]);
    }
    
    // 로그인 처리: 비밀번호 검증 후 대시보드로 이동
    public function processLogin() {
        $csrf_token = form()->post('csrf_token', '');
        $email = form()->post('email', '');
        $password = form()->post('password', '');

        if (!verify_csrf_token($csrf_token)) {
            die("Invalid CSRF token.");
        }        
        
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            // "remember" 체크박스가 체크되지 않았다면 세션 쿠키 수명을 1시간으로 재설정
            if (form()->post('remember') != '1') {
                $params = session_get_cookie_params();
                setcookie(session_name(), session_id(), time() + 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            }
            session()->set('user', [
                'id'    => $user['id'],
                'email' => $user['email'],
                'name'  => $user['name']
            ]);
            header("Location: /dashboard");
            exit();
        } else {
            // Log a warning message for a failed login attempt with additional context.
            $this->logger->warning("Login failed", ['email'  => $email, 'reason' => 'Invalid email or password']);
            
            return View::render('auth/login', [
                'error'      => 'Invalid email or password',
                'csrf_token' => session()->get('csrf_token'),
            ]);
        }
    }

    // 비밀번호 찾기 폼 출력
    public function showForgotPassword() {
        if (session()->noexists('csrf_token')) {
            session()->set('csrf_token', generate_csrf_token());
        }
        return View::render('auth/forgot-password', ['csrf_token' => session()->get('csrf_token')]);
    }

    // 비밀번호 찾기 처리: 메일 발송
    public function processForgotPassword() {
        $csrf_token = form()->post('csrf_token', '');
        $email = form()->post('email', '');
        
        if (!verify_csrf_token($csrf_token)) {
            die("Invalid CSRF token.");
        }
        
        // Retrieve and validate the submitted email.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return View::render('auth/forgot-password', [
                'error' => "Invalid email address.",
                'csrf_token' => session()->get('csrf_token'),
            ]);
        }

        // Check if the email exists in your user database.
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);
        
        // If the user does not exist, for security, pretend the email was sent.
        if (!$user) {
            return View::render('auth/forgot-password', [
                'success'   => "An email has been sent with instructions to reset your password.",
                'csrf_token'=> session()->get('csrf_token'),
            ]);
        }

        // Generate a secure token for password reset.
        $token = bin2hex(random_bytes(16));

        // Construct the password reset link.
        $resetLink = config('app.url') . "/reset-password?token=" . urlencode($token);

        // Create a PHPMailer instance using the mailer() helper.
        $mailer = mailer()->smtp();

        try {
            // Set sender and recipient details.
            $mailer->setFrom(config('mail.from.address'), config('mail.from.name'));
            $mailer->addAddress($email);

            // Set the email subject and body.
            $mailer->Subject = "Password Reset Request";
            $mailer->Body = "
                Dear User,\n\nWe received a request to reset your password.\n
                Please click the link below to reset your password:\n\n
                {$resetLink}\n\n
                If you did not request a password reset, please ignore this email.
            ";
            $mailer->isHTML(false); // Sending as plain text email.

            // Attempt to send the email.
            $mailer->send();

            return View::render('auth/forgot-password', [
                'success'   => "An email has been sent with instructions to reset your password.",
                'csrf_token'=> session()->get('csrf_token'),
            ]);
        } catch (\Exception $e) {
            $this->logger->error("Mailer Error: " . $e->getMessage());

            return View::render('auth/forgot-password', [
                'error'     => "Failed to send email. Please try again later.",
                'csrf_token'=> session()->get('csrf_token'),
            ]);
        }
    }
    
    // 회원가입 폼 출력
    public function showRegister() {
        if (session()->noexists('csrf_token')) {
            session()->set('csrf_token', generate_csrf_token());
        }
        return View::render('auth/register', [
            'csrf_token' => session()->get('csrf_token')
        ]);
    }
    
    // 회원가입 처리: 비밀번호 암호화 후 사용자 생성
    public function processRegister() {
        $csrf_token = form()->post('csrf_token', '');
        $name = form()->post('name', '');
        $email = form()->post('email', '');
        $password = form()->post('password', '');
        $confirmPassword = form()->post('confirm_password', '');

        if (!verify_csrf_token($csrf_token)) {
            die("Invalid CSRF token.");
        }
        
        if ($password !== $confirmPassword) {
            return View::render('auth/register', [
                'error'      => 'Passwords do not match',
                'csrf_token' => session()->get('csrf_token')
            ]);
        }
        
        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user) {
            return View::render('auth/register', [
                'error'      => 'Email already registered',
                'csrf_token' => session()->get('csrf_token')
            ]);
        }
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel->createUser($name, $email, $hashedPassword);
        
        header("Location: /auth/login");
        exit();
    }
    
    // 로그아웃 처리
    public function logout() {
        session()->del('user');
        session_destroy();
        header("Location: /auth/login");
        exit();
    }
}
