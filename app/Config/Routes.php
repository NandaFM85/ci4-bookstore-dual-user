<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - redirect to login
$routes->get('/', 'Auth::login');

// Auth Routes - tanpa group dulu untuk testing
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::attemptLogin');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register', 'Auth::attemptRegister');
$routes->get('auth/logout', 'Auth::logout');

// Direct routes untuk testing
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');

// Dashboard Routes
$routes->get('dashboard/admin', 'Dashboard::admin', ['filter' => 'auth']);
$routes->get('dashboard/user', 'Dashboard::user', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

$routes->get('user/catalog', 'User::catalog', ['filter' => 'auth']);
$routes->get('user/cart', 'User::cart', ['filter' => 'auth']);
$routes->get('user/addToCart', 'User::addToCart', ['filter' => 'auth']);
$routes->get('user/updateCart', 'User::updateCart', ['filter' => 'auth']);
$routes->get('user/removeFromCart', 'User::removeFromCart', ['filter' => 'auth']);
$routes->get('user/clearCart', 'User::clearCart', ['filter' => 'auth']); 

//profile routes 
$routes->get('user/profile', 'User::profile');
$routes->post('user/updateProfile', 'User::updateProfile');
$routes->get('user/changePassword', 'User::changePassword');
$routes->post('user/updatePassword', 'User::updatePassword');

// User Management Routes - tanpa group
$routes->get('admin/users', 'Admin\UserController::index', ['filter' => 'auth']);
$routes->post('admin/users/store', 'Admin\UserController::store', ['filter' => 'auth']);
$routes->post('admin/users/update/(:num)', 'Admin\UserController::update/$1', ['filter' => 'auth']);
$routes->post('admin/users/delete/(:num)', 'Admin\UserController::delete/$1', ['filter' => 'auth']);
$routes->post('admin/users/upload-profile/(:num)', 'Admin\UserController::uploadProfile/$1', ['filter' => 'auth']);
$routes->post('admin/users/store', 'Admin\UserController::store', ['filter' => 'auth']);
$routes->post('admin/users/update/(:num)', 'Admin\UserController::update/$1', ['filter' => 'auth']);
$routes->post('admin/users/delete/(:num)', 'Admin\UserController::delete/$1', ['filter' => 'auth']);
$routes->post('admin/users/upload-profile/(:num)', 'Admin\UserController::uploadProfile/$1', ['filter' => 'auth']);

$routes->get('admin/books', 'Admin\BookController::index', ['filter' => 'auth']);
$routes->post('admin/books/store', 'Admin\BookController::store', ['filter' => 'auth']);
$routes->post('admin/books/update/(:num)', 'Admin\BookController::update/$1', ['filter' => 'auth']);
$routes->post('admin/books/delete/(:num)', 'Admin\BookController::delete/$1', ['filter' => 'auth']);
$routes->post('admin/books/upload-cover/(:num)', 'Admin\BookController::uploadCover/$1', ['filter' => 'auth']);
$routes->get('admin/books/get/(:num)', 'Admin\BookController::getBook/$1', ['filter' => 'auth']);
$routes->post('admin/books/toggle-status/(:num)', 'Admin\BookController::toggleStatus/$1', ['filter' => 'auth']);

$routes->get('user/checkout', 'User::checkout');
$routes->get('user/processCheckout', 'User::processCheckout', ['filter' => 'nocsrf']); // Use custom filter
$routes->get('user/payment/(:num)', 'User::payment/$1');
$routes->get('scan-barcode/(:num)', 'User::scanBarcode/$1');
$routes->get('debug-upload', 'User::debugUpload');
$routes->post('debug-upload', 'User::debugUpload');

// Basic user routes tanpa filter
$routes->get('user/payment/(:num)', 'User::payment/$1');
$routes->post('user/upload-payment', 'User::uploadPayment');
$routes->get('user/orders', 'User::orders');

$routes->get('user/orders', 'User::orders');
$routes->get('user/confirm-order', 'User::confirmOrder');
$routes->get('user/invoice/(:num)', 'User::viewInvoice/$1');

// Admin Routes - Kelola Pesanan
$routes->get('admin/orders', 'Admin::orders');
$routes->get('admin/orders/(:segment)', 'Admin::orders/$1');
$routes->get('admin/order-detail/(:num)', 'Admin::orderDetail/$1');
$routes->get('admin/invoice/(:num)', 'Admin::generateInvoice/$1');
$routes->get('admin/reports', 'Admin::reports');
$routes->get('admin/export-reports', 'Admin::exportReports');


// Tambahkan routes ini ke dalam file app/Config/Routes.php

// Routes untuk Invoice Admin
$routes->get('admin/invoices', 'Admin::invoices');
$routes->get('admin/export-invoices', 'Admin::exportInvoices');

/**
 * Alternatif jika route group tidak bekerja:
 * Tambahkan langsung tanpa group:
 */

// Direct routes (alternative)
$routes->get('user/pay', 'User::pay');
$routes->post('user/pay', 'User::pay');
$routes->get('user/payment/(:num)', 'User::payment/$1');

$routes->get('user/printInvoice/(:num)', 'User::printInvoice/$1');
$routes->get('user/downloadInvoice/(:num)', 'User::downloadInvoice/$1');
$routes->get('user/downloadInvoicePDF/(:num)', 'User::downloadInvoicePDF/$1');
$routes->get('user/getInvoice', 'User::getInvoice');

