<?php

namespace App\Services;

/**
 * SEO content analyzer — evaluates content quality and returns a score 0–100
 * with actionable suggestions. Runs server-side for saved content and client-side
 * (via JS mirror) for live preview.
 */
class SeoAnalyzer
{
    protected string $focusKeyword = '';
    protected string $title = '';
    protected string $metaTitle = '';
    protected string $metaDescription = '';
    protected string $content = '';
    protected string $slug = '';
    protected array $checks = [];

    public function analyze(array $data): array
    {
        $this->focusKeyword   = mb_strtolower(trim($data['focus_keyword'] ?? ''));
        $this->title          = trim($data['title'] ?? '');
        $this->metaTitle      = trim($data['meta_title'] ?? $this->title);
        $this->metaDescription = trim($data['meta_description'] ?? '');
        $this->content        = trim($data['content'] ?? '');
        $this->slug           = trim($data['slug'] ?? '');
        $this->checks         = [];

        // Run all checks
        $this->checkFocusKeyword();
        $this->checkTitle();
        $this->checkMetaDescription();
        $this->checkContentLength();
        $this->checkKeywordDensity();
        $this->checkHeadings();
        $this->checkImageAltText();
        $this->checkInternalLinks();
        $this->checkSlug();
        $this->checkFirstParagraph();
        $this->checkReadability();

        $seoScore = $this->calculateScore();
        $readabilityScore = $this->calculateReadabilityScore();

        return [
            'seo_score'         => $seoScore,
            'readability_score' => $readabilityScore,
            'checks'            => $this->checks,
            'suggestions'       => $this->getSuggestions(),
        ];
    }

    protected function checkFocusKeyword(): void
    {
        if (empty($this->focusKeyword)) {
            $this->checks['focus_keyword'] = [
                'status'  => 'error',
                'message' => 'No focus keyword set. Add a keyword to optimize for.',
                'weight'  => 15,
                'score'   => 0,
            ];
            return;
        }

        $this->checks['focus_keyword'] = [
            'status'  => 'pass',
            'message' => 'Focus keyword is set.',
            'weight'  => 15,
            'score'   => 15,
        ];
    }

    protected function checkTitle(): void
    {
        $title = $this->metaTitle ?: $this->title;
        $len = mb_strlen($title);

        if ($len === 0) {
            $this->checks['title'] = [
                'status' => 'error', 'message' => 'Title is empty.',
                'weight' => 15, 'score' => 0,
            ];
            return;
        }

        $score = 0;
        $messages = [];

        // Length check
        if ($len >= 30 && $len <= 65) {
            $score += 7;
            $messages[] = "Title length is good ({$len} chars).";
        } elseif ($len < 30) {
            $score += 3;
            $messages[] = "Title is too short ({$len} chars). Aim for 30-65 characters.";
        } else {
            $score += 4;
            $messages[] = "Title is too long ({$len} chars). Keep under 65 characters.";
        }

        // Keyword in title
        if (!empty($this->focusKeyword) && mb_stripos($title, $this->focusKeyword) !== false) {
            $score += 8;
            $messages[] = 'Focus keyword found in title.';
        } elseif (!empty($this->focusKeyword)) {
            $messages[] = 'Add the focus keyword to the title.';
        }

        $status = $score >= 12 ? 'pass' : ($score >= 7 ? 'warning' : 'error');

        $this->checks['title'] = [
            'status' => $status, 'message' => implode(' ', $messages),
            'weight' => 15, 'score' => $score,
        ];
    }

