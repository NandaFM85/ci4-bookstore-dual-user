<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Anda harus login terlebih dahulu');
        }
        
        // Check for admin access
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        if (strpos($path, '/admin/') !== false && session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses tidak diizinkan');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}