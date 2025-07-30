<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;    
    protected $allowedFields = [
        'username', 'email', 'password', 'role', 'fullname', 'address', 'profile_image', 'created_at', 'updated_at'
    ];
    
    // Enable timestamps
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation rules untuk create (bukan untuk update)
    protected $validationRules = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    protected $validationMessages = [
        'username' => [
            'required' => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'max_length' => 'Username maksimal 50 karakter',
            'is_unique' => 'Username sudah digunakan'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter'
        ]
    ];
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

        protected function beforeInsert(array $data)
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function createUser($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['role'] = $data['role'] ?? 'user'; // Default role untuk user baru
        $data['profile_image'] = $data['profile_image'] ?? 'default-profile.jpg';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->insert($data);
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
    
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }
    
    // Override update method untuk debugging
    public function update($id = null, $data = null): bool
    {
        // Add updated_at if not present
        if (is_array($data) && !isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        // Log the update attempt
        log_message('info', 'UserModel update attempt - ID: ' . $id . ', Data: ' . json_encode($data));
        
        try {
            $result = parent::update($id, $data);
            log_message('info', 'UserModel update result: ' . ($result ? 'success' : 'failed'));
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'UserModel update exception: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // Override insert method untuk debugging
    public function insert($data = null, bool $returnID = true)
    {
        // Add timestamps if not present
        if (is_array($data)) {
            if (!isset($data['created_at'])) {
                $data['created_at'] = date('Y-m-d H:i:s');
            }
            if (!isset($data['updated_at'])) {
                $data['updated_at'] = date('Y-m-d H:i:s');
            }
        }
        
        log_message('info', 'UserModel insert attempt - Data: ' . json_encode($data));
        
        try {
            $result = parent::insert($data, $returnID);
            log_message('info', 'UserModel insert result: ' . ($result ? 'success' : 'failed'));
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'UserModel insert exception: ' . $e->getMessage());
            throw $e;
        }
    }
    public function isUsernameExists($username, $excludeId = null)
    {
        $builder = $this->where('username', $username);
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }

    /**
     * Check if email exists (excluding specific ID)
     */
    public function isEmailExists($email, $excludeId = null)
    {
        $builder = $this->where('email', $email);
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }

    /**
     * Update user profile image
     */
    public function updateProfileImage($userId, $imageName)
    {
        return $this->update($userId, [
            'profile_image' => $imageName,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

       public function searchUsers($keyword)
    {
        return $this->groupStart()
                    ->like('username', $keyword)
                    ->orLike('email', $keyword)
                    ->orLike('fullname', $keyword)
                    ->groupEnd()
                    ->findAll();
    }

    /**
     * Get user statistics
     */
    public function getUserStats()
    {
        $totalUsers = $this->countAll();
        $adminCount = $this->where('role', 'admin')->countAllResults();
        $userCount = $this->where('role', 'user')->countAllResults();

        return [
            'total' => $totalUsers,
            'admin' => $adminCount,
            'user' => $userCount
        ];
    }

        public function updatePassword($userId, $newPassword)
    {
        return $this->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }



}