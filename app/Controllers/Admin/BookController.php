<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookModel;

class BookController extends BaseController
{
    protected $bookModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->bookModel = new BookModel();
        
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Buku',
            'user' => [
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'profile_image' => session()->get('profile_image') ?: 'default-profile.jpg'
            ],
            'books' => $this->bookModel->orderBy('created_at', 'DESC')->findAll(),
            'categories' => $this->bookModel->getBookCategories(),
            'stats' => $this->bookModel->getBookStats()
        ];

        return view('admin/books/index', $data);
    }

    public function store()
    {
        // Validation rules
        $rules = [
            'title' => 'required|max_length[255]',
            'author' => 'required|max_length[255]',
            'price' => 'required|decimal|greater_than[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'status' => 'required|in_list[active,inactive]',
            'cover_image' => 'uploaded[cover_image]|is_image[cover_image]|max_size[cover_image,2048]|ext_in[cover_image,jpg,jpeg,png,gif]'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $coverImage = $this->request->getFile('cover_image');
        $coverImageName = null;

        // Handle cover image upload
        if ($coverImage && $coverImage->isValid() && !$coverImage->hasMoved()) {
            $coverImageName = $coverImage->getRandomName();
            
            // Create directory if not exists
            $uploadPath = FCPATH . 'assets/images/covers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $coverImage->move($uploadPath, $coverImageName);
        }

        // Prepare data
        $data = [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'publisher' => $this->request->getPost('publisher'),
            'publication_year' => $this->request->getPost('publication_year') ?: null,
            'isbn' => $this->request->getPost('isbn'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'pages' => $this->request->getPost('pages') ?: null,
            'language' => $this->request->getPost('language') ?: 'Indonesian',
            'status' => $this->request->getPost('status'),
            'cover_image' => $coverImageName
        ];

        // Check ISBN uniqueness
        if ($data['isbn'] && $this->bookModel->isISBNExists($data['isbn'])) {
            session()->setFlashdata('error', 'ISBN sudah digunakan oleh buku lain');
            return redirect()->back()->withInput();
        }

        if ($this->bookModel->insert($data)) {
            session()->setFlashdata('success', 'Buku berhasil ditambahkan');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan buku');
        }

        return redirect()->to(base_url('admin/books'));
    }

    public function update($id)
    {
        $book = $this->bookModel->find($id);
        if (!$book) {
            session()->setFlashdata('error', 'Buku tidak ditemukan');
            return redirect()->to(base_url('admin/books'));
        }

        // Validation rules
        $rules = [
            'title' => 'required|max_length[255]',
            'author' => 'required|max_length[255]',
            'price' => 'required|decimal|greater_than[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'status' => 'required|in_list[active,inactive]'
        ];

        // Add cover image validation if uploaded
        $coverImage = $this->request->getFile('cover_image');
        if ($coverImage && $coverImage->isValid()) {
            $rules['cover_image'] = 'is_image[cover_image]|max_size[cover_image,2048]|ext_in[cover_image,jpg,jpeg,png,gif]';
        }

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        // Prepare data
        $data = [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'publisher' => $this->request->getPost('publisher'),
            'publication_year' => $this->request->getPost('publication_year') ?: null,
            'isbn' => $this->request->getPost('isbn'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'pages' => $this->request->getPost('pages') ?: null,
            'language' => $this->request->getPost('language') ?: 'Indonesian',
            'status' => $this->request->getPost('status')
        ];

        // Check ISBN uniqueness (exclude current book)
        if ($data['isbn'] && $this->bookModel->isISBNExists($data['isbn'], $id)) {
            session()->setFlashdata('error', 'ISBN sudah digunakan oleh buku lain');
            return redirect()->back()->withInput();
        }

        // Handle cover image upload
        if ($coverImage && $coverImage->isValid() && !$coverImage->hasMoved()) {
            $coverImageName = $coverImage->getRandomName();
            
            // Create directory if not exists
            $uploadPath = FCPATH . 'assets/images/covers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $coverImage->move($uploadPath, $coverImageName);
            
            // Delete old cover image
            if ($book['cover_image'] && file_exists($uploadPath . $book['cover_image'])) {
                unlink($uploadPath . $book['cover_image']);
            }
            
            $data['cover_image'] = $coverImageName;
        }

        if ($this->bookModel->update($id, $data)) {
            session()->setFlashdata('success', 'Buku berhasil diupdate');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate buku');
        }

        return redirect()->to(base_url('admin/books'));
    }

    public function delete($id)
    {
        $book = $this->bookModel->find($id);
        if (!$book) {
            session()->setFlashdata('error', 'Buku tidak ditemukan');
            return redirect()->to(base_url('admin/books'));
        }

        // Delete cover image file
        if ($book['cover_image']) {
            $coverPath = FCPATH . 'assets/images/covers/' . $book['cover_image'];
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        if ($this->bookModel->delete($id)) {
            session()->setFlashdata('success', 'Buku berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus buku');
        }

        return redirect()->to(base_url('admin/books'));
    }

    public function uploadCover($id)
    {
        $book = $this->bookModel->find($id);
        if (!$book) {
            session()->setFlashdata('error', 'Buku tidak ditemukan');
            return redirect()->to(base_url('admin/books'));
        }

        $rules = [
            'cover_image' => 'uploaded[cover_image]|is_image[cover_image]|max_size[cover_image,2048]|ext_in[cover_image,jpg,jpeg,png,gif]'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back();
        }

        $coverImage = $this->request->getFile('cover_image');
        
        if ($coverImage && $coverImage->isValid() && !$coverImage->hasMoved()) {
            $coverImageName = $coverImage->getRandomName();
            
            // Create directory if not exists
            $uploadPath = FCPATH . 'assets/images/covers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $coverImage->move($uploadPath, $coverImageName);
            
            // Delete old cover image
            if ($book['cover_image'] && file_exists($uploadPath . $book['cover_image'])) {
                unlink($uploadPath . $book['cover_image']);
            }
            
            if ($this->bookModel->update($id, ['cover_image' => $coverImageName])) {
                session()->setFlashdata('success', 'Cover buku berhasil diupload');
            } else {
                session()->setFlashdata('error', 'Gagal mengupload cover buku');
            }
        } else {
            session()->setFlashdata('error', 'File cover tidak valid');
        }

        return redirect()->to(base_url('admin/books'));
    }

    // API endpoint untuk mendapatkan data buku (untuk AJAX)
    public function getBook($id)
    {
        $book = $this->bookModel->find($id);
        
        if (!$book) {
            return $this->response->setJSON(['success' => false, 'message' => 'Buku tidak ditemukan']);
        }

        return $this->response->setJSON(['success' => true, 'data' => $book]);
    }

    // Method untuk toggle status buku
    public function toggleStatus($id)
    {
        $book = $this->bookModel->find($id);
        if (!$book) {
            return $this->response->setJSON(['success' => false, 'message' => 'Buku tidak ditemukan']);
        }

        $newStatus = $book['status'] === 'active' ? 'inactive' : 'active';
        
        if ($this->bookModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true, 
                'message' => 'Status buku berhasil diubah',
                'new_status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status buku']);
        }
    }
}