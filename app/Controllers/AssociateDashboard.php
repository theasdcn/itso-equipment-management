<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\EquipmentModel;
use App\Models\BorrowModel;

class AssociateDashboard extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();

        if ($session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Associates only.');
        }

        $borrowModel = new BorrowModel();
        $associateId = $session->get('user_id');

        $data = [
            'title' => 'Associate Dashboard - ITSO EMS',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role'),
                'email' => $session->get('email')
            ],
            'stats' => [
                'active_borrows' => $borrowModel->getActiveBorrowCount($associateId),
                'pending_requests' => $borrowModel->getPendingRequestCount($associateId),
                'total_borrows' => $borrowModel->getTotalBorrowCount($associateId)
            ],
            'recent_borrows' => $borrowModel->getStudentRecentBorrows($associateId, 5)
        ];

        return view('associate/dashboard', $data);
    }

    public function about_us()
    {
        $session = \Config\Services::session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login to access this page.');
        }

        $data = [
            'title' => 'About Us - ITSO EMS',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ]
        ];

        return view('associate/about_us', $data);
    }

    public function equipment()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();
        $borrowModel = new BorrowModel();
        $associateId = $session->get('user_id');

        $equipment = $equipmentModel->getAvailableEquipment();

        foreach ($equipment as &$item) {
            $item['is_borrowed_by_user'] = $borrowModel->isEquipmentBorrowedByStudent($item['equipment_id'], $associateId);
            if ($item['is_borrowed_by_user']) {

                $borrowRecord = $borrowModel->getActiveBorrowByStudent($item['equipment_id'], $associateId);
                $item['expected_return_date'] = $borrowRecord['expected_return_date'] ?? null;
            }
        }

        $data = [
            'title' => 'Browse Equipment - Associate',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'equipment' => $equipment
        ];

        return view('associate/equipment', $data);
    }

    public function borrowEquipment()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentId = $this->request->getPost('equipment_id');
        $associateId = $session->get('user_id');

        $equipmentModel = new EquipmentModel();
        $borrowModel = new BorrowModel();
        $userModel = new UserModel();

        $equipment = $equipmentModel->find($equipmentId);
        if (!$equipment) {
            return redirect()->back()->with('error', 'Equipment not found.');
        }

        if ($equipment['available_quantity'] <= 0) {
            return redirect()->back()->with('error', 'This equipment is currently unavailable.');
        }

        if ($borrowModel->isEquipmentBorrowedByStudent($equipmentId, $associateId)) {
            return redirect()->back()->with('error', 'You have already borrowed this equipment.');
        }

        if ($borrowModel->hasReachedBorrowLimit($associateId, 5)) {
            return redirect()->back()->with('error', 'You have reached the maximum borrow limit of 5 items.');
        }

        $rules = [
            'borrow_date' => 'required',
            'borrow_time' => 'required',
            'return_date' => 'required',
            'return_time' => 'required',
            'purpose' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please fill in all required fields. Purpose must be at least 10 characters.');
        }

        $borrowDate = $this->request->getPost('borrow_date');
        $borrowTime = $this->request->getPost('borrow_time');
        $returnDate = $this->request->getPost('return_date');
        $returnTime = $this->request->getPost('return_time');
        $purpose = $this->request->getPost('purpose');

        $borrowDateTime = $borrowDate . ' ' . $borrowTime . ':00';
        $expectedReturnDateTime = $returnDate . ' ' . $returnTime . ':00';

        if (strtotime($expectedReturnDateTime) <= strtotime($borrowDateTime)) {
            return redirect()->back()->with('error', 'Return date and time must be after borrow date and time.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {

            $borrowData = [
                'equipment_id' => $equipmentId,
                'student_id' => $associateId,
                'purpose' => $purpose,
                'academic_subject' => 'Associate Use',
                'professor_name' => 'N/A',
                'room_number' => 'N/A',
                'borrow_date' => $borrowDateTime,
                'expected_return_date' => $expectedReturnDateTime,
                'status' => 'borrowed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $borrowSuccess = $borrowModel->insert($borrowData);
            
            if (!$borrowSuccess) {
                throw new \Exception('Failed to create borrow record');
            }

            $borrowId = $borrowModel->getInsertID();

            $quantitySuccess = $equipmentModel->updateEquipmentQuantities($equipmentId, 'borrow');
            
            if (!$quantitySuccess) {
                throw new \Exception('Failed to update equipment quantities');
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }

            $userData = $userModel->find($associateId);
            $borrowDetails = [
                'borrow_id' => $borrowId,
                'borrow_date' => $borrowDateTime,
                'expected_return_date' => $expectedReturnDateTime,
                'purpose' => $purpose
            ];
            
            $authController = new AuthController();
            $authController->sendBorrowEmail($userData, $equipment, $borrowDetails);

            $formattedReturnDate = date('M j, Y g:i A', strtotime($expectedReturnDateTime));
            return redirect()->back()->with('success', "Equipment borrowed successfully! Please return it by $formattedReturnDate. A confirmation email has been sent.");

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Borrow equipment failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to borrow equipment. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function returnEquipment()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentId = $this->request->getPost('equipment_id');
        $associateId = $session->get('user_id');

        $equipmentModel = new EquipmentModel();
        $borrowModel = new BorrowModel();
        $userModel = new UserModel();

        $borrowRecord = $borrowModel->getActiveBorrowByStudent($equipmentId, $associateId);
        if (!$borrowRecord) {
            return redirect()->back()->with('error', 'You have not borrowed this equipment.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {

            $returnData = [
                'returned_at' => date('Y-m-d H:i:s'),
                'status' => 'returned',
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $returnSuccess = $borrowModel->update($borrowRecord['borrow_id'], $returnData);
            
            if (!$returnSuccess) {
                throw new \Exception('Failed to update borrow record');
            }

            $quantitySuccess = $equipmentModel->updateEquipmentQuantities($equipmentId, 'return');
            
            if (!$quantitySuccess) {
                throw new \Exception('Failed to update equipment quantities');
            }

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }

            $userData = $userModel->find($associateId);
            $equipment = $equipmentModel->find($equipmentId);
            $returnDetails = [
                'borrow_id' => $borrowRecord['borrow_id'],
                'returned_at' => $returnData['returned_at'],
                'borrow_date' => $borrowRecord['borrow_date']
            ];
            
            $authController = new AuthController();
            $authController->sendReturnEmail($userData, $equipment, $returnDetails);

            return redirect()->back()->with('success', 'Equipment returned successfully! Thank you for returning it. A confirmation email has been sent.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Return equipment failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to return equipment. Please try again.');
        }
    }

    public function reservations()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $borrowModel = new BorrowModel();
        $associateId = $session->get('user_id');

        $data = [
            'title' => 'My Reservations - Associate',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'borrow_requests' => $borrowModel->getStudentBorrowRequests($associateId)
        ];

        return view('associate/reservations', $data);
    }

    public function newReservation()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $equipmentModel = new EquipmentModel();
        
        $data = [
            'title' => 'New Borrow Request - Associate',
            'user' => [
                'first_name' => $session->get('first_name'),
                'last_name' => $session->get('last_name'),
                'role' => $session->get('role')
            ],
            'equipment' => $equipmentModel->getAvailableEquipment()
        ];

        return view('associate/new_reservation', $data);
    }

    public function submitBorrowRequest()
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $rules = [
            'equipment_id' => 'required|integer',
            'purpose' => 'required|min_length[10]',
            'academic_subject' => 'required',
            'professor_name' => 'required',
            'room_number' => 'required',
            'borrow_date' => 'required|valid_date',
            'expected_return_date' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $borrowData = [
            'equipment_id' => $this->request->getPost('equipment_id'),
            'student_id' => $session->get('user_id'),
            'purpose' => $this->request->getPost('purpose'),
            'academic_subject' => $this->request->getPost('academic_subject'),
            'professor_name' => $this->request->getPost('professor_name'),
            'room_number' => $this->request->getPost('room_number'),
            'borrow_date' => $this->request->getPost('borrow_date'),
            'expected_return_date' => $this->request->getPost('expected_return_date'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $borrowModel = new BorrowModel();
        if ($borrowModel->insert($borrowData)) {
            return redirect()->to('/associate/reservations')->with('success', 'Borrow request submitted successfully! Waiting for approval.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to submit borrow request.');
        }
    }

    public function cancelReservation($reservationId)
    {
        $session = \Config\Services::session();
        
        if ($session->get('role') !== 'associate') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }

        $borrowModel = new BorrowModel();
        $associateId = $session->get('user_id');

        $reservation = $borrowModel->where('borrow_id', $reservationId)
                                  ->where('student_id', $associateId)
                                  ->first();

        if (!$reservation) {
            return redirect()->to('/associate/reservations')->with('error', 'Reservation not found.');
        }

        if ($reservation['status'] !== 'pending') {
            return redirect()->to('/associate/reservations')->with('error', 'Only pending reservations can be cancelled.');
        }

        if ($borrowModel->delete($reservationId)) {
            return redirect()->to('/associate/reservations')->with('success', 'Reservation cancelled successfully.');
        } else {
            return redirect()->to('/associate/reservations')->with('error', 'Failed to cancel reservation.');
        }
    }
}