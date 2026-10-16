<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();

        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $role = $session->get('role');
        
        switch ($role) {
            case 'student':
                return redirect()->to('/student/dashboard');
                break;
            case 'itso_personnel':
                return redirect()->to('/itso-dashboard');
                break;
            case 'associate':
                return redirect()->to('/associate/dashboard');
                break;
            default:
                $data = [
                    'title' => 'Dashboard',
                    'user' => [
                        'first_name' => $session->get('first_name'),
                        'last_name' => $session->get('last_name'),
                        'role' => $session->get('role')
                    ]
                ];
                return view('dashboard', $data);
        }
    }
}