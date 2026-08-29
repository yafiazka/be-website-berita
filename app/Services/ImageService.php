<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Maximum width for optimized article images/thumbnails.
     */
    protected static int $maxWidth = 1600;

    /**
     * Maximum height for optimized article images/thumbnails.
     */
    protected static int $maxHeight = 1200;

    /**
     * Image quality compression (1 - 100).
     */
    protected static int $quality = 85;

    /**
     * Process and save an image from various input sources (base64 string, UploadedFile, or existing path/URL).
     *
     * @param mixed $input
     * @param string $folder
     * @return string|null Relative storage path or external URL
     */
    public static function processAndStore(mixed $input, string $folder = 'articles'): ?string
    {
        if (empty($input)) {
            return null;
        }

        // 1. If it's already an external HTTP/HTTPS URL
        if (is_string($input) && (str_starts_with($input, 'http://') || str_starts_with($input, 'https://'))) {
            return $input;
        }

        // 2. If it's a Base64 data string (e.g. data:image/png;base64,....)
        if (is_string($input) && preg_match('/^data:image\/(\w+);base64,/', $input, $matches)) {
            return self::processBase64($input, $matches[1], $folder);
        }

        // 3. If it's an UploadedFile instance
        if ($input instanceof UploadedFile) {
            return self::processUploadedFile($input, $folder);
        }

        // 4. If it's already a local storage relative file (ensure it is a real file, NOT a directory)
        if (is_string($input)) {
            $storagePath = storage_path('app/public/' . ltrim($input, '/'));
            if (is_file($storagePath) && !is_dir($storagePath)) {
                return ltrim($input, '/');
            }
        }

        // 5. If it's a valid local file path on the system (ensure it is a real file, NOT a directory)
        if (is_string($input) && is_file($input) && !is_dir($input)) {
            $binaryData = @file_get_contents($input);
            if ($binaryData !== false && !empty($binaryData)) {
                return self::optimizeAndSaveBinary($binaryData, $folder);
            }
        }

        // 6. If it's a short string that looks like an image filename/path (e.g. articles/sample.jpg)
        if (is_string($input) && strlen($input) < 500 && !is_dir($input) && preg_match('/\.(jpe?g|png|webp|gif|svg|avif)$/i', $input)) {
            return $input;
        }

        return null;
    }

    /**
     * Decode, optimize, and store Base64 image.
     */
    protected static function processBase64(string $base64Data, string $format, string $folder): ?string
    {
        $data = substr($base64Data, strpos($base64Data, ',') + 1);
        $binaryData = base64_decode($data);

        if ($binaryData === false || empty($binaryData)) {
            return null;
        }

        return self::optimizeAndSaveBinary($binaryData, $folder);
    }

    /**
     * Optimize and store an UploadedFile safely.
     */
    protected static function processUploadedFile(UploadedFile $file, string $folder): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $realPath = $file->getRealPath();
        if (empty($realPath) || !is_file($realPath) || is_dir($realPath)) {
            return null;
        }

        $binaryData = @file_get_contents($realPath);
        if ($binaryData === false || empty($binaryData)) {
            return $file->store($folder, 'public');
        }

        return self::optimizeAndSaveBinary($binaryData, $folder);
    }

    /**
     * Perform smart resizing and compression using PHP GD.
     */
    protected static function optimizeAndSaveBinary(string $binaryData, string $folder): ?string
    {
        if (!function_exists('imagecreatefromstring')) {
            // GD not available fallback
            $filename = Str::uuid() . '.jpg';
            $path = $folder . '/' . $filename;
            Storage::disk('public')->put($path, $binaryData);
            return $path;
        }

        $sourceImage = @imagecreatefromstring($binaryData);
        if ($sourceImage === false) {
            return null;
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // Calculate proportional dimensions without making it too small
        $newWidth = $origWidth;
        $newHeight = $origHeight;

        if ($origWidth > self::$maxWidth || $origHeight > self::$maxHeight) {
            $ratio = min(self::$maxWidth / $origWidth, self::$maxHeight / $origHeight);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);
        }

        // Create new truecolor image canvas
        $targetImage = imagecreatetruecolor($newWidth, $newHeight);

        // Handle alpha transparency for PNG / WebP
        imagealphablending($targetImage, false);
        imagesavealpha($targetImage, true);
        $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
        imagefilledrectangle($targetImage, 0, 0, $newWidth, $newHeight, $transparent);
        imagealphablending($targetImage, true);

        // Resample smoothly
        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth,
            $newHeight,
            $origWidth,
            $origHeight
        );

        // Output buffer to WebP (or JPEG if WebP is unsupported)
        ob_start();
        $extension = 'webp';
        if (function_exists('imagewebp')) {
            imagewebp($targetImage, null, self::$quality);
        } else {
            $extension = 'jpg';
            imagejpeg($targetImage, null, self::$quality);
        }
        $optimizedData = ob_get_clean();

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        $filename = Str::uuid() . '.' . $extension;
        $path = $folder . '/' . $filename;

        Storage::disk('public')->put($path, $optimizedData);

        return $path;
    }
}
