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

        // Generate unique filename (tanpa spasi/karakter yang merusak URL)
        $ext = $file->getClientOriginalExtension();
        $base = time() . '_' . Str::random(10);
        $filename = str_replace([' ', "\t", "\n", "\r"], '_', $base) . '.' . $ext;

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
     * Encode path untuk URL (spasi & karakter khusus jadi %20, dll) agar gambar tetap bisa dimuat.
     *
     * @param string|null $path Path relatif misal "images/articles/1771915493 9m1qoNtgAi.png"
     * @return string Path ter-encode per segmen
     */
    public static function encodePathForUrl(?string $path): string
    {
        if (!$path || trim($path) === '') {
            return '';
        }
        $path = ltrim($path, '/');
        $segments = explode('/', $path);
        return implode('/', array_map('rawurlencode', $segments));
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
