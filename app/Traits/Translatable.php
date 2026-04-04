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
            return $translations[$locale];
        }

        if ($locale !== 'en' && is_array($translations) && ! empty($translations['en'])) {
            return $translations['en'];
        }

        return $this->getAttribute($field);
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
