<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthMiddleware
{
    public function handle($next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            $cookieName = config_item('sess_cookie_name') ?: 'LLSession';
            session_name($cookieName);
            session_start();
        }

        if (empty($_SESSION['auth_user'])) {
            $_SESSION['login_error'] = 'Please log in before accessing product management.';
            header('Location: ' . site_url('login'));
            exit;
        }

        return $next();
    }
}
