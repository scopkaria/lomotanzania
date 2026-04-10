<?php
// ADDED: Reusable HTML sanitizer for rich text editors across all admin controllers

namespace App\Traits;

use DOMDocument;
use Illuminate\Support\Str;

trait SanitizesHtml
{
    /**
     * Sanitize rich-text HTML — whitelist safe tags and attributes.
     * Prevents XSS while allowing editorial formatting.
     */
    protected function sanitizeRichText(?string $html): ?string
    {
        $html = trim((string) $html);

        if ($html === '' || $html === '<p><br></p>') {
            return null;
        }

        $allowedTags = [
            'p', 'br', 'strong', 'em', 'b', 'i', 'u', 'sub', 'sup',
            'ul', 'ol', 'li', 'a', 'img', 'video', 'source',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'blockquote', 'pre', 'code', 'hr',
            'table', 'thead', 'tbody', 'tr', 'th', 'td',
            'figure', 'figcaption', 'span', 'div',
        ];

        $allowedAttributes = [
            'a'     => ['href', 'target', 'rel', 'title'],
            'img'   => ['src', 'alt', 'width', 'height', 'class', 'style'],
            'video' => ['src', 'controls', 'autoplay', 'loop', 'muted', 'playsinline', 'poster', 'preload', 'class', 'style'],
            'source' => ['src', 'type'],
            'td'    => ['colspan', 'rowspan'],
            'th'    => ['colspan', 'rowspan'],
            'span'  => ['style', 'class'],
            'div'   => ['style', 'class'],
            'p'     => ['style', 'class'],
            'figure' => ['class', 'style'],
        ];

        // Allowed CSS properties for style attributes
        $allowedStyles = [
            'text-align', 'float', 'margin', 'margin-left', 'margin-right',
            'padding', 'width', 'max-width', 'height',
        ];

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previousState = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><div id="sanitize-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);

        $root = $dom->getElementById('sanitize-root');
        if (!$root) {
            return strip_tags($html, '<' . implode('><', $allowedTags) . '>');
        }

        $nodes = [];
        foreach ($root->getElementsByTagName('*') as $node) {
            $nodes[] = $node;
        }

        for ($index = count($nodes) - 1; $index >= 0; $index--) {
            $node = $nodes[$index];

            // Remove disallowed tags (unwrap children)
            if (!in_array($node->nodeName, $allowedTags, true)) {
                $this->unwrapNode($node);
                continue;
            }

            // Clean attributes
            if ($node->hasAttributes()) {
                $toRemove = [];
                for ($i = $node->attributes->length - 1; $i >= 0; $i--) {
                    $attr = $node->attributes->item($i);
                    $isAllowed = in_array(
                        $attr->nodeName,
                        $allowedAttributes[$node->nodeName] ?? [],
                        true
                    );
                    if (!$isAllowed) {
                        $toRemove[] = $attr->nodeName;
                    }
                }
                foreach ($toRemove as $attrName) {
                    $node->removeAttribute($attrName);
                }
            }

            // Sanitize style attributes
            if ($node->hasAttribute('style')) {
                $style = $this->sanitizeStyle($node->getAttribute('style'), $allowedStyles);
                if ($style) {
                    $node->setAttribute('style', $style);
                } else {
                    $node->removeAttribute('style');
                }
            }

            // Sanitize links
            if ($node->nodeName === 'a') {
                $href = $this->sanitizeLinkHref($node->getAttribute('href'));
                if ($href === null) {
                    $this->unwrapNode($node);
                } else {
                    $node->setAttribute('href', $href);
                    if ($node->getAttribute('target') === '_blank') {
                        $node->setAttribute('rel', 'noopener noreferrer');
                    }
                }
            }

            // Sanitize images — only allow local storage URLs
            if ($node->nodeName === 'img') {
                $src = $node->getAttribute('src');
                if (!$this->isAllowedMediaSrc($src)) {
                    $node->parentNode?->removeChild($node);
                }
            }

            if ($node->nodeName === 'video') {
                $src = $node->getAttribute('src');
                if ($src !== '' && !$this->isAllowedMediaSrc($src)) {
                    $node->parentNode?->removeChild($node);
                    continue;
                }

                $poster = $node->getAttribute('poster');
                if ($poster !== '' && !$this->isAllowedMediaSrc($poster)) {
                    $node->removeAttribute('poster');
                }
            }

            if ($node->nodeName === 'source') {
                $src = $node->getAttribute('src');
                if (!$this->isAllowedMediaSrc($src)) {
                    $node->parentNode?->removeChild($node);
                }
            }
        }

        $cleanHtml = '';
        foreach ($root->childNodes as $child) {
            $cleanHtml .= $dom->saveHTML($child);
        }

        return trim($cleanHtml) ?: null;
    }

    protected function sanitizeStyle(string $style, array $allowed): ?string
    {
        $clean = [];
        $parts = array_filter(array_map('trim', explode(';', $style)));

        foreach ($parts as $part) {
            $colonPos = strpos($part, ':');
            if ($colonPos === false) continue;
            $prop = trim(substr($part, 0, $colonPos));
            $value = trim(substr($part, $colonPos + 1));

            if (in_array($prop, $allowed, true) && !preg_match('/expression|url|javascript/i', $value)) {
                $clean[] = $prop . ': ' . $value;
            }
        }

        return $clean ? implode('; ', $clean) : null;
    }

    protected function isAllowedMediaSrc(string $src): bool
    {
        // Allow relative paths (local storage)
        if (Str::startsWith($src, ['/storage/', '/images/', 'storage/'])) {
            return true;
        }
        // Allow absolute local URLs
        if (Str::startsWith($src, [url('/storage/'), url('/images/')])) {
            return true;
        }
        // Allow https images (external embeds)
        if (Str::startsWith($src, 'https://')) {
            return true;
        }

        return false;
    }

    protected function unwrapNode(\DOMNode $node): void
    {
        while ($node->firstChild) {
            $node->parentNode?->insertBefore($node->firstChild, $node);
        }
        $node->parentNode?->removeChild($node);
    }

    protected function sanitizeLinkHref(?string $href): ?string
    {
        $href = trim((string) $href);
        if ($href === '') return null;
        if (Str::startsWith($href, ['/', '#'])) return $href;

        return preg_match('/^(https?:|mailto:|tel:)/i', $href) ? $href : null;
    }
}
