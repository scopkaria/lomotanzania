<?php

namespace App\Services;

use App\Models\SeoLinkRule;
use Illuminate\Support\Collection;

/**
 * Internal Linking Engine — scans content for keywords and injects internal links.
 * Rules are loaded from the seo_link_rules table.
 */
class InternalLinker
{
    protected ?Collection $rules = null;

    /**
     * Process content and inject internal links based on keyword rules.
     * Each keyword is linked only once (first occurrence) to avoid over-optimization.
     *
     * @param string $content  HTML content to process
     * @param int    $maxLinks Maximum links to inject per content block
     */
    public function process(string $content, int $maxLinks = 5): string
    {
        if (empty($content)) {
            return $content;
        }

        $rules = $this->getRules();
        if ($rules->isEmpty()) {
            return $content;
        }

        $injected = 0;

        foreach ($rules as $rule) {
            if ($injected >= $maxLinks) {
                break;
            }

            $keyword = preg_quote($rule->keyword, '/');
            // Match keyword not already inside an <a> tag or HTML attribute
            $pattern = '/(?<!["\'>\/])(\b' . $keyword . '\b)(?![^<]*<\/a>)(?![^<]*>)/iu';

            if (preg_match($pattern, $content)) {
                $anchor = e($rule->anchor);
                $url = e($rule->url);
                $replacement = '<a href="' . $url . '" class="seo-internal-link" title="' . $anchor . '">${1}</a>';
                $content = preg_replace($pattern, $replacement, $content, 1);
                $injected++;
            }
        }

        return $content;
    }

    /**
     * Get all active link rules, sorted by priority (higher first).
     */
    protected function getRules(): Collection
    {
        if ($this->rules === null) {
            $this->rules = SeoLinkRule::active()
                ->orderByDesc('priority')
                ->get();
        }
        return $this->rules;
    }

    /**
     * Auto-populate link rules from existing destinations and countries.
     */
    public static function syncFromContent(): int
    {
        $count = 0;

        // Destinations → link to destination pages
        foreach (\App\Models\Destination::all() as $dest) {
            $url = '/' . app()->getLocale() . '/destinations/' . $dest->slug;
            SeoLinkRule::firstOrCreate(
                ['keyword' => $dest->name],
                ['url' => $url, 'priority' => 60, 'is_active' => true]
            );
            $count++;
        }

        // Countries → link to country pages
        foreach (\App\Models\Country::all() as $country) {
            $url = '/' . app()->getLocale() . '/countries/' . $country->slug;
            SeoLinkRule::firstOrCreate(
                ['keyword' => $country->name],
                ['url' => $url, 'priority' => 70, 'is_active' => true]
            );
            $count++;
        }

        return $count;
    }
}
