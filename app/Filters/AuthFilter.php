<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page.');
        }

        $uri = $request->getUri()->getPath();
        if (strpos($uri, '/itso') === 0 && $session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. ITSO personnel only.');
        }

        if (strpos($uri, '/associate') === 0 && $session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Associates only.');
        }

        if (strpos($uri, '/student') === 0 && $session->get('role') !== 'student') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Students only.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}