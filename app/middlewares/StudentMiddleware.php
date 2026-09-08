<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentMiddleware
{
    public function handle($next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $requestToken = $_GET['access'] ?? '';
        $sessionToken = $_SESSION['profile_access_token'] ?? '';

        if ($requestToken !== '' && $sessionToken !== '' && hash_equals($sessionToken, $requestToken)) {
            $_SESSION['student_profile_once'] = true;
            header('Location: ' . site_url('student/profile'));
            exit;
        }

        if (!empty($_SESSION['student_profile_once'])) {
            unset($_SESSION['student_profile_once']);
            $_SESSION['middleware_message'] = 'StudentMiddleware verified access from Student Home.';
            return $next();
        }

        $_SESSION['student_notice'] = 'Warning: Direct access to the Student Profile is not allowed. Please open the protected profile from Student Home first.';
        header('Location: ' . site_url('student'));
        exit;
    }
}
?>
