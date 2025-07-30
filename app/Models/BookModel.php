<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'title', 
        'author', 
        'publisher', 
        'publication_year', 
        'isbn', 
        'category', 
        'description', 
        'price', 
        'stock', 
        'cover_image', 
        'pages', 
        'language', 
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'author' => 'required|max_length[255]',
        'price' => 'required|decimal|greater_than[0]',
        'stock' => 'required|integer|greater_than_equal_to[0]',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Judul buku harus diisi',
            'max_length' => 'Judul buku maksimal 255 karakter'
        ],
        'author' => [
            'required' => 'Penulis harus diisi',
            'max_length' => 'Nama penulis maksimal 255 karakter'
        ],
        'price' => [
            'required' => 'Harga harus diisi',
            'decimal' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih dari 0'
        ],
        'stock' => [
            'required' => 'Stok harus diisi',
            'integer' => 'Stok harus berupa angka bulat',
            'greater_than_equal_to' => 'Stok tidak boleh negatif'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status harus active atau inactive'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom methods
    public function getActiveBooks()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getBooksByCategory($category)
    {
        return $this->where('category', $category)
                    ->where('status', 'active')
                    ->findAll();
    }

    public function searchBooks($keyword)
    {
        return $this->groupStart()
                    ->like('title', $keyword)
                    ->orLike('author', $keyword)
                    ->orLike('category', $keyword)
                    ->groupEnd()
                    ->where('status', 'active')
                    ->findAll();
    }

    public function getTotalBooks()
    {
        return $this->countAll();
    }

    public function getActiveBookCount()
    {
        return $this->where('status', 'active')->countAllResults();
    }

    public function getInactiveBookCount()
    {
        return $this->where('status', 'inactive')->countAllResults();
    }

    public function getTotalStock()
    {
        return $this->selectSum('stock')->first()['stock'] ?? 0;
    }

    public function getLowStockBooks($limit = 5)
    {
        try {
            return $this->where('stock <=', 5)
                       ->where('status', 'active')
                       ->orderBy('stock', 'ASC')
                       ->limit($limit)
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error in getLowStockBooks: ' . $e->getMessage());
            return [];
        }
    }

    public function getBookCategories()
    {
        return $this->select('category')
                    ->distinct()
                    ->where('category IS NOT NULL')
                    ->where('category !=', '')
                    ->orderBy('category', 'ASC')
                    ->findAll();
    }

    public function getRecentBooks($limit = 5)
    {
        try {
            return $this->orderBy('created_at', 'DESC')
                       ->limit($limit)
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error in getRecentBooks: ' . $e->getMessage());
            return [];
        }
    }

    public function updateStock($bookId, $quantity, $operation = 'subtract')
    {
        $book = $this->find($bookId);
        if (!$book) {
            return false;
        }

        $newStock = $operation === 'add' 
            ? $book['stock'] + $quantity 
            : $book['stock'] - $quantity;

        if ($newStock < 0) {
            return false; // Tidak boleh stok negatif
        }

        return $this->update($bookId, ['stock' => $newStock]);
    }

    public function isISBNExists($isbn, $excludeId = null)
    {
        $builder = $this->where('isbn', $isbn);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    // Method untuk statistik dashboard
    public function getBookStats()
    {
        try {
            // Total books
            $totalBooks = $this->countAll();
            
            // Active books
            $activeBooks = $this->where('status', 'active')->countAllResults(false);
            
            // Inactive books
            $inactiveBooks = $this->where('status', 'inactive')->countAllResults(false);
            
            // Total stock
            $totalStockResult = $this->selectSum('stock')->get()->getRow();
            $totalStock = $totalStockResult ? $totalStockResult->stock : 0;
            
            // Low stock count (assuming low stock is <= 5)
            $lowStockCount = $this->where('stock <=', 5)->where('status', 'active')->countAllResults(false);
            
            // Categories count
            $categoriesCount = $this->distinct()->select('category')->countAllResults(false);
            
            return [
                'total_books' => $totalBooks,
                'active_books' => $activeBooks,
                'inactive_books' => $inactiveBooks,
                'total_stock' => (int)$totalStock,
                'low_stock_count' => $lowStockCount,
                'categories_count' => $categoriesCount
            ];
            
        } catch (\Exception $e) {
            // Log error dan return default values
            log_message('error', 'Error in getBookStats: ' . $e->getMessage());
            
            return [
                'total_books' => 0,
                'active_books' => 0,
                'inactive_books' => 0,
                'total_stock' => 0,
                'low_stock_count' => 0,
                'categories_count' => 0
            ];
        }
    }
    public function getBookWithStock($id)
{
    $book = $this->find($id);
    if ($book) {
        $book['in_stock'] = $book['stock'] > 0;
        $book['stock_status'] = $book['stock'] > 5 ? 'available' : ($book['stock'] > 0 ? 'limited' : 'out_of_stock');
    }
    return $book;
}
public function isAvailable($bookId, $requestedQuantity = 1)
{
    $book = $this->find($bookId);
    
    if (!$book || $book['status'] !== 'active') {
        return false;
    }
    
    return $book['stock'] >= $requestedQuantity;
}

}