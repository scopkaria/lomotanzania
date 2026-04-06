<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

/**
 * Provides translatable field support for Eloquent models.
 *
 * Models using this trait should define:
 *   protected array $translatable = ['title', 'description'];
 *
 * Each translatable field must have a corresponding JSON column named
 * {field}_translations in the database table.
 *
 * Usage:
 *   $safari->translated('title')         → returns title in current locale, falls back to EN
 *   $safari->translated('title', 'fr')   → returns French title
 *   $safari->setTranslation('title', 'fr', 'Safari du Serengeti')
 *   $safari->getTranslations('title')    → ['en' => '...', 'fr' => '...']
 */
trait Translatable
{
    /**
     * Get the translated value for a field.
     */
    public function translated(string $field, ?string $locale = null): mixed
    {
        $locale = $locale ?: App::getLocale();
        $column = $field . '_translations';
        $translations = $this->getAttribute($column);

        if (is_string($translations)) {
            $translations = json_decode($translations, true);
        }

        // Return translation if available, else fallback to EN, else original field
        if (is_array($translations) && ! empty($translations[$locale])) {
            return $this->fixBrokenUnicode($translations[$locale]);
        }

        if ($locale !== 'en' && is_array($translations) && ! empty($translations['en'])) {
            return $this->fixBrokenUnicode($translations['en']);
        }

        return $this->getAttribute($field);
    }

    /**
     * Fix broken unicode escape sequences (e.g. "u00e9" without backslash).
     */
    protected function fixBrokenUnicode(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        // Fix sequences like u00e9 → proper é character
        if (preg_match('/(?<!\\\\)u[0-9a-fA-F]{4}/', $value)) {
            $value = preg_replace_callback('/(?<!\\\\)u([0-9a-fA-F]{4})/', function ($m) {
                return mb_chr(hexdec($m[1]), 'UTF-8');
            }, $value);
        }

        return $value;
    }

    /**
     * Get all translations for a field.
     */
    public function getTranslations(string $field): array
    {
        $column = $field . '_translations';
        $translations = $this->getAttribute($column);

        if (is_string($translations)) {
            $translations = json_decode($translations, true);
        }

        return is_array($translations) ? $translations : [];
    }

    /**
     * Set a translation for a specific locale.
     */
    public function setTranslation(string $field, string $locale, mixed $value): static
    {
        $column = $field . '_translations';
        $translations = $this->getTranslations($field);
        $translations[$locale] = $value;
        $this->setAttribute($column, $translations);

        return $this;
    }

    /**
     * Set all translations for a field at once.
     */
    public function setTranslations(string $field, array $translations): static
    {
        $column = $field . '_translations';
        $this->setAttribute($column, $translations);

        return $this;
    }

    /**
     * Get the translatable fields defined on the model.
     */
    public function getTranslatableFields(): array
    {
        return property_exists($this, 'translatable') ? $this->translatable : [];
    }
}
