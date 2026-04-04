<?php

namespace App\Services;

use App\Models\SeoImageMeta;
use Illuminate\Support\Str;

/**
 * Image SEO service — generates optimized alt text and filenames.
 */
class ImageSeoService
{
    /**
     * Generate SEO-friendly alt text from context.
     */
    public function generateAltText(string $filename, ?string $context = null): string
    {
        // Strip extension and path
        $name = pathinfo($filename, PATHINFO_FILENAME);

        // Convert dashes/underscores/camelCase to words
        $words = preg_replace('/[-_]+/', ' ', $name);
        $words = preg_replace('/([a-z])([A-Z])/', '$1 $2', $words);
        $words = trim(strtolower($words));

        // Remove numbers-only segments and generic prefixes
        $words = preg_replace('/\b\d+\b/', '', $words);
        $words = trim(preg_replace('/\s+/', ' ', $words));

        if ($context) {
            return Str::limit("{$words} - {$context}", 120, '');
        }

        if (strlen($words) < 5) {
            return 'Safari experience in Tanzania';
        }

        return Str::limit($words, 120, '');
    }

    /**
     * Generate SEO-friendly filename.
     */
    public function generateSeoFilename(string $originalName, ?string $context = null): string
    {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $name = $context ?: pathinfo($originalName, PATHINFO_FILENAME);

        $slug = Str::slug(Str::limit($name, 60, ''));

        return $slug . '.' . strtolower($ext);
    }

    /**
     * Store or update image SEO metadata.
     */
    public function saveMeta(string $path, ?string $context = null): SeoImageMeta
    {
        $filename = basename($path);

        return SeoImageMeta::updateOrCreate(
            ['path' => $path],
            [
                'alt_text'     => $this->generateAltText($filename, $context),
                'seo_filename' => $this->generateSeoFilename($filename, $context),
            ]
        );
    }

    /**
     * Bulk process all images missing metadata.
     */
    public function processUntagged(): int
    {
        $storagePath = storage_path('app/public');
        if (!is_dir($storagePath)) {
            return 0;
        }

        $count = 0;
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePath));

        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), $extensions)) {
                $relativePath = str_replace($storagePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relativePath = str_replace('\\', '/', $relativePath);

                $exists = SeoImageMeta::where('path', $relativePath)->exists();
                if (!$exists) {
                    $this->saveMeta($relativePath);
                    $count++;
                }
            }
        }

        return $count;
    }
}
