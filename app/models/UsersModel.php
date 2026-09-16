<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = ['firstname', 'lastname', 'email', 'username'];

    public function getAllUsers()
    {
        return $this->all();
    }

    public function getUser($id)
    {
        return $this->find((int) $id);
    }

    public function createUser(array $data)
    {
        return $this->insert($data);
    }

    public function updateUser($id, array $data)
    {
        return $this->update((int) $id, $data);
    }

    public function deleteUser($id)
    {
        return $this->delete((int) $id);
    }

    public function emailExists($email, $ignoreId = null)
    {
        $record = $this->find_by('email', $email);
        if (!$record) {
            return false;
        }

        return $ignoreId === null || (int) $record['id'] !== (int) $ignoreId;
    }

    public function usernameExists($username, $ignoreId = null)
    {
        $record = $this->find_by('username', $username);
        if (!$record) {
            return false;
        }

        return $ignoreId === null || (int) $record['id'] !== (int) $ignoreId;
    }
}
