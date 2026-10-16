<?php

namespace App\Models;

use CodeIgniter\Model;

class EquipmentModel extends Model
{
    protected $table = 'equipment';
    protected $primaryKey = 'equipment_id';
    protected $allowedFields = [
        'equipment_code', 'name', 'category', 'description', 'specifications',
        'total_quantity', 'available_quantity', 'in_use_quantity', 'under_maintenance',
        'status', 'location', 'notes', 'image', 'thumbnail', 'created_by', 'updated_by',
        'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    protected $validationRules = [
        'equipment_code' => 'required|is_unique[equipment.equipment_code]',
        'name' => 'required|min_length[2]|max_length[255]',
        'category' => 'required|min_length[2]|max_length[100]',
        'total_quantity' => 'required|integer|greater_than[0]',
        'available_quantity' => 'required|integer',
        'status' => 'required|in_list[available,unavailable,maintenance,retired]'
    ];

    protected $validationMessages = [
        'equipment_code' => [
            'is_unique' => 'This equipment code is already assigned to another item.'
        ]
    ];

    protected $beforeInsert = ['generateEquipmentCode'];
    protected $beforeUpdate = ['updateQuantities'];

    protected function generateEquipmentCode(array $data)
    {
        if (!isset($data['data']['equipment_code']) || empty($data['data']['equipment_code'])) {
            $prefix = 'EQ';
            $random = strtoupper(substr(md5(uniqid()), 0, 6));
            $data['data']['equipment_code'] = $prefix . $random;
        }
        return $data;
    }

    protected function updateQuantities(array $data)
    {
        if (isset($data['data']['total_quantity'])) {
            $total = $data['data']['total_quantity'];
            $in_use = $data['data']['in_use_quantity'] ?? 0;
            $maintenance = $data['data']['under_maintenance'] ?? 0;
            
            $data['data']['available_quantity'] = $total - $in_use - $maintenance;
            
            if ($data['data']['available_quantity'] <= 0) {
                $data['data']['status'] = 'unavailable';
            } else {
                $data['data']['status'] = 'available';
            }
        }
        return $data;
    }

