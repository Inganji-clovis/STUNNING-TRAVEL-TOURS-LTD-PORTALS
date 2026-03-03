<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageOptimizer
{
    /**
     * Optimize an uploaded image using native PHP GD library.
     * Compression reduces file size significantly for LAN networks.
     *
     * @param UploadedFile $file
     * @param int $quality Quality from 0 to 100
     * @return UploadedFile
     */
    public static function optimize(UploadedFile $file, $quality = 70): UploadedFile
    {
        $mimeType = $file->getMimeType();
        $filePath = $file->getRealPath();

        // Only process supported images
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'])) {
            return $file;
        }

        list($width, $height) = getimagesize($filePath);

        // Max dimensions to prevent massive 4K images on LAN
        $maxWidth = 1920;
        $maxHeight = 1080;

        $newWidth = $width;
        $newHeight = $height;

        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = round($width * $ratio);
            $newHeight = round($height * $ratio);
        }

        $image = null;
        switch ($mimeType) {
            case 'image/jpeg':
                $image = @imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($filePath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($filePath);
                break;
        }

        if (!$image) {
            return $file;
        }

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Handle transparency for PNG and WebP
        if (in_array($mimeType, ['image/png', 'image/webp'])) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Overwrite the temp file with the optimized compressed version
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($resized, $filePath, $quality);
                break;
            case 'image/png':
                // PNG quality is 0-9. Convert 0-100 to 0-9
                $pngQuality = round((100 - $quality) / 100 * 9);
                imagepng($resized, $filePath, $pngQuality);
                break;
            case 'image/webp':
                imagewebp($resized, $filePath, $quality);
                break;
        }

        imagedestroy($image);
        imagedestroy($resized);

        // Clear stat cache to ensure Laravel gets the new file size
        clearstatcache(true, $filePath);

        return $file;
    }
}