    protected function checkMetaDescription(): void
    {
        $len = mb_strlen($this->metaDescription);

        if ($len === 0) {
            $this->checks['meta_description'] = [
                'status' => 'error', 'message' => 'Meta description is empty. Add a compelling description.',
                'weight' => 10, 'score' => 0,
            ];
            return;
        }

        $score = 0;
        $messages = [];

        if ($len >= 120 && $len <= 160) {
            $score += 5;
            $messages[] = "Meta description length is ideal ({$len} chars).";
        } elseif ($len > 160) {
            $score += 3;
            $messages[] = "Meta description is too long ({$len} chars). Keep under 160 characters.";
        } else {
            $score += 3;
            $messages[] = "Meta description is short ({$len} chars). Aim for 120-160 characters.";
        }

        if (!empty($this->focusKeyword) && mb_stripos($this->metaDescription, $this->focusKeyword) !== false) {
            $score += 5;
            $messages[] = 'Focus keyword found in meta description.';
        } elseif (!empty($this->focusKeyword)) {
            $messages[] = 'Add the focus keyword to the meta description.';
        }

        $status = $score >= 8 ? 'pass' : ($score >= 5 ? 'warning' : 'error');

        $this->checks['meta_description'] = [
            'status' => $status, 'message' => implode(' ', $messages),
            'weight' => 10, 'score' => $score,
        ];
    }

    protected function checkContentLength(): void
    {
        $plainText = strip_tags($this->content);
        $wordCount = str_word_count($plainText);

        if ($wordCount === 0) {
            $this->checks['content_length'] = [
                'status' => 'error', 'message' => 'No content provided.',
                'weight' => 15, 'score' => 0,
            ];
            return;
        }

        if ($wordCount >= 300) {
            $score = 15;
            $status = 'pass';
            $msg = "Content length is good ({$wordCount} words).";
        } elseif ($wordCount >= 150) {
            $score = 10;
            $status = 'warning';
            $msg = "Content could be longer ({$wordCount} words). Aim for 300+ words.";
        } else {
            $score = 5;
            $status = 'error';
            $msg = "Content is too short ({$wordCount} words). Add more detailed content.";
        }

        $this->checks['content_length'] = [
            'status' => $status, 'message' => $msg,
            'weight' => 15, 'score' => $score,
        ];
    }

    protected function checkKeywordDensity(): void
    {
        if (empty($this->focusKeyword)) {
            $this->checks['keyword_density'] = [
                'status' => 'warning', 'message' => 'Cannot check density without focus keyword.',
                'weight' => 10, 'score' => 0,
            ];
            return;
        }

        $plainText = mb_strtolower(strip_tags($this->content));
        $wordCount = str_word_count($plainText);

        if ($wordCount === 0) {
            $this->checks['keyword_density'] = [
                'status' => 'error', 'message' => 'No content to check keyword density.',
                'weight' => 10, 'score' => 0,
            ];
            return;
        }

        $keywordCount = mb_substr_count($plainText, $this->focusKeyword);
        $density = ($keywordCount / $wordCount) * 100;

        if ($density >= 0.5 && $density <= 3.0) {
            $this->checks['keyword_density'] = [
                'status' => 'pass',
                'message' => sprintf('Keyword density is %.1f%% (%d occurrences). Good range.', $density, $keywordCount),
                'weight' => 10, 'score' => 10,
            ];
        } elseif ($density > 3.0) {
            $this->checks['keyword_density'] = [
                'status' => 'warning',
                'message' => sprintf('Keyword density is %.1f%% — possible keyword stuffing. Aim for 0.5-3%%.', $density),
                'weight' => 10, 'score' => 5,
            ];
        } else {
            $this->checks['keyword_density'] = [
                'status' => 'warning',
                'message' => sprintf('Keyword density is low (%.1f%%). Use the keyword more naturally.', $density),
                'weight' => 10, 'score' => 3,
            ];
        }
    }

