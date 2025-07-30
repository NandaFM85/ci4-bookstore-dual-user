<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userRole = session()->get('role');
        $requiredRole = $arguments[0] ?? null;

        if (!$userRole || $userRole !== $requiredRole) {
            if ($userRole === 'admin') {
                return redirect()->to('/dashboard/admin');
            } else {
                return redirect()->to('/dashboard/user');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}