<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageOptimizer
{
    /**
     * Optimize image for web
     */
    public static function optimize($imagePath, $maxWidth = 1200, $quality = 85)
    {
        if (!file_exists($imagePath)) {
            return false;
        }
        
        try {
            $image = Image::make($imagePath);
            
            // Resize if too large
            if ($image->width() > $maxWidth) {
                $image->resize($maxWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            // Optimize quality
            $image->encode('jpg', $quality);
            
            // Save optimized image
            $optimizedPath = str_replace(['.png', '.gif'], '.jpg', $imagePath);
            $image->save($optimizedPath);
            
            return $optimizedPath;
        } catch (\Exception $e) {
            \Log::error('Image optimization failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate responsive images
     */
    public static function generateResponsiveImages($imagePath, $sizes = [320, 640, 1024, 1200])
    {
        if (!file_exists($imagePath)) {
            return [];
        }
        
        $responsiveImages = [];
        
        try {
            foreach ($sizes as $size) {
                $image = Image::make($imagePath);
                
                if ($image->width() > $size) {
                    $image->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
                
                $responsivePath = str_replace('.', "_{$size}w.", $imagePath);
                $image->save($responsivePath);
                $responsiveImages[$size] = $responsivePath;
            }
            
            return $responsiveImages;
        } catch (\Exception $e) {
            \Log::error('Responsive image generation failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Generate WebP version of image
     */
    public static function generateWebP($imagePath)
    {
        if (!file_exists($imagePath)) {
            return false;
        }
        
        try {
            $image = Image::make($imagePath);
            $webpPath = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $imagePath);
            $image->encode('webp', 80)->save($webpPath);
            
            return $webpPath;
        } catch (\Exception $e) {
            \Log::error('WebP generation failed: ' . $e->getMessage());
            return false;
        }
    }
}
