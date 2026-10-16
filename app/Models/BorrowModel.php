<?php

namespace App\Models;

use CodeIgniter\Model;

class BorrowModel extends Model
{
    protected $table = 'borrow_requests';
    protected $primaryKey = 'borrow_id';
    protected $allowedFields = [
        'equipment_id', 'student_id', 'purpose', 'professor_name', 
        'room_number', 'borrow_date', 'expected_return_date', 
        'academic_subject', 'status', 'approved_by', 'approved_at',
        'returned_at', 'created_at', 'updated_at', 'return_notes'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'equipment_id' => 'required|integer',
        'student_id' => 'required|integer',
        'purpose' => 'required|min_length[10]',
        'status' => 'required|in_list[pending,approved,rejected,borrowed,returned,cancelled]'
    ];

    protected $validationMessages = [
        'equipment_id' => [
            'required' => 'Equipment ID is required.',
            'integer' => 'Equipment ID must be an integer.'
        ],
        'student_id' => [
            'required' => 'Student ID is required.',
            'integer' => 'Student ID must be an integer.'
        ],
        'purpose' => [
            'required' => 'Purpose is required.',
            'min_length' => 'Purpose must be at least 10 characters long.'
        ]
    ];

    public function getAllBorrowRequests($status = null)
    {
        $builder = $this->db->table('borrow_requests br');
        $builder->select('br.*, e.name as equipment_name, e.equipment_code, u.first_name, u.last_name, u.email, u.role');
        $builder->join('equipment e', 'e.equipment_id = br.equipment_id');
        $builder->join('users u', 'u.id = br.student_id');
        
        if ($status) {
            $builder->where('br.status', $status);
        }
        
        $builder->orderBy('br.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getReturnedEquipment($filters = [])
    {
        $builder = $this->db->table('borrow_requests br');
        $builder->select('br.*, e.name as equipment_name, e.equipment_code, u.first_name, u.last_name, u.role');
        $builder->join('equipment e', 'e.equipment_id = br.equipment_id');
        $builder->join('users u', 'u.id = br.student_id');
        $builder->where('br.status', 'returned');

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(br.returned_at) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('DATE(br.returned_at) <=', $filters['date_to']);
        }
        
        $builder->orderBy('br.returned_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getReturnStats()
    {
        $stats = [];

        $stats['total_returns'] = $this->where('status', 'returned')->countAllResults();
        
        $stats['returns_this_week'] = $this->where('status', 'returned')
                                          ->where('returned_at >=', date('Y-m-d', strtotime('-7 days')))
                                          ->countAllResults();
        
        $stats['returns_today'] = $this->where('status', 'returned')
                                      ->where('DATE(returned_at)', date('Y-m-d'))
                                      ->countAllResults();
        
        return $stats;
    }

    public function approveRequest($borrowId, $approvedBy)
    {
        return $this->update($borrowId, [
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function rejectRequest($borrowId, $rejectedBy, $reason)
    {
        return $this->update($borrowId, [
            'status' => 'rejected',
            'return_notes' => $reason,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function completeRequest($borrowId)
    {
        return $this->update($borrowId, [
            'status' => 'returned',
            'returned_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getStudentBorrowRequests($studentId)
    {
        return $this->select('borrow_requests.*, equipment.name as equipment_name, equipment.equipment_code')
                    ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                    ->where('student_id', $studentId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getActiveBorrowCount($studentId)
    {
        return $this->where('student_id', $studentId)
                    ->where('status', 'borrowed')
                    ->countAllResults();
    }

    public function getPendingRequestCount($studentId)
    {
        return $this->where('student_id', $studentId)
                    ->where('status', 'pending')
                    ->countAllResults();
    }

    public function getTotalBorrowCount($studentId)
    {
        return $this->where('student_id', $studentId)
                    ->whereIn('status', ['approved', 'borrowed', 'returned'])
                    ->countAllResults();
    }

    public function getStudentRecentBorrows($studentId, $limit = 5)
    {
        return $this->select('borrow_requests.*, equipment.name as equipment_name, equipment.equipment_code')
                    ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                    ->where('student_id', $studentId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function isEquipmentAvailable($equipmentId, $borrowDate, $returnDate)
    {

        $overlappingBorrows = $this->where('equipment_id', $equipmentId)
                                  ->where('status', 'borrowed')
                                  ->groupStart()
                                  ->where('borrow_date <=', $returnDate)
                                  ->where('expected_return_date >=', $borrowDate)
                                  ->groupEnd()
                                  ->countAllResults();

        return $overlappingBorrows === 0;
    }

    public function isEquipmentBorrowedByStudent($equipmentId, $studentId)
    {
        return $this->where('equipment_id', $equipmentId)
                    ->where('student_id', $studentId)
                    ->where('status', 'borrowed')
                    ->countAllResults() > 0;
    }

    public function getActiveBorrowByStudent($equipmentId, $studentId)
    {
        return $this->where('equipment_id', $equipmentId)
                    ->where('student_id', $studentId)
                    ->where('status', 'borrowed')
                    ->first();
    }

    public function getBorrowRequestsWithEquipment($status = null)
    {
        $builder = $this->select('borrow_requests.*, equipment.name as equipment_name, equipment.equipment_code, users.first_name, users.last_name, users.email')
                       ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                       ->join('users', 'users.user_id = borrow_requests.student_id');
        
        if ($status) {
            $builder->where('borrow_requests.status', $status);
        }
        
        return $builder->orderBy('borrow_requests.created_at', 'DESC')->findAll();
    }

    public function updateBorrowStatus($borrowId, $status, $returnNotes = null)
    {
        $updateData = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($status === 'approved') {
            $updateData['approved_by'] = session()->get('user_id');
            $updateData['approved_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'returned') {
            $updateData['returned_at'] = date('Y-m-d H:i:s');
        }
        
        if ($returnNotes) {
            $updateData['return_notes'] = $returnNotes;
        }
        
        return $this->update($borrowId, $updateData);
    }

    public function getOverdueBorrows()
    {
        return $this->select('borrow_requests.*, equipment.name as equipment_name, users.first_name, users.last_name, users.email')
                   ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                   ->join('users', 'users.user_id = borrow_requests.student_id')
                   ->where('status', 'borrowed')
                   ->where('expected_return_date <', date('Y-m-d'))
                   ->findAll();
    }

    public function getBorrowStats()
    {
        $stats = [
            'total_requests' => $this->countAll(),
            'pending_requests' => $this->where('status', 'pending')->countAllResults(),
            'approved_requests' => $this->where('status', 'approved')->countAllResults(),
            'active_borrows' => $this->where('status', 'borrowed')->countAllResults(),
            'returned_borrows' => $this->where('status', 'returned')->countAllResults(),
            'overdue_borrows' => $this->where('status', 'borrowed')
                                     ->where('expected_return_date <', date('Y-m-d'))
                                     ->countAllResults()
        ];

        return $stats;
    }

    public function getBorrowRequestsByStatus($status)
    {
        return $this->select('borrow_requests.*, equipment.name as equipment_name, users.first_name, users.last_name')
                   ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                   ->join('users', 'users.user_id = borrow_requests.student_id')
                   ->where('borrow_requests.status', $status)
                   ->orderBy('borrow_requests.created_at', 'DESC')
                   ->findAll();
    }

    public function getStudentActiveBorrows($studentId)
    {
        return $this->select('borrow_requests.*, equipment.name as equipment_name, equipment.equipment_code')
                   ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                   ->where('student_id', $studentId)
                   ->where('status', 'borrowed')
                   ->orderBy('borrow_date', 'DESC')
                   ->findAll();
    }

    public function getStudentBorrowHistory($studentId)
    {
        return $this->select('borrow_requests.*, equipment.name as equipment_name, equipment.equipment_code')
                   ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                   ->where('student_id', $studentId)
                   ->whereIn('status', ['returned', 'rejected', 'cancelled'])
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function hasReachedBorrowLimit($studentId, $maxLimit = 5)
    {
        $activeBorrows = $this->where('student_id', $studentId)
                             ->where('status', 'borrowed')
                             ->countAllResults();
        
        return $activeBorrows >= $maxLimit;
    }

    public function getEquipmentBorrowHistory($equipmentId, $limit = 10)
    {
        return $this->select('borrow_requests.*, users.first_name, users.last_name, users.email')
                   ->join('users', 'users.user_id = borrow_requests.student_id')
                   ->where('equipment_id', $equipmentId)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    public function getMonthlyBorrowStats($year = null, $month = null)
    {
        if (!$year) $year = date('Y');
        if (!$month) $month = date('m');
        
        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return [
            'total_borrows' => $this->where('DATE(created_at) >=', $startDate)
                                   ->where('DATE(created_at) <=', $endDate)
                                   ->countAllResults(),
            'approved_borrows' => $this->where('DATE(created_at) >=', $startDate)
                                      ->where('DATE(created_at) <=', $endDate)
                                      ->whereIn('status', ['approved', 'borrowed', 'returned'])
                                      ->countAllResults(),
            'pending_requests' => $this->where('DATE(created_at) >=', $startDate)
                                      ->where('DATE(created_at) <=', $endDate)
                                      ->where('status', 'pending')
                                      ->countAllResults()
        ];
    }

    public function getPopularEquipment($limit = 5)
    {
        return $this->select('equipment.equipment_id, equipment.name, equipment.equipment_code, COUNT(borrow_requests.borrow_id) as borrow_count')
                   ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                   ->whereIn('borrow_requests.status', ['approved', 'borrowed', 'returned'])
                   ->groupBy('equipment.equipment_id, equipment.name, equipment.equipment_code')
                   ->orderBy('borrow_count', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    public function cleanupOldPendingRequests()
    {
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        return $this->where('status', 'pending')
                   ->where('created_at <', $thirtyDaysAgo)
                   ->delete();
    }

    public function getRequestsNeedingAttention()
    {
        $threeDaysAgo = date('Y-m-d H:i:s', strtotime('-3 days'));
        
        return $this->select('borrow_requests.*, equipment.name as equipment_name, users.first_name, users.last_name')
                   ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                   ->join('users', 'users.user_id = borrow_requests.student_id')
                   ->where('status', 'pending')
                   ->where('created_at <', $threeDaysAgo)
                   ->orderBy('created_at', 'ASC')
                   ->findAll();
    }

    public function updateReturnDate($borrowId, $returnDate)
    {
        return $this->update($borrowId, [
            'returned_at' => $returnDate,
            'status' => 'returned',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function canStudentBorrowEquipment($studentId, $equipmentId, $quantity = 1)
    {

        if ($this->hasReachedBorrowLimit($studentId)) {
            return ['success' => false, 'message' => 'You have reached the maximum borrow limit.'];
        }

        if ($this->isEquipmentBorrowedByStudent($equipmentId, $studentId)) {
            return ['success' => false, 'message' => 'You have already borrowed this equipment.'];
        }

        $equipmentModel = new EquipmentModel();
        $equipment = $equipmentModel->find($equipmentId);
        
        if (!$equipment) {
            return ['success' => false, 'message' => 'Equipment not found.'];
        }

        if ($equipment['available_quantity'] < $quantity) {
            return ['success' => false, 'message' => 'This equipment is currently unavailable.'];
        }

        return ['success' => true, 'message' => 'Equipment can be borrowed.'];
    }

    public function createDirectBorrow($equipmentId, $studentId)
    {
        $borrowData = [
            'equipment_id' => $equipmentId,
            'student_id' => $studentId,
            'purpose' => 'Direct borrowing from equipment catalog',
            'academic_subject' => 'General Academic Use',
            'professor_name' => 'Not specified',
            'room_number' => 'Not specified',
            'borrow_date' => date('Y-m-d H:i:s'),
            'expected_return_date' => date('Y-m-d H:i:s', strtotime('+7 days')),
            'status' => 'borrowed',
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($borrowData);
    }

    public function processDirectReturn($equipmentId, $studentId)
    {
        $borrowRecord = $this->getActiveBorrowByStudent($equipmentId, $studentId);
        
        if (!$borrowRecord) {
            return false;
        }

        $returnData = [
            'returned_at' => date('Y-m-d H:i:s'),
            'status' => 'returned',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->update($borrowRecord['borrow_id'], $returnData);
    }

    public function getAllActiveBorrows()
    {
        return $this->select('borrow_requests.*, equipment.name as equipment_name, equipment.equipment_code, users.first_name, users.last_name, users.email')
                   ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                   ->join('users', 'users.user_id = borrow_requests.student_id')
                   ->where('borrow_requests.status', 'borrowed')
                   ->orderBy('borrow_requests.borrow_date', 'ASC')
                   ->findAll();
    }

    public function getBorrowRequestWithDetails($borrowId)
    {
        return $this->select('borrow_requests.*, equipment.name as equipment_name, equipment.equipment_code, equipment.category, users.first_name, users.last_name, users.email, users.phone')
                   ->join('equipment', 'equipment.equipment_id = borrow_requests.equipment_id')
                   ->join('users', 'users.user_id = borrow_requests.student_id')
                   ->where('borrow_requests.borrow_id', $borrowId)
                   ->first();
    }

    public function getStudentBorrowStats($studentId)
    {
        return [
            'total_borrows' => $this->getTotalBorrowCount($studentId),
            'active_borrows' => $this->getActiveBorrowCount($studentId),
            'pending_requests' => $this->getPendingRequestCount($studentId),
            'returned_borrows' => $this->where('student_id', $studentId)
                                      ->where('status', 'returned')
                                      ->countAllResults(),
            'overdue_borrows' => $this->where('student_id', $studentId)
                                     ->where('status', 'borrowed')
                                     ->where('expected_return_date <', date('Y-m-d'))
                                     ->countAllResults()
        ];
    }

    public function createBorrowRequestWithDates($data)
    {
        return $this->insert($data);
    }

    public function getPaginatedBorrowRequests($status = null, $limit = 10, $offset = 0)
    {
        $builder = $this->db->table('borrow_requests br');
        $builder->select('br.*, e.name as equipment_name, e.equipment_code, u.first_name, u.last_name, u.email, u.role');
        $builder->join('equipment e', 'e.equipment_id = br.equipment_id');
        $builder->join('users u', 'u.id = br.student_id');
        
        if ($status) {
            $builder->where('br.status', $status);
        }
        
        $builder->orderBy('br.created_at', 'DESC')
                ->limit($limit, $offset);
        
        return $builder->get()->getResultArray();
    }

    public function getBorrowRequestsCount($status = null)
    {
        $builder = $this->db->table('borrow_requests br');
        $builder->join('equipment e', 'e.equipment_id = br.equipment_id');
        $builder->join('users u', 'u.id = br.student_id');
        
        if ($status) {
            $builder->where('br.status', $status);
        }
        
        return $builder->countAllResults();
    }

    public function getPaginatedReturnedEquipment($filters = [], $limit = 10, $offset = 0)
    {
        $builder = $this->db->table('borrow_requests br');
        $builder->select('br.*, e.name as equipment_name, e.equipment_code, u.first_name, u.last_name, u.role');
        $builder->join('equipment e', 'e.equipment_id = br.equipment_id');
        $builder->join('users u', 'u.id = br.student_id');
        $builder->where('br.status', 'returned');

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(br.returned_at) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('DATE(br.returned_at) <=', $filters['date_to']);
        }
        
        $builder->orderBy('br.returned_at', 'DESC')
                ->limit($limit, $offset);
        
        return $builder->get()->getResultArray();
    }

    public function getReturnedEquipmentCount($filters = [])
    {
        $builder = $this->db->table('borrow_requests br');
        $builder->join('equipment e', 'e.equipment_id = br.equipment_id');
        $builder->join('users u', 'u.id = br.student_id');
        $builder->where('br.status', 'returned');

        if (!empty($filters['date_from'])) {
            $builder->where('DATE(br.returned_at) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('DATE(br.returned_at) <=', $filters['date_to']);
        }
        
        return $builder->countAllResults();
    }

    public function getActiveBorrowsCount($dateFilter = [], $category = '')
    {
        $builder = $this->where('status', 'borrowed');
        
        if (!empty($dateFilter['start']) && !empty($dateFilter['end'])) {
            $builder->where('DATE(borrow_date) >=', $dateFilter['start'])
                    ->where('DATE(borrow_date) <=', $dateFilter['end']);
        }
        
        if (!empty($category)) {
            $builder->join('equipment e', 'e.equipment_id = borrow_requests.equipment_id')
                    ->where('e.category', $category);
        }
        
        return $builder->countAllResults();
    }

    public function getBorrowingHistoryReport($dateFilter = [], $category = '', $department = '')
    {
        $builder = $this->db->table('borrow_requests br');
        $builder->select('br.*, e.name as equipment_name, e.category, e.equipment_code, 
                         u.first_name, u.last_name, u.role, u.department,
                         au.first_name as approved_first_name, au.last_name as approved_last_name');
        $builder->join('equipment e', 'e.equipment_id = br.equipment_id');
        $builder->join('users u', 'u.id = br.student_id');
        $builder->join('users au', 'au.id = br.approved_by', 'left');

        if (!empty($dateFilter['start']) && !empty($dateFilter['end'])) {
            $builder->where('DATE(br.created_at) >=', $dateFilter['start'])
                    ->where('DATE(br.created_at) <=', $dateFilter['end']);
        }

        if (!empty($category)) {
            $builder->where('e.category', $category);
        }

        if (!empty($department)) {
            $builder->where('u.department', $department);
        }
        
        $builder->orderBy('br.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}