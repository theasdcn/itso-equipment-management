<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';
    protected $primaryKey = 'return_id';
    protected $allowedFields = [
        'borrow_id', 'equipment_id', 'user_id', 'returned_at', 
        'condition', 'notes', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    public function getAllReturns($filters = [])
    {
        $builder = $this->db->table('returns r');
        $builder->select('r.*, u.first_name, u.last_name, u.role, e.name as equipment_name, e.equipment_code, b.borrow_date, b.expected_return_date');
        $builder->join('users u', 'u.id = r.user_id');
        $builder->join('equipment e', 'e.equipment_id = r.equipment_id');
        $builder->join('borrow_requests b', 'b.borrow_id = r.borrow_id');

        if (!empty($filters['date_from'])) {
            $builder->where('r.returned_at >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $builder->where('r.returned_at <=', $filters['date_to']);
        }
        if (!empty($filters['equipment_id'])) {
            $builder->where('r.equipment_id', $filters['equipment_id']);
        }
        if (!empty($filters['user_id'])) {
            $builder->where('r.user_id', $filters['user_id']);
        }
        
        $builder->orderBy('r.returned_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getReturnStats()
    {
        $stats = [];

        $stats['total_returns'] = $this->countAll();
        
        $stats['returns_this_week'] = $this->where('returned_at >=', date('Y-m-d', strtotime('-7 days')))->countAllResults();
        
        $stats['returns_today'] = $this->where('DATE(returned_at)', date('Y-m-d'))->countAllResults();
        
        $stats['good_condition'] = $this->where('condition', 'good')->countAllResults();
        $stats['fair_condition'] = $this->where('condition', 'fair')->countAllResults();
        $stats['poor_condition'] = $this->where('condition', 'poor')->countAllResults();
        
        return $stats;
    }

    public function recordReturn($borrowId, $equipmentId, $userId, $condition = 'good', $notes = '')
    {
        $returnData = [
            'borrow_id' => $borrowId,
            'equipment_id' => $equipmentId,
            'user_id' => $userId,
            'returned_at' => date('Y-m-d H:i:s'),
            'condition' => $condition,
            'notes' => $notes
        ];
        
        return $this->insert($returnData);
    }

    public function getEquipmentReturnHistory($equipmentId)
    {
        $builder = $this->db->table('returns r');
        $builder->select('r.*, u.first_name, u.last_name, u.role');
        $builder->join('users u', 'u.id = r.user_id');
        $builder->where('r.equipment_id', $equipmentId);
        $builder->orderBy('r.returned_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function getUserReturnHistory($userId)
    {
        $builder = $this->db->table('returns r');
        $builder->select('r.*, e.name as equipment_name, e.equipment_code');
        $builder->join('equipment e', 'e.equipment_id = r.equipment_id');
        $builder->where('r.user_id', $userId);
        $builder->orderBy('r.returned_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}