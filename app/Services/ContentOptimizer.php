<?php

namespace App\Services;

/**
 * Content optimizer — provides free, rule-based content improvement suggestions
 * for headings, keyword usage, structure, and FAQ opportunities.
 */
class ContentOptimizer
{
    public function optimize(array $data): array
    {
        $title      = $data['title'] ?? '';
        $content    = $data['content'] ?? '';
        $keyword    = mb_strtolower(trim($data['focus_keyword'] ?? ''));
        $plainText  = strip_tags($content);
        $wordCount  = str_word_count($plainText);
        $lower      = mb_strtolower($plainText);

        $suggestions = [];

        // 1. Heading structure
        preg_match_all('/<h([1-6])[^>]*>(.*?)<\/h\1>/i', $content, $headingMatches);
        $headingLevels = $headingMatches[1] ?? [];

        if (empty($headingLevels)) {
            $suggestions[] = [
                'category' => 'headings',
                'priority' => 'high',
                'message'  => 'Add H2 and H3 headings to structure your content. This helps both readers and search engines.',
                'example'  => $keyword ? "Example: <h2>Best {$title} Experiences</h2>" : null,
            ];
        } else {
            if (!in_array('2', $headingLevels)) {
                $suggestions[] = [
                    'category' => 'headings',
                    'priority' => 'medium',
                    'message'  => 'Use H2 headings for main sections. Only H3+ headings found.',
                ];
            }
            if (count($headingLevels) < 3 && $wordCount > 300) {
                $suggestions[] = [
                    'category' => 'headings',
                    'priority' => 'medium',
                    'message'  => 'Add more subheadings to break up your content. Aim for an H2 every 300 words.',
                ];
            }
        }

        // 2. Keyword usage
        if (!empty($keyword)) {
            $kwCount = mb_substr_count($lower, $keyword);
            if ($kwCount === 0) {
                $suggestions[] = [
                    'category' => 'keywords',
                    'priority' => 'high',
                    'message'  => "Focus keyword \"{$keyword}\" not found in content. Use it naturally throughout.",
                ];
            }

            // Check first paragraph
            $firstPara = mb_strtolower(implode(' ', array_slice(str_word_count($plainText, 1), 0, 100)));
            if (mb_stripos($firstPara, $keyword) === false) {
                $suggestions[] = [
                    'category' => 'keywords',
                    'priority' => 'medium',
                    'message'  => 'Use the focus keyword in the first paragraph/introduction.',
                ];
            }

            // Check headings for keyword
            $kwInHeading = false;
            foreach (($headingMatches[2] ?? []) as $hText) {
                if (mb_stripos(strip_tags($hText), $keyword) !== false) {
                    $kwInHeading = true;
                    break;
                }
            }
            if (!$kwInHeading && !empty($headingLevels)) {
                $suggestions[] = [
                    'category' => 'keywords',
                    'priority' => 'medium',
                    'message'  => 'Include the focus keyword in at least one subheading.',
                ];
            }
        }

        // 3. Missing sections for safari/travel content
        $missingTopics = [];
        $topicChecks = [
            'highlight'    => ['highlights', 'what to expect', 'key features'],
            'itinerary'    => ['day 1', 'day 2', 'itinerary', 'schedule'],
            'inclusions'   => ['included', 'inclusions', 'what\'s included'],
            'pricing'      => ['price', 'pricing', 'cost', 'from $', 'per person'],
            'faq'          => ['faq', 'frequently asked', 'common questions'],
        ];

        foreach ($topicChecks as $topic => $keywords) {
            $found = false;
            foreach ($keywords as $kw) {
                if (mb_stripos($lower, $kw) !== false) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $missingTopics[] = $topic;
            }
        }

        if (in_array('faq', $missingTopics)) {
            $suggestions[] = [
                'category' => 'content',
                'priority' => 'medium',
                'message'  => 'Consider adding an FAQ section. This can help your content appear in Google\'s "People Also Ask" box.',
                'example'  => $keyword ? "Example FAQ: \"What is the best time for {$keyword}?\"" : null,
            ];
        }

        // 4. Content length
        if ($wordCount < 300) {
            $suggestions[] = [
                'category' => 'content',
                'priority' => 'high',
                'message'  => "Content is only {$wordCount} words. Aim for at least 300 words for good SEO. Consider adding details, tips, or related information.",
            ];
        } elseif ($wordCount < 800) {
            $suggestions[] = [
                'category' => 'content',
                'priority' => 'low',
                'message'  => "Content is {$wordCount} words. For competitive keywords, 800-1500 words usually performs better.",
            ];
        }

        // 5. Internal links
        preg_match_all('/<a[^>]+href\s*=\s*["\']([^"\']+)["\'][^>]*>/i', $content, $linkMatches);
        $links = $linkMatches[1] ?? [];
        $internalLinks = array_filter($links, fn($l) => !str_starts_with($l, 'http') || str_contains($l, request()->getHost()));

        if (count($internalLinks) < 2) {
            $suggestions[] = [
                'category' => 'links',
                'priority' => 'medium',
                'message'  => 'Add internal links to related safaris, destinations, or articles. This improves SEO and user engagement.',
            ];
        }

        // 6. Images
        preg_match_all('/<img[^>]+>/i', $content, $imgMatches);
        if (empty($imgMatches[0])) {
            $suggestions[] = [
                'category' => 'media',
                'priority' => 'low',
                'message'  => 'Add images to your content. Visual content improves engagement and time-on-page.',
            ];
        }

        // Sort by priority
        usort($suggestions, function ($a, $b) {
            $order = ['high' => 0, 'medium' => 1, 'low' => 2];
            return ($order[$a['priority']] ?? 3) <=> ($order[$b['priority']] ?? 3);
        });

        return [
            'suggestions' => $suggestions,
            'word_count'  => $wordCount,
            'heading_count' => count($headingLevels),
            'link_count'  => count($links),
            'image_count' => count($imgMatches[0] ?? []),
        ];
    }
}
