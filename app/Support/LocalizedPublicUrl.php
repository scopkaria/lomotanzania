<?php

namespace App\Support;

use App\Http\Middleware\SetLocale;
use App\Models\ChatSession;

class LocalizedPublicUrl
{
    public static function supportedLocales(): array
    {
        return SetLocale::SUPPORTED;
    }

    public static function defaultLocale(): string
    {
        return SetLocale::DEFAULT;
    }

    public static function sanitizeLocale(?string $locale): ?string
    {
        return in_array($locale, self::supportedLocales(), true) ? $locale : null;
    }

    public static function inferLocaleFromTrackedEntry(?string $entry): ?string
    {
        if (! is_string($entry) || trim($entry) === '') {
            return null;
        }

        if (str_contains($entry, ' — ')) {
            $parts = explode(' — ', $entry, 2);
            $entry = $parts[1] ?? $parts[0];
        }

        $path = parse_url($entry, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            $path = $entry;
        }

        $segment = strtok(trim($path, '/'), '/');

        return self::sanitizeLocale($segment ?: null);
    }

    public static function inferLocaleForChat(?ChatSession $chatSession, ?string $fallback = null): string
    {
        $fallback = self::sanitizeLocale($fallback) ?? self::defaultLocale();

        if (! $chatSession) {
            return $fallback;
        }

        $candidates = [$chatSession->current_page];
        $history = $chatSession->page_history ?? [];

        if (is_array($history)) {
            $candidates = array_merge($candidates, array_reverse($history));
        }

        foreach ($candidates as $candidate) {
            $locale = self::inferLocaleFromTrackedEntry($candidate);
            if ($locale) {
                return $locale;
            }
        }

        return $fallback;
    }

    public static function route(string $name, array $parameters = [], ?string $locale = null, bool $absolute = false): string
    {
        $parameters = array_merge(['locale' => self::sanitizeLocale($locale) ?? self::defaultLocale()], $parameters);

        return route($name, $parameters, $absolute);
    }

    public static function path(string $path, ?string $locale = null): string
    {
        $locale = self::sanitizeLocale($locale) ?? self::defaultLocale();
        $path = trim($path);

        if ($path === '' || $path === '/') {
            return '/' . $locale;
        }

        if (preg_match('/^(mailto:|tel:|#|javascript:)/i', $path)) {
            return $path;
        }

        $parts = parse_url($path);
        $pathPart = $parts['path'] ?? $path;
        $normalizedPath = '/' . ltrim((string) $pathPart, '/');
        $segments = explode('/', trim($normalizedPath, '/'));
        $firstSegment = $segments[0] ?? '';

        if (in_array($firstSegment, ['admin', 'api', 'storage', 'build'], true)) {
            return $normalizedPath . self::suffix($parts);
        }

        if (self::sanitizeLocale($firstSegment)) {
            array_shift($segments);
        }

        $localizedPath = '/' . $locale;
        if ($segments !== [] && $segments[0] !== '') {
            $localizedPath .= '/' . implode('/', $segments);
        }

        return $localizedPath . self::suffix($parts);
    }

    protected static function suffix(array $parts): string
    {
        $suffix = '';

        if (! empty($parts['query'])) {
            $suffix .= '?' . $parts['query'];
        }

        if (! empty($parts['fragment'])) {
            $suffix .= '#' . $parts['fragment'];
        }

        return $suffix;
    }
}