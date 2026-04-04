<?php

namespace App\Console\Commands;

use App\Models\Accommodation;
use App\Models\Category;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Media;
use App\Models\Post;
use App\Models\SafariPackage;
use App\Models\Setting;
use App\Models\TourType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportExistingMedia extends Command
{
    protected $signature = 'media:import-existing';
    protected $description = 'Import all existing images from models into the Media Library';

    public function handle(): int
    {
        $this->info('Importing existing images into Media Library...');
        $imported = 0;
        $skipped = 0;

        $imageFields = [
            [SafariPackage::class, ['featured_image', 'og_image']],
            [Country::class, ['featured_image', 'og_image']],
            [Destination::class, ['featured_image', 'og_image']],
            [TourType::class, ['featured_image']],
            [Category::class, ['featured_image']],
            [Post::class, ['featured_image']],
        ];

        foreach ($imageFields as [$modelClass, $fields]) {
            $modelName = class_basename($modelClass);
            foreach ($modelClass::all() as $record) {
                foreach ($fields as $field) {
                    $path = $record->{$field};
                    if (empty($path)) {
                        continue;
                    }

                    $result = $this->importPath($path);
                    if ($result === 'imported') {
                        $imported++;
                    } else {
                        $skipped++;
                    }
                }
            }
            $this->line("  Processed {$modelName}");
        }

        // Itinerary images
        foreach (Itinerary::whereNotNull('image_path')->get() as $itinerary) {
            $result = $this->importPath($itinerary->image_path);
            $result === 'imported' ? $imported++ : $skipped++;
        }
        $this->line('  Processed Itinerary');

        // Accommodation gallery images
        foreach (Accommodation::with('images')->get() as $accommodation) {
            foreach ($accommodation->images as $image) {
                if (empty($image->image_path)) {
                    continue;
                }
                $result = $this->importPath($image->image_path);
                $result === 'imported' ? $imported++ : $skipped++;
            }
        }
        $this->line('  Processed Accommodation images');

        // Settings
        $setting = Setting::first();
        if ($setting) {
            foreach (['logo_path', 'default_og_image'] as $field) {
                $path = $setting->{$field};
                if (!empty($path)) {
                    $result = $this->importPath($path);
                    $result === 'imported' ? $imported++ : $skipped++;
                }
            }
            $this->line('  Processed Settings');
        }

        $this->info("Done! Imported: {$imported}, Skipped (already exist): {$skipped}");

        return self::SUCCESS;
    }

    protected function importPath(string $path): string
    {
        // Already in media library?
        if (Media::where('path', $path)->exists()) {
            return 'skipped';
        }

        // File must exist on disk
        if (!Storage::disk('public')->exists($path)) {
            $this->warn("  File not found: {$path}");
            return 'skipped';
        }

        $fullPath = Storage::disk('public')->path($path);

        Media::create([
            'filename'  => basename($path),
            'path'      => $path,
            'mime_type'  => mime_content_type($fullPath) ?: 'image/jpeg',
            'size'      => Storage::disk('public')->size($path),
            'alt_text'  => null,
            'disk'      => 'public',
        ]);

        return 'imported';
    }
}
