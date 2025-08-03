<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\BookModel;
use App\Models\CartModel;
use App\Models\OrderModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $bookModel;
    protected $cartModel;
    protected $orderModel; // Tambahkan OrderModel

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bookModel = new BookModel();
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel(); // Inisialisasi OrderModel
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

        // Get order data untuk statistik dengan error handling
        try {
            $orders = $this->orderModel->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error getting orders: ' . $e->getMessage());
            $orders = [];
        }
        
        // Pastikan $orders adalah array
        if (!is_array($orders)) {
            $orders = [];
        }

        // Inisialisasi variables dengan nilai default
        $totalRevenue = 0;
        $completedOrders = 0;
        $pendingOrders = 0;
        $totalInvoices = count($orders);

        // Hitung revenue statistics dengan validasi ketat
        if (!empty($orders)) {
            foreach ($orders as $order) {
                // Validasi dan konversi total_amount
                $amount = 0;
                if (isset($order['total_amount'])) {
                    // Pastikan nilai adalah numeric
                    if (is_numeric($order['total_amount'])) {
                        $amount = (float) $order['total_amount'];
                    }
                }
                
                // Tambahkan ke total revenue jika valid
                if ($amount > 0) {
                    $totalRevenue += $amount;
                }

                // Hitung completed orders
                if (isset($order['status']) && $order['status'] === 'completed') {
                    $completedOrders++;
                }

                // Hitung pending orders
                if (isset($order['status']) && in_array($order['status'], ['pending_payment', 'pending_verification'], true)) {
                    $pendingOrders++;
                }
            }
        }

        // Pastikan semua nilai adalah numeric dan tidak NaN
        $totalRevenue = is_numeric($totalRevenue) ? $totalRevenue : 0;
        $completedOrders = is_numeric($completedOrders) ? $completedOrders : 0;
        $pendingOrders = is_numeric($pendingOrders) ? $pendingOrders : 0;

        // Get user statistics dengan error handling
        try {
            $totalUsers = $this->userModel->countAll();
            $adminUsers = $this->userModel->where('role', 'admin')->countAllResults();
            $regularUsers = $this->userModel->where('role', 'user')->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error getting user stats: ' . $e->getMessage());
            $totalUsers = 0;
            $adminUsers = 0;
            $regularUsers = 0;
        }

        // Pastikan semua nilai user stats adalah numeric
        $totalUsers = is_numeric($totalUsers) ? (int) $totalUsers : 0;
        $adminUsers = is_numeric($adminUsers) ? (int) $adminUsers : 0;
        $regularUsers = is_numeric($regularUsers) ? (int) $regularUsers : 0;

        // Stats dengan validasi ketat untuk mencegah NaN
        $stats = [
            'total_books' => is_numeric($bookStats['total_books'] ?? 0) ? (int) ($bookStats['total_books'] ?? 0) : 0,
            'total_revenue' => $totalRevenue, // Sudah divalidasi di atas
            'active_books' => is_numeric($bookStats['active_books'] ?? 0) ? (int) ($bookStats['active_books'] ?? 0) : 0,
            'inactive_books' => is_numeric($bookStats['inactive_books'] ?? 0) ? (int) ($bookStats['inactive_books'] ?? 0) : 0,
            'total_stock' => is_numeric($bookStats['total_stock'] ?? 0) ? (int) ($bookStats['total_stock'] ?? 0) : 0,
            'low_stock_count' => is_numeric($bookStats['low_stock_count'] ?? 0) ? (int) ($bookStats['low_stock_count'] ?? 0) : 0,
            'categories_count' => is_numeric($bookStats['categories_count'] ?? 0) ? (int) ($bookStats['categories_count'] ?? 0) : 0,
            'total_invoices' => $totalInvoices, // Sudah divalidasi (count array)
            'completed_orders' => $completedOrders, // Sudah divalidasi di atas
            'pending_orders' => $pendingOrders, // Sudah divalidasi di atas
            'new_orders' => $pendingOrders, // Menggunakan pending orders sebagai new orders
            'total_users' => $totalUsers,
            'admin_users' => $adminUsers,
            'regular_users' => $regularUsers
        ];

        // Debug: Log stats untuk troubleshooting (hapus di production)
        // log_message('debug', 'Dashboard Stats: ' . print_r($stats, true));

        // Get data dengan error handling
        try {
            $recentBooks = $this->bookModel->getRecentBooks(5);
            $lowStockBooks = $this->bookModel->getLowStockBooks(5);
        } catch (\Exception $e) {
            log_message('error', 'Error getting books data: ' . $e->getMessage());
            $recentBooks = [];
            $lowStockBooks = [];
        }

        $data = [
            'title' => 'Dashboard Admin',
            'user' => [
                'username' => session()->get('username') ?? 'Unknown',
                'fullname' => session()->get('fullname') ?? 'Unknown User',
                'email' => session()->get('email') ?? 'no-email@example.com',
                'role' => session()->get('role') ?? 'admin',
                'profile_image' => session()->get('profile_image') ?? 'default-profile.jpg'
            ],
            'stats' => $stats,
            'recent_books' => $recentBooks,
            'low_stock_books' => $lowStockBooks
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

    // Method tambahan untuk mendapatkan revenue stats (opsional)
    private function getRevenueStats()
    {
        try {
            $orders = $this->orderModel->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error in getRevenueStats: ' . $e->getMessage());
            return [
                'total_revenue' => 0,
                'completed_revenue' => 0,
                'pending_revenue' => 0
            ];
        }
        
        if (!is_array($orders) || empty($orders)) {
            return [
                'total_revenue' => 0,
                'completed_revenue' => 0,
                'pending_revenue' => 0
            ];
        }

        $totalRevenue = 0;
        $completedRevenue = 0;
        $pendingRevenue = 0;

        foreach ($orders as $order) {
            $amount = 0;
            if (isset($order['total_amount']) && is_numeric($order['total_amount'])) {
                $amount = (float) $order['total_amount'];
            }

            $status = $order['status'] ?? '';

            $totalRevenue += $amount;

            if ($status === 'completed') {
                $completedRevenue += $amount;
            } elseif (in_array($status, ['pending_payment', 'pending_verification'], true)) {
                $pendingRevenue += $amount;
            }
        }

        return [
            'total_revenue' => $totalRevenue,
            'completed_revenue' => $completedRevenue,
            'pending_revenue' => $pendingRevenue
        ];
    }

    // Helper function untuk format revenue (gunakan di view)
    public static function formatRevenue($amount)
    {
        if (!is_numeric($amount) || $amount == 0) {
            return '0';
        }

        $amount = (float) $amount;

        if ($amount >= 1000000000) {
            return number_format($amount / 1000000000, 1, ',', '.') . 'M'; // Miliar
        } elseif ($amount >= 1000000) {
            return number_format($amount / 1000000, 1, ',', '.') . 'Jt'; // Juta
        } elseif ($amount >= 1000) {
            return number_format($amount / 1000, 0, ',', '.') . 'K'; // Ribu
        }
        
        return number_format($amount, 0, ',', '.');
    }
}