<?php

namespace App\Services;

use CodeIgniter\Files\File;

class FileUploadService
{
    protected $uploadPath;
    protected $allowedTypes = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    protected $maxSize = 2048; 
    protected $imageService;

    public function __construct()
    {
        $this->uploadPath = FCPATH . 'uploads/equipment/';
        $this->imageService = service('image');
        
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function uploadEquipmentImage($file)
    {
        if (!$file->isValid()) {
            throw new \RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
        }

        if (!in_array($file->getExtension(), $this->allowedTypes)) {
            throw new \RuntimeException('Invalid file type. Allowed types: ' . implode(', ', $this->allowedTypes));
        }

        if ($file->getSize() > ($this->maxSize * 1024)) {
            throw new \RuntimeException('File size must be less than ' . $this->maxSize . 'KB');
        }

        $newName = $file->getRandomName();
        $thumbnailName = 'thumb_' . $newName;
        if (!$file->move($this->uploadPath, $newName)) {
            throw new \RuntimeException('Failed to move uploaded file');
        }

        try {
            $this->imageService->withFile($this->uploadPath . $newName)
                ->fit(300, 300, 'center')
                ->save($this->uploadPath . $thumbnailName, 85);

            $imageInfo = getimagesize($this->uploadPath . $newName);
            $maxDimension = 800;
            
            if ($imageInfo[0] > $maxDimension || $imageInfo[1] > $maxDimension) {
                $this->imageService->withFile($this->uploadPath . $newName)
                    ->resize($maxDimension, $maxDimension, true, 'width')
                    ->save($this->uploadPath . $newName, 85);
            }

            return [
                'image' => $newName,
                'thumbnail' => $thumbnailName
            ];

        } catch (\Exception $e) {
            $this->deleteFile($newName);
            $this->deleteFile($thumbnailName);
            throw new \RuntimeException('Image processing failed: ' . $e->getMessage());
        }
    }

    public function deleteEquipmentImages($image, $thumbnail = null)
    {
        $deleted = false;
        
        if (!empty($image) && file_exists($this->uploadPath . $image)) {
            $deleted = unlink($this->uploadPath . $image) || $deleted;
        }
        
        if (!empty($thumbnail) && file_exists($this->uploadPath . $thumbnail)) {
            $deleted = unlink($this->uploadPath . $thumbnail) || $deleted;
        }
        
        return $deleted;
    }

    public function getImageUrl($filename)
    {
        if (empty($filename) || !file_exists($this->uploadPath . $filename)) {
            return base_url('img/placeholder-equipment.png');
        }
        
        return base_url('uploads/equipment/' . $filename);
    }

    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    private function deleteFile($filename)
    {
        if (!empty($filename) && file_exists($this->uploadPath . $filename)) {
            return unlink($this->uploadPath . $filename);
        }
        return false;
    }

    public function validateFile($file)
    {
        if (!$file->isValid()) {
            return $file->getErrorString();
        }

        if (!in_array($file->getExtension(), $this->allowedTypes)) {
            return 'Invalid file type. Allowed: ' . implode(', ', $this->allowedTypes);
        }

        if ($file->getSize() > ($this->maxSize * 1024)) {
            return 'File size must be less than ' . $this->maxSize . 'KB';
        }

        return true;
    }
}