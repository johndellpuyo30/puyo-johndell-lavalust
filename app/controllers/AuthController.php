<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->startSession();
    }

    public function login()
    {
        if (!empty($_SESSION['auth_user'])) {
            redirect('products');
        }

        $data = [
            'error' => $_SESSION['login_error'] ?? '',
            'notice' => $_SESSION['login_notice'] ?? ''
        ];

        unset($_SESSION['login_error'], $_SESSION['login_notice']);
        $this->call->view('auth/login', $data);
    }

    public function authenticate()
    {
        $this->call->model('AuthModel');

        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $_SESSION['login_error'] = 'Please enter your username and password.';
            redirect('login');
            return;
        }

        $user = $this->AuthModel->findByUsername($username);

        if (!$user || !password_verify($password, (string) ($user['password'] ?? ''))) {
            $_SESSION['login_error'] = 'Invalid username or password.';
            redirect('login');
            return;
        }

        session_regenerate_id(true);
        $_SESSION['auth_user'] = [
            'id' => (int) $user['id'],
            'username' => (string) $user['username']
        ];

        redirect('products');
    }

    public function logout()
    {
        unset($_SESSION['auth_user']);
        session_regenerate_id(true);
        $_SESSION['login_notice'] = 'You have been logged out.';
        redirect('login');
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $cookieName = config_item('sess_cookie_name') ?: 'LLSession';
            session_name($cookieName);
            session_start();
        }
    }
}
