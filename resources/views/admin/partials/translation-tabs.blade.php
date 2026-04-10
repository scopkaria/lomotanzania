{{-- ============================================================
     Translations Tab Panel — Reusable across admin forms
     Props: $model (Eloquent model with Translatable trait)
            $fields (array of field configs)
     ============================================================ --}}

@php
    $locales = [
        'en' => ['name' => 'English',  'flag' => '🇬🇧'],
        'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
        'de' => ['name' => 'Deutsch',  'flag' => '🇩🇪'],
        'es' => ['name' => 'Español',  'flag' => '🇪🇸'],
    ];
    $model = $model ?? null;
@endphp

<div class="bg-white rounded-xl shadow-sm p-6" x-data="translationPanel({ fields: {{ json_encode(collect($fields)->pluck('name')->values()) }} })" x-init="init()">
    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5">
        <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4 text-[#FEBC11]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
            Translations
        </span>
    </h3>

    {{-- Language Tabs --}}
    <div class="flex gap-1 mb-5 border-b border-gray-100">
        @foreach($locales as $code => $locale)
            @if($code === 'en') @continue @endif
            <button type="button" @click="langTab = '{{ $code }}'"
                    :class="langTab === '{{ $code }}' ? 'border-[#FEBC11] text-gray-900 font-semibold' : 'border-transparent text-gray-400 hover:text-gray-600'"
                    class="flex items-center gap-1.5 px-4 py-2.5 text-sm border-b-2 transition">
                <span>{{ $locale['flag'] }}</span>
                {{ $locale['name'] }}
            </button>
        @endforeach
    </div>

    <div class="flex items-center justify-between mb-4">
        <p class="text-xs text-gray-400">
            English content is taken from the main fields above. Add translations below. If left empty, English will be used as fallback.
        </p>
        <div class="flex items-center gap-2 shrink-0 ml-4">
            {{-- Auto-Translate button --}}
            <button type="button"
                @click="autoTranslate()"
                :disabled="translating"
                class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-100 disabled:opacity-50 disabled:cursor-wait">
                <template x-if="!translating">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
                </template>
                <template x-if="translating">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </template>
                <span x-text="translating ? 'Translating…' : 'Auto-Translate'"></span>
            </button>

            {{-- Copy from English button --}}
            <button type="button"
                @click="copyFromEnglish()"
                class="inline-flex items-center gap-1.5 rounded-lg bg-[#FEBC11]/10 px-3 py-1.5 text-xs font-semibold text-[#8b6d00] transition hover:bg-[#FEBC11]/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                Copy from English
            </button>
        </div>
    </div>

    {{-- Translation status toast --}}
    <div x-show="translateMsg" x-transition.opacity
         :class="translateError ? 'bg-red-50 text-red-700 border-red-200' : 'bg-green-50 text-green-700 border-green-200'"
         class="mb-4 px-4 py-2 rounded-lg border text-xs font-medium"
         x-text="translateMsg"></div>

    {{-- Translation Fields per Language --}}
    @foreach($locales as $code => $locale)
        @if($code === 'en') @continue @endif
        <div x-show="langTab === '{{ $code }}'" class="space-y-4">
            @foreach($fields as $field)
                @php
                    $fieldName = $field['name'];
                    $fieldLabel = $field['label'];
                    $fieldType = $field['type'] ?? 'text';
                    $currentValue = old("translations.{$code}.{$fieldName}",
                        $model ? ($model->getTranslations($fieldName)[$code] ?? '') : '');
                @endphp

                <div>
                    <label for="translations_{{ $code }}_{{ $fieldName }}"
                           class="flex items-center justify-between text-sm font-medium text-gray-700 mb-1">
                        <span>{{ $locale['flag'] }} {{ $fieldLabel }}</span>
                        <button type="button"
                                @click="translateField('{{ $fieldName }}', '{{ $code }}')"
                                :disabled="fieldTranslating === '{{ $code }}_{{ $fieldName }}'"
                                class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 hover:text-blue-800 transition disabled:opacity-50 disabled:cursor-wait"
                                title="Auto-translate this field">
                            <template x-if="fieldTranslating === '{{ $code }}_{{ $fieldName }}'">
                                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </template>
                            <template x-if="fieldTranslating !== '{{ $code }}_{{ $fieldName }}'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
                            </template>
                            Translate
                        </button>
                    </label>

                    @if($fieldType === 'textarea')
                        <textarea name="translations[{{ $code }}][{{ $fieldName }}]"
                                  id="translations_{{ $code }}_{{ $fieldName }}"
                                  rows="{{ $field['rows'] ?? 3 }}"
                                  placeholder="{{ $field['placeholder'] ?? "Enter {$fieldLabel} in {$locale['name']}" }}"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">{{ $currentValue }}</textarea>
                    @elseif($fieldType === 'richtext')
                        @include('admin.partials.rich-editor', [
                            'name'        => "translations[{$code}][{$fieldName}]",
                            'id'          => "translations_{$code}_{$fieldName}",
                            'value'       => $currentValue,
                            'rows'        => $field['rows'] ?? 'medium',
                            'placeholder' => $field['placeholder'] ?? "Enter {$fieldLabel} in {$locale['name']}",
                            'label'       => null,
                        ])
                    @else
                        <input type="text"
                               name="translations[{{ $code }}][{{ $fieldName }}]"
                               id="translations_{{ $code }}_{{ $fieldName }}"
                               value="{{ $currentValue }}"
                               placeholder="{{ $field['placeholder'] ?? "Enter {$fieldLabel} in {$locale['name']}" }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#FEBC11] focus:border-[#FEBC11] text-sm">
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>

@pushOnce('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('translationPanel', (config) => ({
        langTab: 'fr',
        translating: false,
        fieldTranslating: null,
        translateMsg: '',
        translateError: false,
        fields: config.fields || [],
        langCodes: { fr: 'fr', de: 'de', es: 'es' },

        init() {
            // Auto-dismiss status messages
            this.$watch('translateMsg', (val) => {
                if (val) setTimeout(() => this.translateMsg = '', 4000);
            });
        },

        syncQuillEditors() {
            // Sync all rich text editors to their textareas
            if (typeof window.syncAllEditors === 'function') {
                window.syncAllEditors();
            }
        },

        copyFromEnglish() {
            this.syncQuillEditors();
            this.fields.forEach(field => {
                const source = document.querySelector('[name=\'' + field + '\']');
                const target = document.querySelector('[name=\'translations[' + this.langTab + '][' + field + ']\']');
                if (source && target) {
                    target.value = source.value;
                    target.dispatchEvent(new Event('input', { bubbles: true }));
                    // Also update rich text editor if present
                    const editorId = 'translations_' + this.langTab + '_' + field;
                    if (typeof window.setEditorContent === 'function') {
                        window.setEditorContent(editorId, source.value);
                    }
                }
            });
        },

        async translateText(text, targetLang) {
            if (!text || !text.trim()) return '';

            // Strip HTML for plain-text translation, preserve for display
            const isHtml = /<[a-z][\s\S]*>/i.test(text);
            const plainText = isHtml
                ? text.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim()
                : text.trim();

            if (!plainText) return '';

            const url = 'https://api.mymemory.translated.net/get?'
                + new URLSearchParams({ q: plainText.substring(0, 4500), langpair: 'en|' + targetLang });

            const resp = await fetch(url);
            const data = await resp.json();

            if (data.responseStatus === 200 && data.responseData.translatedText) {
                let translated = data.responseData.translatedText;
                // MyMemory sometimes returns ALL CAPS for untranslated — detect and skip
                if (translated === translated.toUpperCase() && plainText !== plainText.toUpperCase()) {
                    return plainText; // Fallback to original
                }
                return translated;
            }
            throw new Error(data.responseData?.translatedText || 'Translation failed');
        },

        async translateField(fieldName, langCode) {
            this.syncQuillEditors();
            const source = document.querySelector('[name=\'' + fieldName + '\']');
            const target = document.querySelector('[name=\'translations[' + langCode + '][' + fieldName + ']\']');
            if (!source || !target) return;

            const text = source.value;
            if (!text || !text.trim()) {
                this.translateMsg = 'No English content to translate for this field.';
                this.translateError = true;
                return;
            }

            this.fieldTranslating = langCode + '_' + fieldName;
            try {
                const translated = await this.translateText(text, langCode);
                target.value = translated;
                target.dispatchEvent(new Event('input', { bubbles: true }));
                // Update rich text editor if this is a rich text field
                const editorId = 'translations_' + langCode + '_' + fieldName;
                if (typeof window.setEditorContent === 'function') {
                    window.setEditorContent(editorId, translated);
                }
            } catch (e) {
                this.translateMsg = 'Translation failed: ' + e.message;
                this.translateError = true;
            } finally {
                this.fieldTranslating = null;
            }
        },

        async autoTranslate() {
            this.syncQuillEditors();
            this.translating = true;
            this.translateMsg = '';
            this.translateError = false;
            const lang = this.langTab;
            let success = 0, failed = 0;

            for (const field of this.fields) {
                const source = document.querySelector('[name=\'' + field + '\']');
                const target = document.querySelector('[name=\'translations[' + lang + '][' + field + ']\']');
                if (!source || !target) continue;

                const text = source.value;
                if (!text || !text.trim()) continue;

                this.fieldTranslating = lang + '_' + field;
                try {
                    const translated = await this.translateText(text, lang);
                    target.value = translated;
                    target.dispatchEvent(new Event('input', { bubbles: true }));
                    // Update rich text editor if this is a rich text field
                    const editorId = 'translations_' + lang + '_' + field;
                    if (typeof window.setEditorContent === 'function') {
                        window.setEditorContent(editorId, translated);
                    }
                    success++;
                } catch (e) {
                    failed++;
                }
                // Small delay to respect rate limits
                await new Promise(r => setTimeout(r, 300));
            }

            this.fieldTranslating = null;
            this.translating = false;

            if (failed > 0) {
                this.translateMsg = `Translated ${success} field(s), ${failed} failed. You can retry individual fields.`;
                this.translateError = true;
            } else if (success > 0) {
                this.translateMsg = `Successfully translated ${success} field(s) to ${({fr:'French',de:'German',es:'Spanish'})[lang]}.`;
                this.translateError = false;
            } else {
                this.translateMsg = 'No English content found to translate.';
                this.translateError = true;
            }
        },
    }));
});
</script>
@endPushOnce
