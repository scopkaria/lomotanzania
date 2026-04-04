<?php

namespace App\Console\Commands;

use App\Services\GeoMarketGenerator;
use App\Services\ImageSeoService;
use App\Services\InternalLinker;
use App\Services\SeoPageGenerator;
use Illuminate\Console\Command;

class GenerateSeoPagesCommand extends Command
{
    protected $signature = 'seo:generate-pages
                            {--type=all : Type to generate (all, pages, markets, links, images)}';

    protected $description = 'Generate programmatic SEO pages, market pages, link rules, and image metadata';

    public function handle(): int
    {
        $type = $this->option('type');

        if (in_array($type, ['all', 'pages'])) {
            $this->info('Generating programmatic SEO pages...');
            $stats = (new SeoPageGenerator())->generateAll();
            $this->info("  Created: {$stats['created']} | Skipped: {$stats['skipped']}");
        }

        if (in_array($type, ['all', 'markets'])) {
            $this->info('Generating GEO market pages...');
            $stats = (new GeoMarketGenerator())->generateAll();
            $this->info("  Created: {$stats['created']} | Skipped: {$stats['skipped']}");
        }

        if (in_array($type, ['all', 'links'])) {
            $this->info('Syncing internal link rules from content...');
            $count = InternalLinker::syncFromContent();
            $this->info("  Synced: {$count} rules");
        }

        if (in_array($type, ['all', 'images'])) {
            $this->info('Processing untagged images...');
            $count = (new ImageSeoService())->processUntagged();
            $this->info("  Processed: {$count} images");
        }

        $this->info('Done!');
        return self::SUCCESS;
    }
}