    protected function checkHeadings(): void
    {
        preg_match_all('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/i', $this->content, $matches);
        $headings = $matches[1] ?? [];

        if (empty($headings)) {
            $this->checks['headings'] = [
                'status' => 'warning', 'message' => 'No headings found in content. Use H2/H3 to structure your content.',
                'weight' => 10, 'score' => 0,
            ];
            return;
        }

        $score = 5; // headings exist
        $messages = [count($headings) . ' heading(s) found.'];

        if (!empty($this->focusKeyword)) {
            $kwInHeading = false;
            foreach ($headings as $heading) {
                if (mb_stripos(strip_tags($heading), $this->focusKeyword) !== false) {
                    $kwInHeading = true;
                    break;
                }
            }
            if ($kwInHeading) {
                $score += 5;
                $messages[] = 'Focus keyword found in a heading.';
            } else {
                $messages[] = 'Add the focus keyword to at least one heading.';
            }
        }

        $status = $score >= 8 ? 'pass' : 'warning';

        $this->checks['headings'] = [
            'status' => $status, 'message' => implode(' ', $messages),
            'weight' => 10, 'score' => $score,
        ];
    }

    protected function checkImageAltText(): void
    {
        preg_match_all('/<img[^>]+>/i', $this->content, $matches);
        $images = $matches[0] ?? [];

        if (empty($images)) {
            $this->checks['images'] = [
                'status' => 'warning', 'message' => 'No images in content. Adding relevant images improves engagement.',
                'weight' => 5, 'score' => 2,
            ];
            return;
        }

        $withAlt = 0;
        foreach ($images as $img) {
            if (preg_match('/alt\s*=\s*"[^"]+"/i', $img) || preg_match("/alt\s*=\s*'[^']+'/i", $img)) {
                $withAlt++;
            }
        }

        $total = count($images);
        if ($withAlt === $total) {
            $this->checks['images'] = [
                'status' => 'pass', 'message' => "All {$total} image(s) have alt text.",
                'weight' => 5, 'score' => 5,
            ];
        } else {
            $missing = $total - $withAlt;
            $this->checks['images'] = [
                'status' => 'warning',
                'message' => "{$missing} of {$total} image(s) missing alt text. Add descriptive alt attributes.",
                'weight' => 5, 'score' => 2,
            ];
        }
    }

    protected function checkInternalLinks(): void
    {
        preg_match_all('/<a[^>]+href\s*=\s*["\']([^"\']+)["\'][^>]*>/i', $this->content, $matches);
        $links = $matches[1] ?? [];

        $internalCount = 0;
        foreach ($links as $link) {
            if (!str_starts_with($link, 'http') || str_contains($link, request()->getHost())) {
                $internalCount++;
            }
        }

        if ($internalCount >= 2) {
            $this->checks['internal_links'] = [
                'status' => 'pass', 'message' => "{$internalCount} internal link(s) found.",
                'weight' => 5, 'score' => 5,
            ];
        } elseif ($internalCount === 1) {
            $this->checks['internal_links'] = [
                'status' => 'warning', 'message' => 'Only 1 internal link. Add more context links.',
                'weight' => 5, 'score' => 3,
            ];
        } else {
            $this->checks['internal_links'] = [
                'status' => 'error', 'message' => 'No internal links found. Link to related safaris, destinations, or articles.',
                'weight' => 5, 'score' => 0,
            ];
        }
    }

    protected function checkSlug(): void
    {
        if (empty($this->slug)) {
            $this->checks['slug'] = [
                'status' => 'warning', 'message' => 'No slug set.',
                'weight' => 5, 'score' => 0,
            ];
            return;
        }

        $score = 3;
        $messages = [];

        if (mb_strlen($this->slug) <= 75) {
            $score += 1;
            $messages[] = 'Slug length is good.';
        } else {
            $messages[] = 'Slug is very long. Shorter slugs perform better.';
        }

        if (!empty($this->focusKeyword)) {
            $kwSlug = str_replace(' ', '-', $this->focusKeyword);
            if (mb_stripos($this->slug, $kwSlug) !== false) {
                $score += 1;
                $messages[] = 'Focus keyword found in slug.';
            } else {
                $messages[] = 'Consider including the focus keyword in the slug.';
            }
        }

        $this->checks['slug'] = [
            'status' => $score >= 4 ? 'pass' : 'warning',
            'message' => implode(' ', $messages),
            'weight' => 5, 'score' => $score,
        ];
    }

