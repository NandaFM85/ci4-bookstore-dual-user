-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2025 at 07:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_buku_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `cover_image` varchar(255) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `language` varchar(50) DEFAULT 'Indonesian',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `publisher`, `publication_year`, `isbn`, `category`, `description`, `price`, `stock`, `cover_image`, `pages`, `language`, `status`, `created_at`, `updated_at`) VALUES
(4, 'A', 'H', 'Republika', '2004', '9789793885029', 'Religius', 'Novel romantis bernuansa islami', 95600.00, 54, '1753405048_fec6a9b96f95fbaccbdc.jpg', 418, 'Indonesian', 'active', '2025-07-24 18:51:06', '2025-07-24 17:58:04'),
(8, 'The Art of Thinking Clearly', 'Rolf Dobelli', 'Harper', '2013', '9780062219695', 'Self-help', 'A book about cognitive biases.', 150000.00, 10, 'cover1.jpg', 384, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(9, 'Sapiens', 'Yuval Noah Harari', 'Harvill Secker', '2014', '9780062316097', 'History', 'A brief history of humankind.', 200000.00, 15, 'cover2.jpg', 512, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(10, 'Atomic Habits', 'James Clear', 'Penguin', '2018', '9780735211292', 'Productivity', 'Build good habits and break bad ones.', 175000.00, 20, 'cover3.jpg', 320, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(11, 'Rich Dad Poor Dad', 'Robert Kiyosaki', 'Warner Books', '2000', '9781612680194', 'Finance', 'What the rich teach their kids about money.', 130000.00, 12, 'cover4.jpg', 336, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(12, 'Filosofi Teras', 'Henry Manampiring', 'Kompas', '2018', '9786024125189', 'Philosophy', 'Filsafat Yunani untuk mental tangguh.', 95000.00, 8, 'cover5.jpg', 300, 'Indonesian', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(13, 'Thinking, Fast and Slow', 'Daniel Kahneman', 'Farrar', '2011', '9780374275631', 'Psychology', 'Two systems of thinking.', 180000.00, 7, 'cover6.jpg', 499, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(14, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', '9789793062793', 'Novel', 'Inspirasi dari Belitung.', 85000.00, 25, 'cover7.jpg', 529, 'Indonesian', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(15, 'To Kill a Mockingbird', 'Harper Lee', 'J. B. Lippincott & Co.', '1960', '9780446310789', 'Classic', 'A novel about racism and injustice.', 145000.00, 5, 'cover8.jpg', 324, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(16, '1984', 'George Orwell', 'Secker & Warburg', '1949', '9780451524935', 'Dystopia', 'A totalitarian future.', 125000.00, 18, 'cover9.jpg', 328, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(17, 'The Subtle Art of Not Giving a F*ck', 'Mark Manson', 'HarperOne', '2016', '9780062457714', 'Self-help', 'A counterintuitive approach to living a good life.', 160000.00, 14, 'cover10.jpg', 224, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(18, 'Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia', '2009', '9789792238625', 'Novel', 'Inspirasi dari pondok pesantren.', 90000.00, 10, 'cover11.jpg', 400, 'Indonesian', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(19, 'A Brief History of Time', 'Stephen Hawking', 'Bantam Books', '1988', '9780553380163', 'Science', 'From the Big Bang to black holes.', 140000.00, 6, 'cover12.jpg', 212, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(20, 'Dilan 1990', 'Pidi Baiq', 'Pastel Books', '2014', '9786027870406', 'Romance', 'Cinta remaja tahun 90-an.', 80000.00, 13, 'cover13.jpg', 346, 'Indonesian', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(21, 'Bumi', 'Tere Liye', 'Gramedia', '2014', '9786020304199', 'Fantasy', 'Petualangan Raib dan kawan-kawan.', 95000.00, 16, 'cover14.jpg', 440, 'Indonesian', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(22, 'The Psychology of Money', 'Morgan Housel', 'Harriman House', '2020', '9780857197689', 'Finance', 'Lessons on wealth, greed and happiness.', 155000.00, 9, 'cover15.jpg', 252, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(23, 'Totto-chan', 'Tetsuko Kuroyanagi', 'Gramedia', '1981', '9786020326986', 'Biography', 'Gadis cilik di jendela.', 105000.00, 7, 'cover16.jpg', 272, 'Indonesian', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(24, 'The Alchemist', 'Paulo Coelho', 'HarperOne', '1988', '9780061122415', 'Fiction', 'A journey of self-discovery.', 120000.00, 11, 'cover17.jpg', 208, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(25, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'Bloomsbury', '1997', '9780747532699', 'Fantasy', 'The beginning of the Harry Potter series.', 185000.00, 30, 'cover18.jpg', 332, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(26, 'Ayat-Ayat Cinta', 'Habiburrahman El Shirazy', 'Republika', '2004', '9789793604078', 'Romance', 'Kisah cinta dan iman.', 90000.00, 17, 'cover19.jpg', 420, 'Indonesian', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23'),
(27, 'Inferno', 'Dan Brown', 'Doubleday', '2013', '9780385537858', 'Thriller', 'Robert Langdon\'s adventure in Italy.', 165000.00, 19, 'cover20.jpg', 480, 'English', 'active', '2025-07-25 20:00:23', '2025-07-25 20:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `payment_account` varchar(100) DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `status` enum('pending_payment','pending_verification','approved','on_shipping','completed','cancelled') DEFAULT 'pending_payment',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `total_amount`, `payment_method`, `payment_account`, `receipt_image`, `tracking_number`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 7, 'ORD-20250726-5544', 1550000.00, 1, NULL, 'dummy_receipt_1_1753500336.png', NULL, 'completed', NULL, '2025-07-25 18:41:45', '2025-07-25 20:25:36'),
(16, 7, 'ORD-20250726-0935', 1550000.00, 1, NULL, 'dummy_receipt_16_1753500325.png', NULL, 'completed', NULL, '2025-07-25 19:34:55', '2025-07-25 20:25:25'),
(17, 7, 'ORD-20250726-6037', 640000.00, 3, NULL, 'dummy_receipt_17_1753502566.png', NULL, 'completed', NULL, '2025-07-25 20:54:49', '2025-07-25 21:02:46'),
(18, 9, 'ORD-20250726-3853', 510000.00, 2, NULL, 'dummy_receipt_18_1753506992.png', NULL, 'completed', NULL, '2025-07-25 22:16:27', '2025-07-25 22:16:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `book_id`, `quantity`, `price`, `created_at`) VALUES
(1, 16, 10, 2, 175000.00, '2025-07-25 19:34:55'),
(2, 16, 27, 2, 165000.00, '2025-07-25 19:34:55'),
(3, 16, 17, 1, 160000.00, '2025-07-25 19:34:55'),
(4, 16, 8, 1, 150000.00, '2025-07-25 19:34:55'),
(5, 16, 9, 1, 200000.00, '2025-07-25 19:34:55'),
(6, 16, 12, 1, 95000.00, '2025-07-25 19:34:55'),
(7, 16, 13, 1, 180000.00, '2025-07-25 19:34:55'),
(8, 16, 14, 1, 85000.00, '2025-07-25 19:34:55'),
(9, 17, 8, 1, 150000.00, '2025-07-25 20:54:49'),
(10, 17, 11, 1, 130000.00, '2025-07-25 20:54:49'),
(11, 17, 12, 1, 95000.00, '2025-07-25 20:54:49'),
(12, 17, 13, 1, 180000.00, '2025-07-25 20:54:49'),
(13, 17, 14, 1, 85000.00, '2025-07-25 20:54:49'),
(14, 18, 8, 1, 150000.00, '2025-07-25 22:16:27'),
(15, 18, 12, 1, 95000.00, '2025-07-25 22:16:27'),
(16, 18, 13, 1, 180000.00, '2025-07-25 22:16:27'),
(17, 18, 14, 1, 85000.00, '2025-07-25 22:16:27');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('bank','ewallet') NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `type`, `account_number`, `account_name`, `logo`, `is_active`, `created_at`) VALUES
(1, 'Bank BCA', 'bank', '1234567890', 'Toko Buku Online', 'bca.png', 1, '2025-07-25 20:12:57'),
(2, 'Bank Mandiri', 'bank', '0987654321', 'Toko Buku Online', 'mandiri.png', 1, '2025-07-25 20:12:57'),
(3, 'GoPay', 'ewallet', '081234567890', 'Toko Buku Online', 'gopay.png', 1, '2025-07-25 20:12:57'),
(4, 'OVO', 'ewallet', '081234567890', 'Toko Buku Online', 'ovo.png', 1, '2025-07-25 20:12:57'),
(5, 'DANA', 'ewallet', '081234567890', 'Toko Buku Online', 'dana.png', 1, '2025-07-25 20:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default-profile.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `fullname`, `address`, `profile_image`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrator User', '123 Admin Street, Admin City', 'default-profile.jpg', '2025-07-24 17:24:30', '2025-07-24 18:26:24'),
(4, 'jul1', 'jul@gmail.com', '$2y$10$xqIVft978dDT8aGJAy3n0OqYiql1RJxnv.41y3onLwnhhoqEXWhWO', 'user', 'jul', 'jl priok2', '1753381916_b7ee4cc0d9dda1bc88b2.png', '2025-07-24 11:26:59', '2025-07-25 11:35:34'),
(5, 'test', 'tes@gmail.com', '$2y$10$TD1Kogw1VBKY2MKg/ZSjy.KxcXINCjDydIkJLUxW895EXMSL/b8zG', 'user', 'tes', NULL, 'default-profile.jpg', '2025-07-24 11:29:20', '2025-07-24 11:29:20'),
(6, 'test12', 'test1@gmail.com', '$2y$10$PG/BYTLTGVmyz81WhFBeWOh88xBCUgtdkLIq5Tg2nL9DEtHet8y66', 'user', 'test1', NULL, 'default-profile.jpg', '2025-07-24 11:30:19', '2025-07-24 11:30:19'),
(7, 'SORA', 'sora@sky.com', '$2y$10$kRbJQe91fib.sNBZkz6YMOGvsQ1G7ReA/E2EtlmoPQZ5Zy2HevPqy', 'user', 'SORA', '', '1753470138_9dbc0d3e51cf156bd517.webp', '2025-07-24 22:57:19', '2025-07-25 19:34:46'),
(8, 'testsys', 'test@sys.com', '$2y$10$Min37ZB5idUJuX2Z7GzJmuHIeHamJn3gwBR6kvZXaGzRu8LmQYDSO', 'admin', 'testsys', 'testing request 1', '1753469265_3da68b3287e5df99cf95.png', '2025-07-25 11:47:05', '2025-07-25 11:49:14'),
(9, 'fadhli', 'fadhli@gmail.com', '$2y$10$f0NZ/jbaeAt.YdWkM9PzJOkXlRM1kFrs7eB7rbtt5LLrcUleCbTLC', 'user', 'fadhli', NULL, 'default-profile.jpg', '2025-07-25 22:09:39', '2025-07-25 22:09:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_title` (`title`),
  ADD KEY `idx_author` (`author`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_book` (`user_id`,`book_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
