<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\EquipmentModel;

class AddEquipmentImages extends Seeder
{
    public function run()
    {
        $equipmentModel = new EquipmentModel();
        $uploadPath = FCPATH . 'uploads/equipment/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $equipment = $equipmentModel->findAll();
        
        $updated = 0;
        $errors = 0;
        
        foreach ($equipment as $item) {
            try {
                
                if (!empty($item['image'])) {
                    continue;
                }

                $imageName = time() . '_' . uniqid() . '.png';
                $thumbnailName = 'thumb_' . $imageName;

                $imagePath = $uploadPath . $imageName;
                $thumbnailPath = $uploadPath . $thumbnailName;

                $this->createPlaceholderImage($imagePath, 800, 600, $item['name'], $item['category']);

                $this->createPlaceholderImage($thumbnailPath, 300, 300, $item['name'], $item['category']);

                $updateData = [
                    'image' => $imageName,
                    'thumbnail' => $thumbnailName
                ];
                
                if ($equipmentModel->update($item['equipment_id'], $updateData)) {
                    $updated++;
                } else {
                    $errors++;

                    if (file_exists($imagePath)) unlink($imagePath);
                    if (file_exists($thumbnailPath)) unlink($thumbnailPath);
                }
                
            } catch (\Exception $e) {
                $errors++;
                echo "Error processing " . $item['name'] . ": " . $e->getMessage() . "\n";
            }
        }
        
        echo "Image upload completed!\n";
        echo "Items updated: " . $updated . "\n";
        echo "Errors: " . $errors . "\n";
    }
    
    private function createPlaceholderImage($filePath, $width, $height, $name, $category)
    {

        $image = imagecreatetruecolor($width, $height);

        $colors = $this->getCategoryColors($category, $image);
        $bgColor = $colors['bg'];
        $textColor = $colors['text'];
        $accentColor = $colors['accent'];

        imagefill($image, 0, 0, $bgColor);

        imagerectangle($image, 0, 0, $width - 1, $height - 1, $accentColor);
        imagerectangle($image, 3, 3, $width - 4, $height - 4, $accentColor);

        $categoryFontSize = min($width / 20, 24);
        $categoryY = $height * 0.15;
        $this->addCenteredText($image, $category, $categoryY, $categoryFontSize, $accentColor);

        $nameFontSize = min($width / 25, 20);
        $nameY = $height * 0.5;
        $this->addCenteredText($image, $name, $nameY, $nameFontSize, $textColor);

        $iconY = $height * 0.75;
        $iconSize = min($width / 6, 60);
        $iconX = ($width - $iconSize) / 2;
        imagefilledrectangle($image, $iconX, $iconY - $iconSize/2, $iconX + $iconSize, $iconY + $iconSize/2, $accentColor);
        
        imagepng($image, $filePath, 9);
        imagedestroy($image);
    }
    
    private function getCategoryColors($category, $image)
    {
        $colorMap = [
            'Laptops' => ['bg' => [240, 248, 255], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'DLP Projectors' => ['bg' => [255, 250, 240], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'HDMI and VGA Cables' => ['bg' => [240, 255, 240], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'DLP Remote Controls' => ['bg' => [255, 240, 245], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'Keyboards and Mice' => ['bg' => [240, 248, 255], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'Wacom Drawing Tablets' => ['bg' => [255, 250, 250], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'Speaker Sets and Webcams' => ['bg' => [245, 255, 250], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'Extension Cords' => ['bg' => [255, 255, 240], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'Cable Crimping Tools' => ['bg' => [248, 248, 255], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'Cable Testers' => ['bg' => [240, 255, 255], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
            'Lab Room Keys' => ['bg' => [255, 248, 240], 'text' => [30, 47, 46], 'accent' => [212, 175, 55]],
        ];
        
        $default = ['bg' => [253, 252, 248], 'text' => [31, 31, 31], 'accent' => [212, 175, 55]];
        $colors = $colorMap[$category] ?? $default;
        
        return [
            'bg' => imagecolorallocate($image, $colors['bg'][0], $colors['bg'][1], $colors['bg'][2]),
            'text' => imagecolorallocate($image, $colors['text'][0], $colors['text'][1], $colors['text'][2]),
            'accent' => imagecolorallocate($image, $colors['accent'][0], $colors['accent'][1], $colors['accent'][2])
        ];
    }
    
    private function getCategoryIcon($category)
    {
        $icons = [
            'Laptops' => '💻',
            'DLP Projectors' => '📽️',
            'HDMI and VGA Cables' => '🔌',
            'DLP Remote Controls' => '📱',
            'Keyboards and Mice' => '⌨️',
            'Wacom Drawing Tablets' => '✏️',
            'Speaker Sets and Webcams' => '🎤',
            'Extension Cords' => '🔌',
            'Cable Crimping Tools' => '🔧',
            'Cable Testers' => '📡',
            'Lab Room Keys' => '🗝️',
        ];
        
        return $icons[$category] ?? '📦';
    }
    
    private function addCenteredText($image, $text, $y, $fontSize, $color)
    {
        $width = imagesx($image);

        $maxWidth = $width * 0.9;
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        $font = 5;
        $charWidth = imagefontwidth($font);
        $charHeight = imagefontheight($font);
        
        foreach ($words as $word) {
            $testLine = $currentLine ? $currentLine . ' ' . $word : $word;
            $textWidth = strlen($testLine) * $charWidth;
            
            if ($textWidth > $maxWidth && $currentLine) {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }
        if ($currentLine) {
            $lines[] = $currentLine;
        }

        $lineHeight = $charHeight * 1.5;
        $totalHeight = count($lines) * $lineHeight;
        $startY = $y - ($totalHeight / 2);
        
        foreach ($lines as $i => $line) {
            $lineY = $startY + ($i * $lineHeight);
            $textWidth = strlen($line) * $charWidth;
            $x = ($width - $textWidth) / 2;
            imagestring($image, $font, $x, $lineY, $line, $color);
        }
    }
}

