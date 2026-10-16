<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'first_name', 'last_name', 'middle_name', 'username', 'email', 
        'password', 'role', 'department', 'contact_number', 'profile_picture', 
        'is_active', 'reset_token', 'reset_token_expires', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]',
        'first_name' => 'required|min_length[2]|max_length[50]',
        'last_name' => 'required|min_length[2]|max_length[50]',
        'role' => 'required|in_list[student,associate,itso_personnel]'
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'This username is already taken.'
        ],
        'email' => [
            'is_unique' => 'This email is already registered.'
        ]
    ];

    protected $skipValidation = false;
    
    public function findByUsernameOrEmail($username)
    {
        return $this->where('username', $username)
                   ->orWhere('email', $username)
                   ->first();
    }

    public function getAllUsers($role = null)
    {
        if ($role) {
            return $this->where('role', $role)->findAll();
        }
        return $this->findAll();
    }

    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    public function toggleUserStatus($userId, $status)
    {
        return $this->update($userId, ['is_active' => $status]);
    }

    public function updateUser($userId, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        
        try {
            $builder->where('id', $userId);
            $result = $builder->update($data);
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'User update failed: ' . $e->getMessage());
            return false;
        }
    }

    public function isEmailUnique($email, $excludeUserId = null)
    {
        $builder = $this->where('email', $email);
        
        if ($excludeUserId) {
            $builder->where('id !=', $excludeUserId);
        }
        
        return $builder->countAllResults() === 0;
    }

    public function getPaginatedUsers($limit = 10, $offset = 0)
    {
        return $this->orderBy('created_at', 'DESC')
                ->limit($limit, $offset)
                ->findAll();
    }

    public function getPaginatedUsersByRole($role, $limit = 10, $offset = 0)
    {
        return $this->where('role', $role)
                ->orderBy('created_at', 'DESC')
                ->limit($limit, $offset)
                ->findAll();
    }

    public function getUsersCountByRole($role)
    {
        return $this->where('role', $role)->countAllResults();
    }

    public function createPasswordResetToken($email)
    {
        $user = $this->where('email', $email)->first();
        
        if (!$user) {
            return false;
        }

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $data = [
            'reset_token' => $tokenHash,
            'reset_token_expires' => $expires
        ];

        return $this->update($user['id'], $data) ? $token : false;
    }

    public function verifyResetToken($token)
    {
        if (!$token) {
            return false;
        }

        $tokenHash = hash('sha256', $token);
        $user = $this->where('reset_token', $tokenHash)
                    ->where('reset_token_expires >', date('Y-m-d H:i:s'))
                    ->first();

        return $user ? $user : false;
    }

    public function updatePassword($userId, $password)
    {
        $data = [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expires' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->update($userId, $data);
    }

    public function getActiveUsersCount($department = '')
    {
        $builder = $this->where('is_active', 1);
        
        if (!empty($department)) {
            $builder->where('department', $department);
        }
        
        return $builder->countAllResults();
    }
}