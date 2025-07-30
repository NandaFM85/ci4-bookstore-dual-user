<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak');
        }

        $users = $this->userModel->findAll();
        
        $data = [
            'title' => 'Manajemen Pengguna',
            'user' => [
                'username' => session()->get('username'),
                'email' => session()->get('email'),
                'fullname' => session()->get('fullname'),
                'profile_image' => session()->get('profile_image') ?: 'default-profile.jpg'
            ],
            'users' => $users ?: []
        ];

        return view('admin/users/index', $data);
    }

    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak');
        }

        // Get and validate input
        $username = trim($this->request->getPost('username'));
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $fullname = trim($this->request->getPost('fullname'));
        $address = trim($this->request->getPost('address'));
        $role = $this->request->getPost('role');

        // Manual validation
        if (empty($username) || strlen($username) < 3) {
            return redirect()->to('/admin/users')->with('error', 'Username minimal 3 karakter');
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->to('/admin/users')->with('error', 'Email tidak valid');
        }

        if (empty($password) || strlen($password) < 6) {
            return redirect()->to('/admin/users')->with('error', 'Password minimal 6 karakter');
        }

        if (!in_array($role, ['admin', 'user'])) {
            return redirect()->to('/admin/users')->with('error', 'Role harus dipilih');
        }

        // Check unique username
        if ($this->userModel->where('username', $username)->first()) {
            return redirect()->to('/admin/users')->with('error', 'Username sudah digunakan');
        }

        // Check unique email
        if ($this->userModel->where('email', $email)->first()) {
            return redirect()->to('/admin/users')->with('error', 'Email sudah digunakan');
        }

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'fullname' => $fullname ?: null,
            'address' => $address ?: null,
            'role' => $role,
            'profile_image' => 'default-profile.jpg'
        ];

        try {
            if ($this->userModel->insert($data)) {
                return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil ditambahkan');
            } else {
                return redirect()->to('/admin/users')->with('error', 'Gagal menambahkan pengguna');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error adding user: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak');
        }

        // Validate ID
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->to('/admin/users')->with('error', 'ID pengguna tidak valid');
        }

        $userData = $this->userModel->find($id);
        if (!$userData) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }

        // Get POST data and trim whitespace
        $username = trim($this->request->getPost('username'));
        $email = trim($this->request->getPost('email'));
        $fullname = trim($this->request->getPost('fullname'));
        $address = trim($this->request->getPost('address'));
        $role = $this->request->getPost('role');
        $password = $this->request->getPost('password');

        // Basic validation
        if (empty($username) || strlen($username) < 3) {
            return redirect()->to('/admin/users')->with('error', 'Username minimal 3 karakter');
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->to('/admin/users')->with('error', 'Email tidak valid');
        }

        if (!in_array($role, ['admin', 'user'])) {
            return redirect()->to('/admin/users')->with('error', 'Role tidak valid');
        }

        // Check for unique username (excluding current user)
        if ($username !== $userData['username']) {
            if ($this->userModel->where('username', $username)->where('id !=', $id)->first()) {
                return redirect()->to('/admin/users')->with('error', 'Username sudah digunakan oleh pengguna lain');
            }
        }

        // Check for unique email (excluding current user)
        if ($email !== $userData['email']) {
            if ($this->userModel->where('email', $email)->where('id !=', $id)->first()) {
                return redirect()->to('/admin/users')->with('error', 'Email sudah digunakan oleh pengguna lain');
            }
        }

        // Password validation if provided
        if (!empty($password) && strlen($password) < 6) {
            return redirect()->to('/admin/users')->with('error', 'Password minimal 6 karakter');
        }

        // Prepare data for update
        $updateData = [
            'username' => $username,
            'email' => $email,
            'fullname' => $fullname ?: null,
            'address' => $address ?: null,
            'role' => $role
        ];

        // Update password only if provided
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            $result = $this->userModel->update($id, $updateData);
            
            if ($result !== false) {
                return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil diperbarui');
            } else {
                return redirect()->to('/admin/users')->with('error', 'Gagal memperbarui pengguna');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during user update: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak');
        }

        // Prevent admin from deleting themselves
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $userData = $this->userModel->find($id);
        if (!$userData) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }

        // Delete profile image if not default
        if ($userData['profile_image'] && $userData['profile_image'] !== 'default-profile.jpg') {
            $imagePath = FCPATH . 'assets/images/profiles/' . $userData['profile_image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus');
        } else {
            return redirect()->to('/admin/users')->with('error', 'Gagal menghapus pengguna');
        }
    }

    public function uploadProfile($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak');
        }

        $userData = $this->userModel->find($id);
        if (!$userData) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }

        $file = $this->request->getFile('profile_image');
        
        if (!$file || !$file->isValid()) {
            return redirect()->to('/admin/users')->with('error', 'File tidak valid atau tidak ditemukan');
        }

        // Manual validation
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 2048 * 1024; // 2MB in bytes

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return redirect()->to('/admin/users')->with('error', 'Format file harus JPG, JPEG, atau PNG');
        }

        if ($file->getSize() > $maxSize) {
            return redirect()->to('/admin/users')->with('error', 'Ukuran file maksimal 2MB');
        }

        // Create directory if not exists
        $uploadPath = FCPATH . 'assets/images/profiles/';
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true)) {
                return redirect()->to('/admin/users')->with('error', 'Gagal membuat direktori upload');
            }
        }

        // Delete old profile image if not default
        if ($userData['profile_image'] && $userData['profile_image'] !== 'default-profile.jpg') {
            $oldImagePath = $uploadPath . $userData['profile_image'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Generate new filename with timestamp to avoid conflicts
        $extension = $file->getExtension();
        $newName = 'profile_' . $id . '_' . time() . '.' . $extension;
        
        try {
            if ($file->move($uploadPath, $newName)) {
                // Update database
                $updateResult = $this->userModel->update($id, [
                    'profile_image' => $newName
                ]);
                
                if ($updateResult !== false) {
                    return redirect()->to('/admin/users')->with('success', 'Foto profil berhasil diperbarui');
                } else {
                    // Delete uploaded file if database update fails
                    $newImagePath = $uploadPath . $newName;
                    if (file_exists($newImagePath)) {
                        unlink($newImagePath);
                    }
                    return redirect()->to('/admin/users')->with('error', 'Gagal menyimpan foto profil ke database');
                }
            } else {
                return redirect()->to('/admin/users')->with('error', 'Gagal mengupload foto profil');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during profile upload: ' . $e->getMessage());
            return redirect()->to('/admin/users')->with('error', 'Terjadi kesalahan saat upload: ' . $e->getMessage());
        }
    }
}