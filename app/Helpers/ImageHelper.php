<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload image to storage
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string|null $oldImage
     * @return string
     */
    public static function upload(UploadedFile $file, string $folder = 'images', string $oldImage = null): string
    {
        // Delete old image if exists
        if ($oldImage) {
            self::delete($oldImage);
        }

        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Store file
        $path = $file->storeAs($folder, $filename, 'public');
        
        return $path;
    }

    /**
     * Delete image from storage
     *
     * @param string $path
     * @return bool
     */
    public static function delete(string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }

    /**
     * Get image URL
     *
     * @param string|null $path
     * @return string|null
     */
    public static function getUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Get image path for storage
     *
     * @param string $type
     * @return string
     */
    public static function getFolder(string $type): string
    {
        $folders = [
            'product' => 'images/products',
            'article' => 'images/articles',
            'gallery' => 'images/galleries',
            'category' => 'images/categories',
        ];

        return $folders[$type] ?? 'images';
    }
}