    protected function checkFirstParagraph(): void
    {
        if (empty($this->focusKeyword) || empty($this->content)) {
            return;
        }

        // Get first 200 words
        $plainText = mb_strtolower(strip_tags($this->content));
        $words = array_slice(str_word_count($plainText, 1), 0, 200);
        $firstSection = implode(' ', $words);

        if (mb_stripos($firstSection, $this->focusKeyword) !== false) {
            $this->checks['first_paragraph'] = [
                'status' => 'pass', 'message' => 'Focus keyword appears in the first paragraph.',
                'weight' => 5, 'score' => 5,
            ];
        } else {
            $this->checks['first_paragraph'] = [
                'status' => 'warning', 'message' => 'Use the focus keyword in the first paragraph.',
                'weight' => 5, 'score' => 0,
            ];
        }
    }

    protected function checkReadability(): void
    {
        $plainText = strip_tags($this->content);
        $sentences = preg_split('/[.!?]+/', $plainText, -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount = count($sentences);

        if ($sentenceCount === 0) return;

        $longSentences = 0;
        foreach ($sentences as $sentence) {
            if (str_word_count(trim($sentence)) > 20) {
                $longSentences++;
            }
        }

        $longPct = ($longSentences / $sentenceCount) * 100;

        if ($longPct <= 25) {
            $this->checks['readability'] = [
                'status' => 'pass', 'message' => 'Good readability — sentences are concise.',
                'weight' => 0, 'score' => 0, 'readability_points' => 40,
            ];
        } elseif ($longPct <= 50) {
            $this->checks['readability'] = [
                'status' => 'warning', 'message' => 'Some sentences are long. Try breaking them up.',
                'weight' => 0, 'score' => 0, 'readability_points' => 25,
            ];
        } else {
            $this->checks['readability'] = [
                'status' => 'error', 'message' => 'Many sentences are too long. Simplify for readability.',
                'weight' => 0, 'score' => 0, 'readability_points' => 10,
            ];
        }
    }

    protected function calculateScore(): int
    {
        $total = 0;
        foreach ($this->checks as $check) {
            $total += ($check['score'] ?? 0);
        }
        return min(100, max(0, $total));
    }

    protected function calculateReadabilityScore(): int
    {
        // Base readability from sentence check
        $readability = $this->checks['readability']['readability_points'] ?? 30;

        // Paragraph check
        $paragraphs = preg_split('/\n\s*\n/', strip_tags($this->content));
        $paraCount = count(array_filter($paragraphs, fn($p) => trim($p) !== ''));

        if ($paraCount >= 3) {
            $readability += 30;
        } elseif ($paraCount >= 2) {
            $readability += 20;
        } else {
            $readability += 10;
        }

        // Transition words bonus
        $transitionWords = ['however', 'therefore', 'moreover', 'furthermore', 'additionally',
            'meanwhile', 'consequently', 'nevertheless', 'in conclusion', 'for example',
            'in addition', 'on the other hand', 'as a result'];
        $lowerContent = mb_strtolower(strip_tags($this->content));
        $transCount = 0;
        foreach ($transitionWords as $tw) {
            $transCount += mb_substr_count($lowerContent, $tw);
        }

        if ($transCount >= 3) {
            $readability += 30;
        } elseif ($transCount >= 1) {
            $readability += 20;
        } else {
            $readability += 5;
        }

        return min(100, max(0, $readability));
    }

    protected function getSuggestions(): array
    {
        $suggestions = [];

        foreach ($this->checks as $key => $check) {
            if ($check['status'] === 'error') {
                $suggestions[] = ['type' => 'error', 'message' => $check['message']];
            } elseif ($check['status'] === 'warning') {
                $suggestions[] = ['type' => 'warning', 'message' => $check['message']];
            }
        }

        return $suggestions;
    }
}
