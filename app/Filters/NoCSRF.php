<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class NoCSRF implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // This filter does nothing - it's used to bypass CSRF
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
        return;
    }
}