    public function getAllEquipment($filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }
        
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('name', $filters['search'])
                   ->orLike('equipment_code', $filters['search'])
                   ->orLike('description', $filters['search'])
                   ->groupEnd();
        }
        
        $equipment = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getEquipmentByCategory($category)
    {
        $equipment = $this->where('category', $category)->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getEquipmentStats()
    {
        $stats = [
            'total_items' => $this->countAll(),
            'total_quantity' => $this->selectSum('total_quantity')->get()->getRow()->total_quantity,
            'available_quantity' => $this->selectSum('available_quantity')->get()->getRow()->available_quantity,
            'in_use_quantity' => $this->selectSum('in_use_quantity')->get()->getRow()->in_use_quantity,
            'maintenance_quantity' => $this->selectSum('under_maintenance')->get()->getRow()->under_maintenance,
            'categories_count' => $this->distinct()->countAllResults('category')
        ];

        $statusCounts = $this->select('status, COUNT(*) as count')
                           ->groupBy('status')
                           ->get()
                           ->getResultArray();
        
        foreach ($statusCounts as $status) {
            $stats[$status['status'] . '_count'] = $status['count'];
        }

        return $stats;
    }

    public function updateEquipmentStatus($equipmentId, $status)
    {
        $allowedStatuses = ['available', 'unavailable', 'maintenance', 'retired'];
        if (!in_array($status, $allowedStatuses)) {
            return false;
        }
        
        return $this->update($equipmentId, ['status' => $status]);
    }

    public function getCategories()
    {
        return $this->distinct()->select('category')->findAll();
    }

    public function isEquipmentCodeUnique($equipmentCode, $excludeId = null)
    {
        $builder = $this->where('equipment_code', $equipmentCode);
        
        if ($excludeId) {
            $builder->where('equipment_id !=', $excludeId);
        }
        
        return $builder->countAllResults() === 0;
    }

    public function processEquipmentImage($imageFile)
    {
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

        try {
            $image = service('image');

            $image->withFile($uploadPath . $newName)
                  ->fit(300, 300, 'center')
                  ->save($uploadPath . $thumbnailName, 85);

            $imageInfo = getimagesize($uploadPath . $newName);
            $maxDimension = 800;
            
            if ($imageInfo[0] > $maxDimension || $imageInfo[1] > $maxDimension) {
                $image->withFile($uploadPath . $newName)
                      ->resize($maxDimension, $maxDimension, true, 'width')
                      ->save($uploadPath . $newName, 85);
            }

            return [
                'image' => $newName,
                'thumbnail' => $thumbnailName
            ];

        } catch (\Exception $e) {
            if (file_exists($uploadPath . $newName)) {
                unlink($uploadPath . $newName);
            }
            if (file_exists($uploadPath . $thumbnailName)) {
                unlink($uploadPath . $thumbnailName);
            }
            log_message('error', 'Image manipulation failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getEquipmentWithImages($filters = [])
    {
        $equipment = $this->getAllEquipment($filters);
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getEquipmentByIdWithImages($equipmentId)
    {
        $equipment = $this->find($equipmentId);
        
        if (!$equipment) {
            return null;
        }
        
        return $this->addImageUrls($equipment);
    }

    private function addImageUrls($equipment)
    {
        $defaultImage = $this->getDefaultImageForEquipment($equipment);
        if ($defaultImage) {
            $encodedFileName = rawurlencode($defaultImage);
            $equipment['image_url'] = base_url('img/' . $encodedFileName);
        } else {
            $equipment['image_url'] = base_url('img/placeholder-equipment.png');
        }
        
            $equipment['thumbnail_url'] = $equipment['image_url'];
        
        return $equipment;
    }

    private function getDefaultImageForEquipment($equipment)
    {
        $category = trim($equipment['category'] ?? '');
        $name = trim($equipment['name'] ?? '');
        $equipmentId = $equipment['equipment_id'] ?? 0;
        
        $categoryMap = [
            'Laptops' => 'LAPTOP',
            'DLP Projectors' => 'DLP',
            'DLP Remote Controls' => 'RE',
            'Keyboards and Mice' => 'KEYBOARD AND MICE',
            'Input Devices' => 'KEYBOARD AND MICE',
            'Wacom Drawing Tablets' => 'WACOM DRAWING TABLES',
            'Drawing Tablets' => 'WACOM DRAWING TABLES',
            'Speaker Sets and Webcams' => null,
            'Audio Equipment' => 'SPEAKER',
            'Video Equipment' => 'WEB CAM',
            'Extension Cords' => 'EX',
            'Cable Crimping Tools' => 'CM',
            'Cable Testers' => 'CT',
            'HDMI and VGA Cables' => 'HDMI AND VGA',
            'Cables & Adapters' => 'HDMI AND VGA',
            'Lab Room Keys' => 'LAB KEYS',
            'Lab Accessories' => 'LAB KEYS',
        ];
        
        $prefix = $categoryMap[$category] ?? null;
        
        if ($prefix === null && $category === 'Speaker Sets and Webcams') {
            $nameLower = strtolower($name);
            if (stripos($nameLower, 'webcam') !== false) {
                $prefix = 'WEB CAM';
            } elseif (stripos($nameLower, 'speaker') !== false) {
                $prefix = 'SPEAKER';
            }
        }
        
        if (!$prefix) {
            $categoryLower = strtolower($category);
            $nameLower = strtolower($name);
            $combined = $categoryLower . ' ' . $nameLower;
            
            if (stripos($combined, 'laptop') !== false) {
                $prefix = 'LAPTOP';
            } elseif (stripos($combined, 'projector') !== false || stripos($combined, 'dlp') !== false) {
                if (stripos($combined, 'remote') !== false) {
                    $prefix = 'RE';
                } else {
                    $prefix = 'DLP';
                }
            } elseif (stripos($combined, 'keyboard') !== false || stripos($combined, 'mouse') !== false) {
                $prefix = 'KEYBOARD AND MICE';
            } elseif (stripos($combined, 'wacom') !== false || stripos($combined, 'tablet') !== false || stripos($combined, 'drawing') !== false) {
                $prefix = 'WACOM DRAWING TABLES';
            } elseif (stripos($combined, 'webcam') !== false) {
                $prefix = 'WEB CAM';
            } elseif (stripos($combined, 'speaker') !== false) {
                $prefix = 'SPEAKER';
            } elseif (stripos($combined, 'extension') !== false || stripos($combined, 'cord') !== false) {
                $prefix = 'EX';
            } elseif (stripos($combined, 'crimping') !== false) {
                $prefix = 'CM';
            } elseif (stripos($combined, 'tester') !== false) {
                $prefix = 'CT';
            } elseif (stripos($combined, 'hdmi') !== false || stripos($combined, 'vga') !== false || stripos($combined, 'cable') !== false) {
                $prefix = 'HDMI AND VGA';
            } elseif (stripos($combined, 'key') !== false && stripos($combined, 'keyboard') === false) {
                $prefix = 'LAB KEYS';
            }
        }
        
        if (!$prefix) {
            return null;
        }
        
        $imgPath = FCPATH . 'img' . DIRECTORY_SEPARATOR;
        
        if (!is_dir($imgPath)) {
            return null;
        }
        
        $images = array_merge(
            glob($imgPath . $prefix . '*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE),
            glob($imgPath . strtolower($prefix) . '*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE),
            glob($imgPath . strtoupper($prefix) . '*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE)
        );
        
        if ($prefix === 'WEB CAM') {
            $images = array_merge($images, glob($imgPath . 'WEBCAM*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE));
            $images = array_merge($images, glob($imgPath . 'webcam*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE));
        }
        
        $images = array_unique($images);
        
        if (empty($images)) {
            return null;
        }
        
        $imageIndex = abs(crc32($equipmentId . $name)) % count($images);
        $selectedImagePath = $images[$imageIndex];
        $fileName = basename($selectedImagePath);
        
        return $fileName;
    }

    public function deleteEquipmentImages($equipmentId)
    {
        $equipment = $this->find($equipmentId);
        
        if (!$equipment) {
            return false;
        }

        $uploadPath = FCPATH . 'uploads/equipment/';
        
        if (!empty($equipment['image']) && file_exists($uploadPath . $equipment['image'])) {
            unlink($uploadPath . $equipment['image']);
        }
        
        if (!empty($equipment['thumbnail']) && file_exists($uploadPath . $equipment['thumbnail'])) {
            unlink($uploadPath . $equipment['thumbnail']);
        }
        
        return true;
    }

    public function getAvailableEquipment($filters = [])
    {
        $builder = $this->where('status', 'available')
                        ->where('available_quantity >', 0);

        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('name', $filters['search'])
                    ->orLike('description', $filters['search'])
                    ->orLike('equipment_code', $filters['search'])
                    ->groupEnd();
        }

        $equipment = $builder->orderBy('name', 'ASC')->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getAvailableEquipmentCount()
    {
        return $this->where('status', 'available')
                    ->where('available_quantity >', 0)
                    ->countAllResults();
    }

    public function getEquipmentWithAccessories($equipmentId = null)
    {
        $builder = $this;
        
        if ($equipmentId) {
            $builder = $builder->where('equipment_id', $equipmentId);
        }
        
        $equipment = $builder->findAll();
        
        foreach ($equipment as &$item) {
            $item['accessories'] = $this->getAccessoriesByCategory($item['category']);
            $item = $this->addImageUrls($item);
        }
        
        return $equipmentId ? ($equipment[0] ?? null) : $equipment;
    }

    private function getAccessoriesByCategory($category)
    {
        $accessories = [
            'Laptops' => ['Charger', 'Laptop Bag'],
            'DLP Projectors' => ['Extension Cord', 'VGA/HDMI Cable', 'Power Cable', 'Remote Control'],
            'Cables & Adapters' => ['Adapter Case'],
            'Input Devices' => ['USB Cable', 'Batteries'],
            'Drawing Tablets' => ['Pen', 'USB Cable', 'Driver CD'],
            'Audio Equipment' => ['Cables', 'Power Adapter', 'Microphone'],
            'Video Equipment' => ['Tripod', 'Cables', 'Memory Card'],
            'Networking Tools' => ['Cables', 'Documentation'],
            'Lab Accessories' => ['Power Cord', 'Connectors'],
            'Other Equipment' => ['Standard Accessories']
        ];

        return $accessories[$category] ?? ['Standard Accessories'];
    }

    public function isEquipmentAvailable($equipmentId, $quantity = 1)
    {
        $equipment = $this->find($equipmentId);
        
        if (!$equipment) {
            return false;
        }

        return $equipment['status'] === 'available' && 
               $equipment['available_quantity'] >= $quantity;
    }

    public function updateEquipmentQuantities($equipmentId, $action, $quantity = 1)
    {
        $equipment = $this->find($equipmentId);
        
        if (!$equipment) {
            return false;
        }

        $updateData = [];

        switch ($action) {
            case 'borrow':
                if ($equipment['available_quantity'] < $quantity) {
                    return false;
                }
                $updateData = [
                    'available_quantity' => $equipment['available_quantity'] - $quantity,
                    'in_use_quantity' => ($equipment['in_use_quantity'] ?? 0) + $quantity
                ];
                break;

            case 'return':
                if (($equipment['in_use_quantity'] ?? 0) < $quantity) {
                    return false;
                }
                $updateData = [
                    'available_quantity' => $equipment['available_quantity'] + $quantity,
                    'in_use_quantity' => ($equipment['in_use_quantity'] ?? 0) - $quantity
                ];
                break;

            case 'maintenance':
                $updateData = [
                    'available_quantity' => $equipment['available_quantity'] - $quantity,
                    'under_maintenance' => ($equipment['under_maintenance'] ?? 0) + $quantity
                ];
                break;

            case 'repair_complete':
                $updateData = [
                    'available_quantity' => $equipment['available_quantity'] + $quantity,
                    'under_maintenance' => ($equipment['under_maintenance'] ?? 0) - $quantity
                ];
                break;

            default:
                return false;
        }

        if ($updateData['available_quantity'] <= 0) {
            $updateData['status'] = 'unavailable';
        } else {
            $updateData['status'] = 'available';
        }

        return $this->update($equipmentId, $updateData);
    }

    public function getPopularEquipment($limit = 5)
    {
        $equipment = $this->where('status', 'available')
                    ->where('available_quantity >', 0)
                    ->orderBy('name', 'ASC')
                    ->limit($limit)
                    ->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function searchEquipment($searchTerm, $category = null)
    {
        $builder = $this->where('status', 'available')
                        ->where('available_quantity >', 0)
                        ->groupStart()
                        ->like('name', $searchTerm)
                        ->orLike('description', $searchTerm)
                        ->orLike('equipment_code', $searchTerm)
                        ->orLike('specifications', $searchTerm)
                        ->groupEnd();

        if ($category) {
            $builder->where('category', $category);
        }

        $equipment = $builder->orderBy('name', 'ASC')->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getEquipmentByStatus($status)
    {
        $equipment = $this->where('status', $status)->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getLowStockEquipment()
    {
        $equipment = $this->where('available_quantity <', 2)
                    ->where('available_quantity >', 0)
                    ->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getEquipmentNeedingMaintenance()
    {
        $equipment = $this->where('under_maintenance >', 0)
                    ->orWhere('status', 'maintenance')
                    ->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getPaginatedEquipment($filters = [], $limit = 10, $offset = 0)
    {
        $builder = $this->builder();
        
        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }
        
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('name', $filters['search'])
                ->orLike('equipment_code', $filters['search'])
                ->orLike('description', $filters['search'])
                ->groupEnd();
        }
        
        $equipment = $builder->orderBy('created_at', 'DESC')
                    ->limit($limit, $offset)
                    ->get()
                    ->getResultArray();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getEquipmentCount($filters = [])
    {
        $builder = $this->builder();
        
        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }
        
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('name', $filters['search'])
                ->orLike('equipment_code', $filters['search'])
                ->orLike('description', $filters['search'])
                ->groupEnd();
        }
        
        return $builder->countAllResults();
    }

    public function getActiveEquipmentCount($category = '')
    {
        $builder = $this->where('status', 'available');
        
        if (!empty($category)) {
            $builder->where('category', $category);
        }
        
        return $builder->countAllResults();
    }

    public function getUnusableEquipmentCount($category = '')
    {
        $builder = $this->where('status', 'unavailable');
        
        if (!empty($category)) {
            $builder->where('category', $category);
        }
        
        return $builder->countAllResults();
    }

    public function getMaintenanceEquipmentCount($category = '')
    {
        $builder = $this->where('status', 'maintenance');
        
        if (!empty($category)) {
            $builder->where('category', $category);
        }
        
        return $builder->countAllResults();
    }

    public function getActiveEquipmentReport($category = '', $dateFilter = [])
    {
        $builder = $this->where('status', 'available');
        
        if (!empty($category)) {
            $builder->where('category', $category);
        }
        
        if (!empty($dateFilter['start']) && !empty($dateFilter['end'])) {
            $builder->where('DATE(created_at) >=', $dateFilter['start'])
                    ->where('DATE(created_at) <=', $dateFilter['end']);
        }
        
        $equipment = $builder->orderBy('name', 'ASC')->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }

    public function getUnusableEquipmentReport($category = '', $dateFilter = [])
    {
        $builder = $this->whereIn('status', ['unavailable', 'maintenance']);
        
        if (!empty($category)) {
            $builder->where('category', $category);
        }
        
        if (!empty($dateFilter['start']) && !empty($dateFilter['end'])) {
            $builder->where('DATE(updated_at) >=', $dateFilter['start'])
                    ->where('DATE(updated_at) <=', $dateFilter['end']);
        }
        
        $equipment = $builder->orderBy('status', 'ASC')->findAll();
        
        foreach ($equipment as &$item) {
            $item = $this->addImageUrls($item);
        }
        
        return $equipment;
    }
}