<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller
{
    private function studentData()
    {
        return [
            'student_id' => 'MCC2024 - 01799',
            'name'       => 'John Dell Fernandez Puyo',
            'course'     => 'BSIT',
            'year'       => '3rd Year',
            'section'    => '3F6',
            'email'      => 'johndellpuyo30@gmail.com'
        ];
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['profile_access_token'])) {
            $_SESSION['profile_access_token'] = bin2hex(random_bytes(16));
        }

        $data = $this->studentData();
        $data['profile_access_token'] = $_SESSION['profile_access_token'];
        $data['notice'] = $_SESSION['student_notice'] ?? null;
        unset($_SESSION['student_notice']);

        $this->call->view('student/student_home', $data);
    }

    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = $this->studentData();
        $data['profile_access_token'] = $_SESSION['profile_access_token'] ?? '';
        $data['middleware_message'] = $_SESSION['middleware_message'] ?? 'Access verified by StudentMiddleware.';

        $this->call->view('student/student_profile', $data);
    }
}
?>
