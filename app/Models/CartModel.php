<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'book_id',
        'quantity'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'book_id' => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID harus diisi',
            'integer' => 'User ID harus berupa angka'
        ],
        'book_id' => [
            'required' => 'Book ID harus diisi',
            'integer' => 'Book ID harus berupa angka'
        ],
        'quantity' => [
            'required' => 'Quantity harus diisi',
            'integer' => 'Quantity harus berupa angka',
            'greater_than' => 'Quantity harus lebih dari 0'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Get cart items for specific user with book details - FIXED
    public function getCartItemsByUser($userId)
    {
        $userId = (int) $userId; // Type cast
        
        // REMOVED books.status condition since the column doesn't exist
        return $this->select('cart_items.*, books.title, books.author, books.price, books.cover_image, books.stock')
                    ->join('books', 'books.id = cart_items.book_id')
                    ->where('cart_items.user_id', $userId)
                    ->findAll();
    }

    // Add item to cart
    public function addToCart($userId, $bookId, $quantity = 1)
    {
        // Type cast semua parameter
        $userId = (int) $userId;
        $bookId = (int) $bookId;
        $quantity = (int) $quantity;

        // Check if item already exists in cart
        $existingItem = $this->where('user_id', $userId)
                            ->where('book_id', $bookId)
                            ->first();

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            return $this->update($existingItem['id'], ['quantity' => $newQuantity]);
        } else {
            // Insert new item
            return $this->insert([
                'user_id' => $userId,
                'book_id' => $bookId,
                'quantity' => $quantity
            ]);
        }
    }

    // Update cart item quantity
    public function updateCartQuantity($userId, $bookId, $quantity)
    {
        // Type cast semua parameter
        $userId = (int) $userId;
        $bookId = (int) $bookId;
        $quantity = (int) $quantity;

        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $bookId);
        }

        return $this->where('user_id', $userId)
                    ->where('book_id', $bookId)
                    ->set(['quantity' => $quantity])
                    ->update();
    }

    // Remove item from cart
    public function removeFromCart($userId, $bookId)
    {
        // Type cast parameter
        $userId = (int) $userId;
        $bookId = (int) $bookId;

        return $this->where('user_id', $userId)
                    ->where('book_id', $bookId)
                    ->delete();
    }

    // Get cart total for user
    public function getCartTotal($userId)
    {
        $userId = (int) $userId; // Type cast
        $items = $this->getCartItemsByUser($userId);
        $total = 0;

        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    // Get cart item count for user
    public function getCartItemCount($userId)
    {
        $userId = (int) $userId; // Type cast
        return $this->where('user_id', $userId)->countAllResults();
    }

    // Clear cart for user
    public function clearCart($userId)
    {
        $userId = (int) $userId; // Type cast
        return $this->where('user_id', $userId)->delete();
    }

    // Check if book is in cart
    public function isInCart($userId, $bookId)
    {
        $userId = (int) $userId; // Type cast
        $bookId = (int) $bookId; // Type cast
        
        return $this->where('user_id', $userId)
                    ->where('book_id', $bookId)
                    ->countAllResults() > 0;
    }

    // Get cart summary - FIXED to handle empty carts properly
    public function getCartSummary($userId)
    {
        $userId = (int) $userId; // Type cast
        
        try {
            $items = $this->getCartItemsByUser($userId);
            $totalItems = 0;
            $totalPrice = 0;

            if (!empty($items)) {
                foreach ($items as $item) {
                    $totalItems += (int) $item['quantity'];
                    $totalPrice += ((float) $item['price'] * (int) $item['quantity']);
                }
            }

            return [
                'items' => $items,
                'total_items' => $totalItems,
                'total_price' => $totalPrice
            ];
        } catch (\Exception $e) {
            log_message('error', 'CartModel getCartSummary error: ' . $e->getMessage());
            return [
                'items' => [],
                'total_items' => 0,
                'total_price' => 0
            ];
        }
    }

    public function getCartItemSummary($userId)
    {
        $userId = (int) $userId; // Type cast
        $cartItems = $this->getCartItems($userId);
        
        $totalItems = 0;
        $totalPrice = 0;
        
        foreach ($cartItems as $item) {
            $totalItems += (int) $item['quantity'];
            $totalPrice += ((float) $item['price'] * (int) $item['quantity']);
        }
        
        return [
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
            'item_count' => count($cartItems)
        ];
    }

    // FIXED getCartItems method
    public function getCartItems($userId)
    {
        $userId = (int) $userId; // Type cast
        
        try {
            $builder = $this->db->table('cart_items');
            return $builder->select('cart_items.*, books.title, books.author, books.price, books.cover_image, books.stock, books.category')
                           ->join('books', 'books.id = cart_items.book_id')
                           ->where('cart_items.user_id', $userId)
                           ->orderBy('cart_items.created_at', 'DESC')
                           ->get()
                           ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'CartModel getCartItems error: ' . $e->getMessage());
            return [];
        }
    }
}