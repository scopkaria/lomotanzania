{{-- ============================================================
     Global Auto-Translate Button — Inline usage for forms
     that use custom language tabs (Pages, Posts, etc.)
     
     Usage: @include('admin.partials.auto-translate-button')
     
     Requires: x-data with langTab property on a parent element.
     Works by finding all input/textarea pairs where the EN field
     has the same base name pattern as the target language field.
     ============================================================ --}}

<div x-data="autoTranslator()" class="inline-flex items-center gap-2">
    <button type="button"
        @click="translateCurrentTab()"
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

    {{-- Status message --}}
    <span x-show="statusMsg" x-transition.opacity
          :class="statusError ? 'text-red-600' : 'text-green-600'"
          class="text-[11px] font-medium"
          x-text="statusMsg"></span>
</div>

@pushOnce('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('autoTranslator', () => ({
        translating: false,
        statusMsg: '',
        statusError: false,

        async translateText(text, targetLang) {
            if (!text || !text.trim()) return '';
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
                if (translated === translated.toUpperCase() && plainText !== plainText.toUpperCase()) {
                    return plainText;
                }
                return translated;
            }
            throw new Error(data.responseData?.translatedText || 'Translation failed');
        },

        async translateCurrentTab() {
            // Find the current langTab from Alpine scope
            const langTab = this.$el.closest('[x-data]')?.parentElement?.closest('[x-data]')?.__x?.$data?.langTab
                         || Alpine.evaluate(this.$el.closest('form'), 'langTab')
                         || null;

            if (!langTab || langTab === 'en') {
                this.statusMsg = 'Switch to FR, DE, or ES tab first.';
                this.statusError = true;
                setTimeout(() => this.statusMsg = '', 3000);
                return;
            }

            this.translating = true;
            this.statusMsg = '';
            this.statusError = false;

            const form = this.$el.closest('form');
            if (!form) return;

            // Find all English source fields (name patterns like title[en], content[en], etc.)
            const enFields = form.querySelectorAll('[name$="[en]"]');
            let success = 0, failed = 0;

            for (const enField of enFields) {
                const enName = enField.name;
                const targetName = enName.replace('[en]', '[' + langTab + ']');
                const targetField = form.querySelector('[name="' + targetName + '"]');

                if (!targetField) continue;
                const text = enField.value;
                if (!text || !text.trim()) continue;

                try {
                    const translated = await this.translateText(text, langTab);
                    targetField.value = translated;
                    targetField.dispatchEvent(new Event('input', { bubbles: true }));
                    success++;
                } catch (e) {
                    failed++;
                }
                await new Promise(r => setTimeout(r, 300));
            }

            this.translating = false;
            const langNames = { fr: 'French', de: 'German', es: 'Spanish' };
            if (failed > 0) {
                this.statusMsg = `${success} translated, ${failed} failed`;
                this.statusError = true;
            } else if (success > 0) {
                this.statusMsg = `${success} field(s) → ${langNames[langTab] || langTab}`;
                this.statusError = false;
            } else {
                this.statusMsg = 'No English content found.';
                this.statusError = true;
            }
            setTimeout(() => this.statusMsg = '', 4000);
        },
    }));
});
</script>
@endPushOnce
