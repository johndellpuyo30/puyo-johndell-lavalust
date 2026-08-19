<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentMiddleware
{
    public function handle($next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $allowed = isset($_SESSION['student_access'])
            && $_SESSION['student_access'] === 'MCC2024 - 01799';

        if (!$allowed) {
            $_SESSION['student_notice'] = 'Access denied. Open Student Home first before viewing the Student Profile.';
            header('Location: ' . site_url('student'));
            exit;
        }

        $_SESSION['middleware_message'] = 'StudentMiddleware verified access: Student Home was opened first.';

        return $next();
    }
}
?>
