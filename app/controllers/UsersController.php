<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UsersModel');
    }

    public function index()
    {
        $data = [
            'users' => $this->UsersModel->getAllUsers(),
            'notice' => isset($_GET['notice']) ? trim($_GET['notice']) : ''
        ];

        $this->call->view('users/index', $data);
    }

    public function create()
    {
        $this->showForm('create', [], []);
    }

    public function store()
    {
        $input = $this->userInput();
        $errors = $this->validateUser($input);

        if ($this->UsersModel->emailExists($input['email'])) {
            $errors['email'] = 'That email address is already registered.';
        }

        if ($this->UsersModel->usernameExists($input['username'])) {
            $errors['username'] = 'That username is already in use.';
        }

        if (!empty($errors)) {
            $this->showForm('create', $input, $errors);
            return;
        }

        $this->UsersModel->createUser($input);
        redirect('users?notice=created');
    }

    public function edit($id)
    {
        $user = $this->UsersModel->getUser($id);
        if (!$user) {
            redirect('users?notice=not-found');
        }

        $this->showForm('edit', $user, [], (int) $id);
    }

    public function update($id)
    {
        $user = $this->UsersModel->getUser($id);
        if (!$user) {
            redirect('users?notice=not-found');
        }

        $input = $this->userInput();
        $errors = $this->validateUser($input);

        if ($this->UsersModel->emailExists($input['email'], $id)) {
            $errors['email'] = 'That email address belongs to another user.';
        }

        if ($this->UsersModel->usernameExists($input['username'], $id)) {
            $errors['username'] = 'That username belongs to another user.';
        }

        if (!empty($errors)) {
            $input['id'] = (int) $id;
            $this->showForm('edit', $input, $errors, (int) $id);
            return;
        }

        $this->UsersModel->updateUser($id, $input);
        redirect('users?notice=updated');
    }

    public function delete($id)
    {
        $user = $this->UsersModel->getUser($id);
        if (!$user) {
            redirect('users?notice=not-found');
        }

        $this->UsersModel->deleteUser($id);
        redirect('users?notice=deleted');
    }

    private function userInput()
    {
        return [
            'firstname' => trim($_POST['firstname'] ?? ''),
            'lastname' => trim($_POST['lastname'] ?? ''),
            'email' => strtolower(trim($_POST['email'] ?? '')),
            'username' => trim($_POST['username'] ?? '')
        ];
    }

    private function validateUser(array $input)
    {
        $errors = [];

        if ($input['firstname'] === '' || strlen($input['firstname']) > 100) {
            $errors['firstname'] = 'Enter a first name up to 100 characters.';
        }

        if ($input['lastname'] === '' || strlen($input['lastname']) > 100) {
            $errors['lastname'] = 'Enter a last name up to 100 characters.';
        }

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL) || strlen($input['email']) > 150) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if (!preg_match('/^[A-Za-z0-9._-]{3,40}$/', $input['username'])) {
            $errors['username'] = 'Use 3-40 letters, numbers, dots, dashes, or underscores.';
        }

        return $errors;
    }

    private function showForm($mode, array $user, array $errors, $id = null)
    {
        $data = [
            'mode' => $mode,
            'user' => $user,
            'errors' => $errors,
            'id' => $id
        ];

        $this->call->view('users/form', $data);
    }
}
