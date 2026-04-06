<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediaSyncSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure storage:link exists
        $publicStoragePath = public_path('storage');
        if (!file_exists($publicStoragePath)) {
            Artisan::call('storage:link');
            $this->command->info('Created storage symlink.');
        } else {
            $this->command->info('Storage symlink already exists.');
        }

        // 2. Scan storage/app/public and create missing media records
        $disk = Storage::disk('public');
        $allFiles = $disk->allFiles();
        $created = 0;
        $skipped = 0;

        foreach ($allFiles as $filePath) {
            // Skip .gitignore and non-image files
            if (str_ends_with($filePath, '.gitignore')) {
                continue;
            }

            $mime = $disk->mimeType($filePath);
            if (!str_starts_with($mime, 'image/')) {
                continue;
            }

            // Check if media record already exists for this path
            $exists = Media::where('path', $filePath)->exists();
            if ($exists) {
                $skipped++;
                continue;
            }

            Media::create([
                'filename' => basename($filePath),
                'path'     => $filePath,
                'mime_type' => $mime,
                'size'     => $disk->size($filePath),
                'alt_text' => null,
                'disk'     => 'public',
            ]);
            $created++;
        }

        $this->command->info("Media sync complete: {$created} created, {$skipped} already existed.");
    }
}
