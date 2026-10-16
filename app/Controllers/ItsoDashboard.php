<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\EquipmentModel;
use App\Models\BorrowModel;
use App\Models\ReturnModel;

class ItsoDashboard extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();

        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. ITSO personnel only.');
        }

        $userModel = new UserModel();
        $equipmentModel = new EquipmentModel();
        $borrowModel = new BorrowModel();

        $data = [
            'title' => 'ITSO Dashboard - System Administration',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role'),
                'email' => $session->get('email')
            ],
            'stats' => $this->getDashboardStats($userModel, $equipmentModel, $borrowModel)
        ];

        return view('itso/dashboard', $data);
    }

    public function about()
    {
        $session = \Config\Services::session();
        
        $data = [
            'title' => 'About Us - ITSO EMS',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role'),
                'email' => $session->get('email')
            ]
        ];

        return view('itso/about_us', $data);
    }

    public function equipment()
    {
        $session = \Config\Services::session();

        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();

        $filters = [
            'category' => $this->request->getGet('category'),
            'status' => $this->request->getGet('status'),
            'search' => $this->request->getGet('search')
        ];

        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $offset = ($currentPage - 1) * $perPage;

        $equipmentData = $equipmentModel->getPaginatedEquipment($filters, $perPage, $offset);
        $totalItems = $equipmentModel->getEquipmentCount($filters);
        $totalPages = ceil($totalItems / $perPage);

        $data = [
            'title' => 'Equipment Management - ITSO',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'equipment' => $equipmentData,
            'stats' => $equipmentModel->getEquipmentStats(),
            'categories' => $equipmentModel->getCategories(),
            'filters' => $filters,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'startItem' => $offset + 1,
            'endItem' => min($offset + $perPage, $totalItems)
        ];

        return view('itso/equipment', $data);
    }

    public function addEquipment()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $data = [
            'title' => 'Add Equipment - ITSO',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'categories' => $this->getEquipmentCategories()
        ];

        return view('itso/add_equipment', $data);
    }

    public function saveEquipment()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'category' => 'required',
            'total_quantity' => 'required|integer|greater_than[0]',
            'description' => 'permit_empty',
            'specifications' => 'permit_empty',
            'location' => 'permit_empty',
            'notes' => 'permit_empty'
        ];

        if ($this->request->getFile('equipment_image')->isValid()) {
            $rules['equipment_image'] = [
                'label' => 'Equipment Image',
                'rules' => 'uploaded[equipment_image]|max_size[equipment_image,2048]|is_image[equipment_image]|mime_in[equipment_image,image/jpg,image/jpeg,image/png,image/webp]',
                'errors' => [
                    'uploaded' => 'Please select an image file.',
                    'max_size' => 'Image file size must be less than 2MB.',
                    'is_image' => 'Please select a valid image file.',
                    'mime_in' => 'Please select a valid image format (JPG, PNG, or WebP).'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageData = $this->handleImageUpload();
        
        $equipmentData = [
            'equipment_code' => $this->request->getPost('equipment_code'),
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'specifications' => $this->request->getPost('specifications'),
            'total_quantity' => $this->request->getPost('total_quantity'),
            'available_quantity' => $this->request->getPost('total_quantity'),
            'in_use_quantity' => 0,
            'under_maintenance' => 0,
            'status' => 'available',
            'location' => $this->request->getPost('location'),
            'notes' => $this->request->getPost('notes'),
            'created_by' => $session->get('user_id')
        ];

        if ($imageData) {
            $equipmentData['image'] = $imageData['image'];
            $equipmentData['thumbnail'] = $imageData['thumbnail'];
        }

        if ($equipmentModel->insert($equipmentData)) {
            return redirect()->to('/itso/equipment')->with('success', 'Equipment added successfully.');
        } else {
            if (isset($imageData)) {
                $this->cleanupUploadedFiles($imageData);
            }
            return redirect()->back()->withInput()->with('error', 'Failed to add equipment.');
        }
    }

    public function editEquipment($equipmentId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();
        $equipment = $equipmentModel->getEquipmentByIdWithImages($equipmentId);

        if (!$equipment) {
            return redirect()->to('/itso/equipment')->with('error', 'Equipment not found.');
        }

        $data = [
            'title' => 'Edit Equipment - ITSO',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'equipment' => $equipment,
            'categories' => $this->getEquipmentCategories()
        ];

        return view('itso/edit_equipment', $data);
    }

    public function updateEquipment($equipmentId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'category' => 'required',
            'total_quantity' => 'required|integer|greater_than[0]',
            'description' => 'permit_empty',
            'specifications' => 'permit_empty',
            'location' => 'permit_empty',
            'notes' => 'permit_empty'
        ];

        if ($this->request->getFile('equipment_image')->isValid()) {
            $rules['equipment_image'] = [
                'label' => 'Equipment Image',
                'rules' => 'max_size[equipment_image,2048]|is_image[equipment_image]|mime_in[equipment_image,image/jpg,image/jpeg,image/png,image/webp]',
                'errors' => [
                    'max_size' => 'Image file size must be less than 2MB.',
                    'is_image' => 'Please select a valid image file.',
                    'mime_in' => 'Please select a valid image format (JPG, PNG, or WebP).'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $equipmentData = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'specifications' => $this->request->getPost('specifications'),
            'total_quantity' => $this->request->getPost('total_quantity'),
            'location' => $this->request->getPost('location'),
            'notes' => $this->request->getPost('notes'),
            'updated_by' => $session->get('user_id')
        ];

        if ($this->request->getFile('equipment_image')->isValid()) {
            $imageData = $this->handleImageUpload();
            if ($imageData) {

                $equipmentModel->deleteEquipmentImages($equipmentId);

                $equipmentData['image'] = $imageData['image'];
                $equipmentData['thumbnail'] = $imageData['thumbnail'];
            }
        }

        if ($equipmentModel->update($equipmentId, $equipmentData)) {
            return redirect()->to('/itso/equipment')->with('success', 'Equipment updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update equipment.');
        }
    }

    public function viewEquipment($equipmentId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();

        $equipment = $equipmentModel->getEquipmentByIdWithImages($equipmentId);

        if (!$equipment) {
            return redirect()->to('/itso/equipment')->with('error', 'Equipment not found.');
        }

        $data = [
            'title' => 'View Equipment - ITSO',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'equipment' => $equipment
        ];

        return view('itso/view_equipment', $data);
    }

    public function toggleEquipmentStatus($equipmentId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();
        $equipment = $equipmentModel->find($equipmentId);

        if (!$equipment) {
            return redirect()->to('/itso/equipment')->with('error', 'Equipment not found.');
        }

        $newStatus = $equipment['status'] === 'available' ? 'unavailable' : 'available';
        $equipmentModel->updateEquipmentStatus($equipmentId, $newStatus);

        $statusText = $newStatus === 'available' ? 'activated' : 'deactivated';
        return redirect()->to('/itso/equipment')->with('success', "Equipment {$statusText} successfully.");
    }

    public function deleteEquipment($equipmentId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();
        $equipment = $equipmentModel->find($equipmentId);

        if (!$equipment) {
            return redirect()->to('/itso/equipment')->with('error', 'Equipment not found.');
        }

        $equipmentModel->deleteEquipmentImages($equipmentId);

        if ($equipmentModel->delete($equipmentId)) {
            return redirect()->to('/itso/equipment')->with('success', 'Equipment deleted successfully.');
        } else {
            return redirect()->to('/itso/equipment')->with('error', 'Failed to delete equipment.');
        }
    }

    public function borrowRequests()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $statusFilter = $this->request->getGet('status');

        $borrowModel = new BorrowModel();
        
        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $offset = ($currentPage - 1) * $perPage;

        $borrowRequests = $borrowModel->getPaginatedBorrowRequests($statusFilter, $perPage, $offset);
        $totalItems = $borrowModel->getBorrowRequestsCount($statusFilter);
        $totalPages = ceil($totalItems / $perPage);
        $borrowStats = $borrowModel->getBorrowStats();

        $data = [
           'title' => 'Borrow Requests - ITSO',
           'user' => [
              'first_name' => $session->get('first_name'),
              'last_name' => $session->get('last_name'),
              'role' => $session->get('role')
            ],
            'borrowRequests' => $borrowRequests,
            'borrowStats' => $borrowStats,
            'statusFilter' => $statusFilter,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'startItem' => $offset + 1,
            'endItem' => min($offset + $perPage, $totalItems)
        ];

        return view('itso/borrow_requests', $data);
    }

    public function returnedEquipment()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $borrowModel = new BorrowModel();

        $filters = [
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to')
        ];

        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $offset = ($currentPage - 1) * $perPage;

        $returnedEquipment = $borrowModel->getPaginatedReturnedEquipment($filters, $perPage, $offset);
        $totalItems = $borrowModel->getReturnedEquipmentCount($filters);
        $totalPages = ceil($totalItems / $perPage);

        $data = [
           'title' => 'Returned Equipment - ITSO',
           'user' => [
               'first_name' => $session->get('first_name'),
               'last_name' => $session->get('last_name'),
               'role' => $session->get('role')
            ],
            'returnedEquipment' => $returnedEquipment,
            'returnStats' => $borrowModel->getReturnStats(),
            'filters' => $filters,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'startItem' => $offset + 1,
            'endItem' => min($offset + $perPage, $totalItems)
        ];

        return view('itso/returned_equipment', $data);
    }

    public function approveRequest($borrowId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $borrowModel = new BorrowModel();
        $equipmentModel = new EquipmentModel();

        $borrowRequest = $borrowModel->find($borrowId);
        
        if (!$borrowRequest) {
            return redirect()->to('/itso/borrow-requests')->with('error', 'Borrow request not found.');
        }

        $equipment = $equipmentModel->find($borrowRequest['equipment_id']);
        if (!$equipment || $equipment['available_quantity'] <= 0) {
            return redirect()->to('/itso/borrow-requests')->with('error', 'Equipment is no longer available.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $approveSuccess = $borrowModel->update($borrowId, [
                'status' => 'approved',
                'approved_by' => $session->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$approveSuccess) {
                throw new \Exception('Failed to approve borrow request');
            }

            $quantitySuccess = $equipmentModel->updateEquipmentQuantities($borrowRequest['equipment_id'], 'borrow');
            
            if (!$quantitySuccess) {
                throw new \Exception('Failed to update equipment quantities');
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to('/itso/borrow-requests')->with('success', 'Borrow request approved successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Approve request failed: ' . $e->getMessage());
            return redirect()->to('/itso/borrow-requests')->with('error', 'Failed to approve borrow request: ' . $e->getMessage());
        }
    }

    public function rejectRequest($borrowId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $reason = $this->request->getPost('reason');
        
        if (empty($reason)) {
            return redirect()->to('/itso/borrow-requests')->with('error', 'Rejection reason is required.');
        }

        $borrowModel = new BorrowModel();
        
        $rejectSuccess = $borrowModel->update($borrowId, [
            'status' => 'rejected',
            'admin_notes' => $reason,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($rejectSuccess) {
            return redirect()->to('/itso/borrow-requests')->with('success', 'Borrow request rejected successfully.');
        } else {
            return redirect()->to('/itso/borrow-requests')->with('error', 'Failed to reject borrow request.');
        }
    }

    public function completeRequest($borrowId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $borrowModel = new BorrowModel();
        $equipmentModel = new EquipmentModel();

        $borrowRequest = $borrowModel->find($borrowId);
        
        if (!$borrowRequest) {
            return redirect()->to('/itso/borrow-requests')->with('error', 'Borrow request not found.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $completeSuccess = $borrowModel->update($borrowId, [
                'status' => 'returned',
                'returned_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$completeSuccess) {
                throw new \Exception('Failed to complete borrow request');
            }

            $quantitySuccess = $equipmentModel->updateEquipmentQuantities($borrowRequest['equipment_id'], 'return');
            
            if (!$quantitySuccess) {
                throw new \Exception('Failed to update equipment quantities');
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to('/itso/borrow-requests')->with('success', 'Borrow request marked as completed and equipment returned.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Complete request failed: ' . $e->getMessage());
            return redirect()->to('/itso/borrow-requests')->with('error', 'Failed to complete borrow request.');
        }
    }

    public function users()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $userModel = new UserModel();

        $roleFilter = $this->request->getGet('role');

        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $offset = ($currentPage - 1) * $perPage;

        if ($roleFilter && in_array($roleFilter, ['student', 'associate', 'itso_personnel'])) {
            $users = $userModel->getPaginatedUsersByRole($roleFilter, $perPage, $offset);
            $totalItems = $userModel->getUsersCountByRole($roleFilter);
        } else {
            $users = $userModel->getPaginatedUsers($perPage, $offset);
            $totalItems = $userModel->countAll();
        }
        
        $totalPages = ceil($totalItems / $perPage);

        $data = [
            'title' => 'User Management - ITSO',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'users' => $users,
            'roleFilter' => $roleFilter,
            'userCounts' => [
                'total' => $userModel->countAll(),
                'itso' => $userModel->where('role', 'itso_personnel')->countAllResults(),
                'associates' => $userModel->where('role', 'associate')->countAllResults(),
                'students' => $userModel->where('role', 'student')->countAllResults(),
                'active' => $userModel->where('is_active', 1)->countAllResults(),
                'inactive' => $userModel->where('is_active', 0)->countAllResults()
            ],
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'startItem' => $offset + 1,
            'endItem' => min($offset + $perPage, $totalItems)
        ];

        return view('itso/users', $data);
    }

    public function editUser($userId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/itso/users')->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit User - ITSO',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'editUser' => $user
        ];

        return view('itso/edit_user', $data);
    }

   public function updateUser($userId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/itso/users')->with('error', 'User not found.');
        }

        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $role = $this->request->getPost('role');
        $is_active = $this->request->getPost('is_active') ? 1 : 0;

        if (empty($first_name) || empty($last_name) || empty($email) || empty($role)) {
            return redirect()->back()->withInput()->with('error', 'All required fields must be filled.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Please provide a valid email address.');
        }

        if (!in_array($role, ['student', 'associate', 'itso_personnel'])) {
            return redirect()->back()->withInput()->with('error', 'Please select a valid role.');
        }

        if ($email !== $user['email']) {
            if (!$userModel->isEmailUnique($email, $userId)) {
                return redirect()->back()->withInput()->with('error', 'This email is already registered to another user.');
            }
        }

        $userData = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'middle_name' => $this->request->getPost('middle_name'),
            'email' => $email,
            'role' => $role,
            'department' => $this->request->getPost('department'),
            'contact_number' => $this->request->getPost('contact_number'),
            'is_active' => $is_active,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $newPassword = $this->request->getPost('new_password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 8) {
                return redirect()->back()->withInput()->with('error', 'Password must be at least 8 characters long.');
            }
            $userData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        
        try {
            $builder->where('id', $userId);
            $result = $builder->update($userData);
            
            if ($result) {
                return redirect()->to('/itso/users')->with('success', 'User updated successfully.');
            } else {

                if ($db->affectedRows() === 0) {
                    return redirect()->back()->withInput()->with('error', 'No changes were made to the user.');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again.');
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'User update exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    public function toggleUserStatus($userId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/itso/users')->with('error', 'User not found.');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $userModel->toggleUserStatus($userId, $newStatus);

        $statusText = $newStatus ? 'activated' : 'deactivated';
        return redirect()->to('/itso/users')->with('success', "User {$statusText} successfully.");
    }

    public function reports()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();
        $borrowModel = new BorrowModel();
        $userModel = new UserModel();

        $dateRange = $this->request->getGet('date_range') ?? 'week';
        $category = $this->request->getGet('category') ?? '';
        $department = $this->request->getGet('department') ?? '';

        $dateFilter = $this->calculateDateRange($dateRange);

        $reportData = $this->getReportData($dateFilter, $category, $department);

        $data = [
            'title' => 'Reports - ITSO',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'active_equipment_count' => $reportData['active_equipment_count'],
            'available_count' => $reportData['available_count'],
            'unusable_count' => $reportData['unusable_count'],
            'maintenance_count' => $reportData['maintenance_count'],
            'active_borrows' => $reportData['active_borrows'],
            'total_users' => $reportData['total_users'],
            'filters' => [
                'date_range' => $dateRange,
                'category' => $category,
                'department' => $department
            ],
            'report_data' => $reportData
        ];

        return view('itso/reports', $data);
    }

    public function generateActiveEquipmentReport()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();

        $dateRange = $this->request->getGet('date_range') ?? 'week';
        $category = $this->request->getGet('category') ?? '';
        $department = $this->request->getGet('department') ?? '';

        $dateFilter = $this->calculateDateRange($dateRange);

        $activeEquipment = $equipmentModel->getActiveEquipmentReport($category, $dateFilter);

        return $this->response->setJSON([
            'success' => true,
            'data' => $activeEquipment,
            'filters' => [
                'date_range' => $dateRange,
                'category' => $category,
                'department' => $department
            ],
            'summary' => [
                'total_items' => count($activeEquipment),
                'total_quantity' => array_sum(array_column($activeEquipment, 'total_quantity')),
                'available_quantity' => array_sum(array_column($activeEquipment, 'available_quantity'))
            ]
        ]);
    }

    public function generateUnusableEquipmentReport()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();

        $dateRange = $this->request->getGet('date_range') ?? 'week';
        $category = $this->request->getGet('category') ?? '';
        $department = $this->request->getGet('department') ?? '';

        $dateFilter = $this->calculateDateRange($dateRange);

        $unusableEquipment = $equipmentModel->getUnusableEquipmentReport($category, $dateFilter);

        return $this->response->setJSON([
            'success' => true,
            'data' => $unusableEquipment,
            'filters' => [
                'date_range' => $dateRange,
                'category' => $category,
                'department' => $department
            ],
            'summary' => [
                'total_items' => count($unusableEquipment),
                'maintenance_count' => array_sum(array_column($unusableEquipment, 'under_maintenance')),
                'unavailable_count' => count(array_filter($unusableEquipment, function($item) {
                    return $item['status'] === 'unavailable';
                }))
            ]
        ]);
    }

    public function generateBorrowingHistoryReport()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $borrowModel = new BorrowModel();
        $userModel = new UserModel();

        $dateRange = $this->request->getGet('date_range') ?? 'week';
        $category = $this->request->getGet('category') ?? '';
        $department = $this->request->getGet('department') ?? '';

        $dateFilter = $this->calculateDateRange($dateRange);

        $borrowingHistory = $borrowModel->getBorrowingHistoryReport($dateFilter, $category, $department);

        return $this->response->setJSON([
            'success' => true,
            'data' => $borrowingHistory,
            'filters' => [
                'date_range' => $dateRange,
                'category' => $category,
                'department' => $department
            ],
            'summary' => [
                'total_records' => count($borrowingHistory),
                'active_borrows' => count(array_filter($borrowingHistory, function($item) {
                    return $item['status'] === 'borrowed';
                })),
                'returned_items' => count(array_filter($borrowingHistory, function($item) {
                    return $item['status'] === 'returned';
                }))
            ]
        ]);
    }

    public function settings()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'itso_personnel') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $data = [
            'title' => 'System Settings - ITSO',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ]
        ];

        return view('itso/settings', $data);
    }

    private function getDashboardStats($userModel, $equipmentModel, $borrowModel)
    {
        return [
            'total_equipment' => $equipmentModel->countAll(),
            'active_borrows' => $borrowModel->where('status', 'borrowed')->countAllResults(),
            'pending_requests' => $borrowModel->where('status', 'pending')->countAllResults(),
            'total_users' => $userModel->countAll(),
            'available_equipment' => $equipmentModel->where('status', 'available')->countAllResults(),
            'maintenance_equipment' => $equipmentModel->where('status', 'maintenance')->countAllResults(),
            'itso_users' => $userModel->where('role', 'itso_personnel')->countAllResults(),
            'associate_users' => $userModel->where('role', 'associate')->countAllResults(),
            'student_users' => $userModel->where('role', 'student')->countAllResults(),
            'active_users' => $userModel->where('is_active', 1)->countAllResults(),
            'inactive_users' => $userModel->where('is_active', 0)->countAllResults()
        ];
    }

    private function getEquipmentCategories()
    {
        return [
            'Laptops',
            'DLP Projectors',
            'Cables & Adapters',
            'Remote Controls',
            'Input Devices',
            'Drawing Tablets',
            'Audio Equipment',
            'Video Equipment',
            'Networking Tools',
            'Lab Accessories',
            'Other Equipment'
        ];
    }

    private function getEquipmentSpecifications($category)
    {
        $specs = [
            'Laptops' => [
                'Processor', 'RAM', 'Storage', 'Screen Size', 'Operating System', 'Charger Included'
            ],
            'DLP Projectors' => [
                'Lumens', 'Resolution', 'Contrast Ratio', 'Lamp Life', 'Extension Cord', 'VGA/HDMI Cable', 'Power Cable'
            ],
            'Cables & Adapters' => [
                'Type', 'Length', 'Connectors', 'Compatibility'
            ],
            'Remote Controls' => [
                'Compatible With', 'Battery Type', 'Range'
            ],
            'Input Devices' => [
                'Type', 'Connection', 'Compatibility', 'Lightning Cable'
            ],
            'Drawing Tablets' => [
                'Size', 'Pressure Levels', 'Resolution', 'Pen Included', 'Compatibility'
            ],
            'Audio Equipment' => [
                'Type', 'Power Output', 'Connectivity', 'Frequency Response'
            ],
            'Video Equipment' => [
                'Resolution', 'Frame Rate', 'Connectivity', 'Field of View'
            ],
            'Networking Tools' => [
                'Type', 'Compatibility', 'Features'
            ],
            'Lab Accessories' => [
                'Type', 'Compatibility', 'Features'
            ],
            'Other Equipment' => [
                'Specifications'
            ]
        ];

        return $specs[$category] ?? ['Specifications'];
    }

    private function handleImageUpload()
    {
        $imageFile = $this->request->getFile('equipment_image');
        
        if (!$imageFile->isValid()) {
            return false;
        }

        $uploadPath = FCPATH . 'uploads/equipment/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $imageFile->getRandomName();
        $thumbnailName = 'thumb_' . $newName;

        if (!$imageFile->move($uploadPath, $newName)) {
            return false;
        }

        $imageService = service('image');

        try {

            $imageService->withFile($uploadPath . $newName)
                        ->fit(300, 300, 'center')
                        ->save($uploadPath . $thumbnailName, 85); 

            $imageInfo = getimagesize($uploadPath . $newName);
            $maxDimension = 800;
            
            if ($imageInfo[0] > $maxDimension || $imageInfo[1] > $maxDimension) {
                $imageService->withFile($uploadPath . $newName)
                            ->resize($maxDimension, $maxDimension, true, 'width')
                            ->save($uploadPath . $newName, 85); 
            }

            return [
                'image' => $newName,
                'thumbnail' => $thumbnailName
            ];

        } catch (\Exception $e) {
            $this->cleanupUploadedFiles(['image' => $newName, 'thumbnail' => $thumbnailName]);
            log_message('error', 'Image manipulation failed: ' . $e->getMessage());
            return false;
        }
    }

    private function cleanupUploadedFiles($imageData)
    {
        $uploadPath = FCPATH . 'uploads/equipment/';
        
        if (isset($imageData['image']) && file_exists($uploadPath . $imageData['image'])) {
            unlink($uploadPath . $imageData['image']);
        }
        if (isset($imageData['thumbnail']) && file_exists($uploadPath . $imageData['thumbnail'])) {
            unlink($uploadPath . $imageData['thumbnail']);
        }
    }

    private function calculateDateRange($range)
    {
        $today = date('Y-m-d');
        
        switch ($range) {
            case 'today':
                return [
                    'start' => $today,
                    'end' => $today
                ];
            case 'week':
                return [
                    'start' => date('Y-m-d', strtotime('monday this week')),
                    'end' => date('Y-m-d', strtotime('sunday this week'))
                ];
            case 'month':
                return [
                    'start' => date('Y-m-01'),
                    'end' => date('Y-m-t')
                ];
            case 'quarter':
                $quarter = ceil(date('n') / 3);
                $startMonth = (($quarter - 1) * 3) + 1;
                $endMonth = $startMonth + 2;
                return [
                    'start' => date('Y-' . sprintf('%02d', $startMonth) . '-01'),
                    'end' => date('Y-' . sprintf('%02d', $endMonth) . '-t')
                ];
            case 'year':
                return [
                    'start' => date('Y-01-01'),
                    'end' => date('Y-12-31')
                ];
            case 'custom':
                return [
                    'start' => $this->request->getGet('start_date') ?? $today,
                    'end' => $this->request->getGet('end_date') ?? $today
                ];
            default:
                return [
                    'start' => date('Y-m-d', strtotime('monday this week')),
                    'end' => date('Y-m-d', strtotime('sunday this week'))
                ];
        }
    }

    private function getReportData($dateFilter, $category = '', $department = '')
    {
        $equipmentModel = new EquipmentModel();
        $borrowModel = new BorrowModel();
        $userModel = new UserModel();

        return [
            'active_equipment_count' => $equipmentModel->getActiveEquipmentCount($category),
            'available_count' => $equipmentModel->getAvailableEquipmentCount($category),
            'unusable_count' => $equipmentModel->getUnusableEquipmentCount($category),
            'maintenance_count' => $equipmentModel->getMaintenanceEquipmentCount($category),
            'active_borrows' => $borrowModel->getActiveBorrowsCount($dateFilter, $category),
            'total_users' => $userModel->getActiveUsersCount($department),
            'active_equipment' => $equipmentModel->getActiveEquipmentReport($category, $dateFilter),
            'unusable_equipment' => $equipmentModel->getUnusableEquipmentReport($category, $dateFilter),
            'borrowing_history' => $borrowModel->getBorrowingHistoryReport($dateFilter, $category, $department)
        ];
    }
}