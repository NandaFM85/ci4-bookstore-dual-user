<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\CartModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $bookModel;
    protected $cartModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bookModel = new BookModel();
        $this->cartModel = new CartModel();
    }

    public function index()
    {
        $role = session()->get('role');
        
        if ($role === 'admin') {
            return redirect()->to('/dashboard/admin');
        } elseif ($role === 'user') {
            return redirect()->to('/dashboard/user');
        } else {
            return redirect()->to('/auth/login');
        }
    }

    public function admin()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login');
        }

        // Tambahkan error handling untuk getBookStats()
        $bookStats = $this->bookModel->getBookStats();
        
        // Debug: cek apa yang dikembalikan oleh getBookStats()
        // log_message('debug', 'Book Stats: ' . print_r($bookStats, true));
        
        // Validasi dan set default values jika data tidak ada
        if (!$bookStats || !is_array($bookStats)) {
            $bookStats = [
                'total_books' => 0,
                'active_books' => 0,
                'inactive_books' => 0,
                'total_stock' => 0,
                'low_stock_count' => 0,
                'categories_count' => 0
            ];
        }

        $totalUsers = $this->userModel->countAll();
        $adminUsers = $this->userModel->where('role', 'admin')->countAllResults();
        $regularUsers = $this->userModel->where('role', 'user')->countAllResults();

        // Gunakan null coalescing operator (??) untuk safety
        $stats = [
            'total_books' => $bookStats['total_books'] ?? 0,
            'active_books' => $bookStats['active_books'] ?? 0,
            'inactive_books' => $bookStats['inactive_books'] ?? 0,
            'total_stock' => $bookStats['total_stock'] ?? 0,
            'low_stock_count' => $bookStats['low_stock_count'] ?? 0,
            'categories_count' => $bookStats['categories_count'] ?? 0,
            'new_orders' => 0,
            'total_users' => $totalUsers,
            'admin_users' => $adminUsers,
            'regular_users' => $regularUsers
        ];

        $data = [
            'title' => 'Dashboard Admin',
            'user' => [
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'role' => session()->get('role'),
                'profile_image' => session()->get('profile_image')
            ],
            'stats' => $stats, // Gunakan $stats yang sudah divalidasi
            'recent_books' => $this->bookModel->getRecentBooks(5),
            'low_stock_books' => $this->bookModel->getLowStockBooks(5)
        ];

        return view('dashboard/admin', $data);
    }

    public function user()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'user') {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('id');  
        if (!$userId) {
            return redirect()->to('/auth/login');
        }  
        $cartItems = $this->cartModel->getCartItemsByUser($userId);
        $cartTotal = $this->cartModel->getCartTotal($userId);
        $recentBooks = $this->bookModel->getRecentBooks(8);
        $cartCount = $this->cartModel->getCartItemCount($userId);
        $cartSummary = $this->cartModel->getCartSummary($userId);  

        $data = [
            'title' => 'Dashboard User',
            'user' => [
                'id' => $userId,
                'username' => session()->get('username'),
                'fullname' => session()->get('fullname'),
                'email' => session()->get('email'),
                'role' => session()->get('role'),
                'profile_image' => session()->get('profile_image')
            ],
            'recent_books' => $recentBooks,
            'cart_count' => $cartCount,
            'total_items' => $cartSummary['total_items'],
        ];

        return view('dashboard/user', $data);
    }
}