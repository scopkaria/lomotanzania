{{-- Enhanced SEO Panel with Live Analysis --}}
{{-- Usage: @include('admin.partials.seo-panel', ['model' => $safari]) --}}
{{-- Requires: Alpine.js, fields named meta_title, meta_description, meta_keywords, og_image on form --}}

@php
    $model = $model ?? null;
    $seoMeta = null;
    if ($model && method_exists($model, 'seoMeta')) {
        $seoMeta = $model->seoMeta('en');
    }
@endphp

<div x-data="seoPanel({
        focusKeyword: '{{ old('focus_keyword', $seoMeta->focus_keyword ?? '') }}',
        metaTitle: '{{ old('meta_title', addslashes($model->meta_title ?? '')) }}',
        metaDescription: '{{ old('meta_description', addslashes($model->meta_description ?? '')) }}',
        slug: '{{ old('slug', $model->slug ?? '') }}',
        seoScore: {{ $seoMeta->seo_score ?? 0 }},
        readabilityScore: {{ $seoMeta->readability_score ?? 0 }},
    })"
    class="pt-6 pb-2 border-t border-gray-200">

    {{-- Section header with score badges --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <h3 class="text-lg font-bold text-gray-900">SEO Analysis</h3>
        </div>
        <div class="flex items-center gap-3">
            {{-- SEO Score --}}
            <div class="flex items-center gap-1.5">
                <div class="relative w-10 h-10">
                    <svg class="w-10 h-10 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.5" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.5" fill="none"
                            :stroke="scoreColor(seoScore)" stroke-width="3"
                            stroke-dasharray="97.4" :stroke-dashoffset="97.4 - (97.4 * seoScore / 100)"
                            stroke-linecap="round"/>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-xs font-bold" :class="scoreTextClass(seoScore)" x-text="seoScore"></span>
                </div>
                <span class="text-xs text-gray-500">SEO</span>
            </div>
            {{-- Readability Score --}}
            <div class="flex items-center gap-1.5">
                <div class="relative w-10 h-10">
                    <svg class="w-10 h-10 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.5" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.5" fill="none"
                            :stroke="scoreColor(readabilityScore)" stroke-width="3"
                            stroke-dasharray="97.4" :stroke-dashoffset="97.4 - (97.4 * readabilityScore / 100)"
                            stroke-linecap="round"/>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-xs font-bold" :class="scoreTextClass(readabilityScore)" x-text="readabilityScore"></span>
                </div>
                <span class="text-xs text-gray-500">Read</span>
            </div>
        </div>
    </div>

    {{-- Focus Keyword --}}
    <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Focus Keyword</label>
        <input type="text" name="focus_keyword" x-model="focusKeyword" @input.debounce.500ms="runAnalysis()"
               placeholder="e.g. Serengeti safari, Tanzania wildlife tour"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
        <p class="mt-1 text-xs text-gray-400">The main keyword you want this page to rank for.</p>
    </div>

    {{-- Slug Preview --}}
    <div class="mb-5 p-4 bg-gray-50 rounded-lg" x-show="slug || focusKeyword">
        <p class="text-xs text-gray-500 mb-1">Google Preview:</p>
        <p class="text-sm text-blue-700 font-medium truncate" x-text="metaTitle || 'Page Title'"></p>
        <p class="text-xs text-green-700 truncate">{{ url('') }}/en/<span x-text="slug || '...'"></span></p>
        <p class="text-xs text-gray-600 line-clamp-2" x-text="metaDescription || 'Add a meta description to see the Google preview...'"></p>
    </div>

    {{-- Meta Title --}}
    <div class="mb-5">
        <div class="flex items-center justify-between mb-1.5">
            <label for="meta_title" class="block text-sm font-medium text-gray-700">SEO Title</label>
            <span class="text-xs" :class="metaTitle.length > 65 ? 'text-red-500' : (metaTitle.length >= 30 ? 'text-green-600' : 'text-gray-400')"
                  x-text="metaTitle.length + '/65'"></span>
        </div>
        <input type="text" name="meta_title" id="meta_title" x-model="metaTitle" @input.debounce.500ms="runAnalysis()"
               value="{{ old('meta_title', $model->meta_title ?? '') }}"
               placeholder="Custom title for search engines"
               maxlength="70"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
        <div class="mt-1 h-1 rounded-full bg-gray-200 overflow-hidden">
            <div class="h-full rounded-full transition-all duration-300"
                 :style="'width:' + Math.min(100, metaTitle.length / 65 * 100) + '%'"
                 :class="metaTitle.length > 65 ? 'bg-red-500' : (metaTitle.length >= 30 ? 'bg-green-500' : 'bg-yellow-400')"></div>
        </div>
        @error('meta_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Meta Description --}}
    <div class="mb-5">
        <div class="flex items-center justify-between mb-1.5">
            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
            <span class="text-xs" :class="metaDescription.length > 160 ? 'text-red-500' : (metaDescription.length >= 120 ? 'text-green-600' : 'text-gray-400')"
                  x-text="metaDescription.length + '/160'"></span>
        </div>
        <textarea name="meta_description" id="meta_description" rows="3" maxlength="500"
                  x-model="metaDescription" @input.debounce.500ms="runAnalysis()"
                  placeholder="Describe this page for Google search results..."
                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">{{ old('meta_description', $model->meta_description ?? '') }}</textarea>
        <div class="mt-1 h-1 rounded-full bg-gray-200 overflow-hidden">
            <div class="h-full rounded-full transition-all duration-300"
                 :style="'width:' + Math.min(100, metaDescription.length / 160 * 100) + '%'"
                 :class="metaDescription.length > 160 ? 'bg-red-500' : (metaDescription.length >= 120 ? 'bg-green-500' : 'bg-yellow-400')"></div>
        </div>
        @error('meta_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Meta Keywords --}}
    <div class="mb-5">
        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1.5">Meta Keywords</label>
        <input type="text" name="meta_keywords" id="meta_keywords"
               value="{{ old('meta_keywords', $model->meta_keywords ?? '') }}"
               placeholder="safari, tanzania, serengeti, wildlife"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm">
        <p class="mt-1 text-xs text-gray-400">Comma-separated keywords.</p>
        @error('meta_keywords') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- OG Image --}}
    <div class="mb-6">
        @include('admin.media.picker', [
            'name'  => 'og_image',
            'value' => old('og_image', $model->og_image ?? ''),
            'label' => 'Social Share Image (OG Image)',
        ])
        <p class="mt-1 text-xs text-gray-400">Recommended: 1200×630px.</p>
    </div>

    {{-- Analysis Results Panel --}}
    <div class="border border-gray-200 rounded-lg overflow-hidden" x-show="checks.length > 0 || suggestions.length > 0">
        {{-- Tabs --}}
        <div class="flex border-b border-gray-200 bg-gray-50">
            <button type="button" @click="analysisTab = 'checks'"
                    :class="analysisTab === 'checks' ? 'border-b-2 border-brand-gold text-brand-dark font-semibold' : 'text-gray-500'"
                    class="px-4 py-2 text-sm transition-colors">
                Analysis <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full" :class="seoScore >= 71 ? 'bg-green-100 text-green-700' : (seoScore >= 41 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')" x-text="seoScore + '%'"></span>
            </button>
            <button type="button" @click="analysisTab = 'suggestions'"
                    :class="analysisTab === 'suggestions' ? 'border-b-2 border-brand-gold text-brand-dark font-semibold' : 'text-gray-500'"
                    class="px-4 py-2 text-sm transition-colors">
                Suggestions <span class="ml-1 text-xs text-gray-400" x-text="'(' + suggestions.length + ')'"></span>
            </button>
            <button type="button" @click="analysisTab = 'optimize'"
                    :class="analysisTab === 'optimize' ? 'border-b-2 border-brand-gold text-brand-dark font-semibold' : 'text-gray-500'"
                    class="px-4 py-2 text-sm transition-colors">
                Optimize
            </button>
        </div>

        {{-- Checks tab --}}
        <div x-show="analysisTab === 'checks'" class="p-4 max-h-64 overflow-y-auto space-y-2">
            <template x-for="check in checks" :key="check.key">
                <div class="flex items-start gap-2 text-sm">
                    <span class="mt-0.5 flex-shrink-0">
                        <template x-if="check.status === 'pass'">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </template>
                        <template x-if="check.status === 'warning'">
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        </template>
                        <template x-if="check.status === 'error'">
                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        </template>
                    </span>
                    <span :class="check.status === 'pass' ? 'text-gray-700' : (check.status === 'warning' ? 'text-yellow-800' : 'text-red-700')" x-text="check.message"></span>
                </div>
            </template>
            <div x-show="checks.length === 0" class="text-sm text-gray-400 italic">
                Add a focus keyword and content to see the analysis.
            </div>
        </div>

        {{-- Suggestions tab --}}
        <div x-show="analysisTab === 'suggestions'" class="p-4 max-h-64 overflow-y-auto space-y-3">
            <template x-for="(sug, i) in suggestions" :key="i">
                <div class="flex items-start gap-2 p-2 rounded-lg" :class="sug.type === 'error' ? 'bg-red-50' : 'bg-yellow-50'">
                    <span class="flex-shrink-0 mt-0.5 w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold"
                          :class="sug.type === 'error' ? 'bg-red-500' : 'bg-yellow-500'" x-text="i + 1"></span>
                    <div>
                        <p class="text-sm" :class="sug.type === 'error' ? 'text-red-800' : 'text-yellow-800'" x-text="sug.message"></p>
                    </div>
                </div>
            </template>
            <div x-show="suggestions.length === 0" class="text-sm text-green-600 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Great job! No improvement suggestions.
            </div>
        </div>

        {{-- Optimize tab --}}
        <div x-show="analysisTab === 'optimize'" class="p-4">
            <p class="text-sm text-gray-600 mb-3">Analyze your content for optimization opportunities.</p>
            <button type="button" @click="optimizeContent()"
                    :disabled="optimizing"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-brand-dark text-white text-sm rounded-lg hover:bg-gray-800 disabled:opacity-50 transition-colors">
                <svg x-show="!optimizing" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
                <svg x-show="optimizing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span x-text="optimizing ? 'Analyzing...' : 'Optimize Content'"></span>
            </button>

            {{-- Optimization results --}}
            <div x-show="optimizationResults.length > 0" class="mt-4 space-y-2">
                <template x-for="(result, i) in optimizationResults" :key="i">
                    <div class="p-3 rounded-lg border text-sm"
                         :class="{
                             'border-red-200 bg-red-50': result.priority === 'high',
                             'border-yellow-200 bg-yellow-50': result.priority === 'medium',
                             'border-blue-200 bg-blue-50': result.priority === 'low'
                         }">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-1.5 py-0.5 text-xs font-semibold rounded uppercase"
                                  :class="{
                                      'bg-red-200 text-red-800': result.priority === 'high',
                                      'bg-yellow-200 text-yellow-800': result.priority === 'medium',
                                      'bg-blue-200 text-blue-800': result.priority === 'low'
                                  }" x-text="result.priority"></span>
                            <span class="text-xs text-gray-500 capitalize" x-text="result.category"></span>
                        </div>
                        <p x-text="result.message"></p>
                        <p x-show="result.example" class="mt-1 text-xs text-gray-500 italic" x-text="result.example"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('seoPanel', (config) => ({
        focusKeyword: config.focusKeyword || '',
        metaTitle: config.metaTitle || '',
        metaDescription: config.metaDescription || '',
        slug: config.slug || '',
        seoScore: config.seoScore || 0,
        readabilityScore: config.readabilityScore || 0,
        checks: [],
        suggestions: [],
        analysisTab: 'checks',
        optimizing: false,
        optimizationResults: [],

        init() {
            // Watch for slug changes from the main form
            this.$nextTick(() => {
                const slugInput = document.querySelector('input[name="slug"]');
                if (slugInput) {
                    slugInput.addEventListener('input', () => {
                        this.slug = slugInput.value;
                        this.runAnalysis();
                    });
                    this.slug = this.slug || slugInput.value;
                }
            });

            // Run initial analysis if we have data
            if (this.focusKeyword || this.metaTitle) {
                this.runAnalysis();
            }
        },

        getContentText() {
            // Try to get content from common form fields
            const fields = ['description', 'content', 'content[en]', 'body'];
            for (const name of fields) {
                const el = document.querySelector(`[name="${name}"]`);
                if (el && el.value) return el.value;
            }
            // Try Quill editors
            const quillEl = document.querySelector('.ql-editor');
            if (quillEl) return quillEl.innerHTML;
            return '';
        },

        getTitleText() {
            // Get the main title from the form
            const fields = ['title', 'title[en]', 'name'];
            for (const name of fields) {
                const el = document.querySelector(`[name="${name}"]`);
                if (el && el.value) return el.value;
            }
            return this.metaTitle;
        },

        runAnalysis() {
            const content = this.getContentText();
            const title = this.getTitleText();
            const kw = this.focusKeyword.toLowerCase().trim();
            const plainText = this.stripTags(content);
            const wordCount = this.countWords(plainText);

            let checks = [];
            let totalScore = 0;

            // 1. Focus keyword (15 pts)
            if (!kw) {
                checks.push({ key: 'keyword', status: 'error', message: 'No focus keyword set.', score: 0, max: 15 });
            } else {
                checks.push({ key: 'keyword', status: 'pass', message: 'Focus keyword is set.', score: 15, max: 15 });
                totalScore += 15;
            }

            // 2. Title (15 pts)
            const titleText = this.metaTitle || title;
            const titleLen = titleText.length;
            if (!titleText) {
                checks.push({ key: 'title', status: 'error', message: 'Title is empty.', score: 0, max: 15 });
            } else {
                let ts = 0, msgs = [];
                if (titleLen >= 30 && titleLen <= 65) { ts += 7; msgs.push(`Title length is good (${titleLen} chars).`); }
                else if (titleLen < 30) { ts += 3; msgs.push(`Title is short (${titleLen} chars). Aim for 30-65.`); }
                else { ts += 4; msgs.push(`Title is long (${titleLen} chars). Keep under 65.`); }

                if (kw && titleText.toLowerCase().includes(kw)) { ts += 8; msgs.push('Keyword in title ✓'); }
                else if (kw) { msgs.push('Add keyword to title.'); }

                totalScore += ts;
                checks.push({ key: 'title', status: ts >= 12 ? 'pass' : (ts >= 7 ? 'warning' : 'error'), message: msgs.join(' '), score: ts, max: 15 });
            }

            // 3. Meta description (10 pts)
            const descLen = this.metaDescription.length;
            if (!this.metaDescription) {
                checks.push({ key: 'meta_desc', status: 'error', message: 'Meta description is empty.', score: 0, max: 10 });
            } else {
                let ds = 0, msgs = [];
                if (descLen >= 120 && descLen <= 160) { ds += 5; msgs.push(`Description length ideal (${descLen}).`); }
                else if (descLen > 160) { ds += 3; msgs.push(`Description too long (${descLen}). Keep under 160.`); }
                else { ds += 3; msgs.push(`Description short (${descLen}). Aim for 120-160.`); }

                if (kw && this.metaDescription.toLowerCase().includes(kw)) { ds += 5; msgs.push('Keyword in description ✓'); }
                else if (kw) { msgs.push('Add keyword to description.'); }

                totalScore += ds;
                checks.push({ key: 'meta_desc', status: ds >= 8 ? 'pass' : (ds >= 5 ? 'warning' : 'error'), message: msgs.join(' '), score: ds, max: 10 });
            }

            // 4. Content length (15 pts)
            if (wordCount === 0) {
                checks.push({ key: 'content', status: 'error', message: 'No content provided.', score: 0, max: 15 });
            } else if (wordCount >= 300) {
                totalScore += 15;
                checks.push({ key: 'content', status: 'pass', message: `Content length good (${wordCount} words).`, score: 15, max: 15 });
            } else if (wordCount >= 150) {
                totalScore += 10;
                checks.push({ key: 'content', status: 'warning', message: `Content could be longer (${wordCount} words). Aim for 300+.`, score: 10, max: 15 });
            } else {
                totalScore += 5;
                checks.push({ key: 'content', status: 'error', message: `Content too short (${wordCount} words). Add more.`, score: 5, max: 15 });
            }

            // 5. Keyword density (10 pts)
            if (kw && wordCount > 0) {
                const kwCount = (plainText.toLowerCase().match(new RegExp(this.escapeRegex(kw), 'gi')) || []).length;
                const density = (kwCount / wordCount) * 100;
                if (density >= 0.5 && density <= 3.0) {
                    totalScore += 10;
                    checks.push({ key: 'density', status: 'pass', message: `Keyword density ${density.toFixed(1)}% (${kwCount}×). Good.`, score: 10, max: 10 });
                } else if (density > 3.0) {
                    totalScore += 5;
                    checks.push({ key: 'density', status: 'warning', message: `Keyword density ${density.toFixed(1)}% — too high. Aim for 0.5-3%.`, score: 5, max: 10 });
                } else if (kwCount > 0) {
                    totalScore += 3;
                    checks.push({ key: 'density', status: 'warning', message: `Keyword density low (${density.toFixed(1)}%). Use keyword more.`, score: 3, max: 10 });
                } else {
                    checks.push({ key: 'density', status: 'error', message: 'Keyword not found in content.', score: 0, max: 10 });
                }
            }

            // 6. Headings (10 pts)
            const headings = content.match(/<h[1-6][^>]*>.*?<\/h[1-6]>/gi) || [];
            if (headings.length === 0 && wordCount > 0) {
                checks.push({ key: 'headings', status: 'warning', message: 'No headings found. Add H2/H3 headings.', score: 0, max: 10 });
            } else if (headings.length > 0) {
                let hs = 5;
                let msg = `${headings.length} heading(s) found.`;
                if (kw) {
                    const kwInH = headings.some(h => this.stripTags(h).toLowerCase().includes(kw));
                    if (kwInH) { hs += 5; msg += ' Keyword in heading ✓'; }
                    else { msg += ' Add keyword to a heading.'; }
                }
                totalScore += hs;
                checks.push({ key: 'headings', status: hs >= 8 ? 'pass' : 'warning', message: msg, score: hs, max: 10 });
            }

            // 7. Images (5 pts)
            const images = content.match(/<img[^>]+>/gi) || [];
            if (images.length === 0) {
                checks.push({ key: 'images', status: 'warning', message: 'No images in content.', score: 2, max: 5 });
                totalScore += 2;
            } else {
                const withAlt = images.filter(img => /alt\s*=\s*["'][^"']+["']/i.test(img)).length;
                if (withAlt === images.length) {
                    totalScore += 5;
                    checks.push({ key: 'images', status: 'pass', message: `${images.length} image(s) with alt text ✓`, score: 5, max: 5 });
                } else {
                    totalScore += 2;
                    checks.push({ key: 'images', status: 'warning', message: `${images.length - withAlt} image(s) missing alt text.`, score: 2, max: 5 });
                }
            }

            // 8. Internal links (5 pts)
            const linkMatches = content.match(/<a[^>]+href\s*=\s*["']([^"']+)["'][^>]*>/gi) || [];
            const internalLinks = linkMatches.filter(l => !l.includes('http') || l.includes(window.location.hostname));
            if (internalLinks.length >= 2) {
                totalScore += 5;
                checks.push({ key: 'links', status: 'pass', message: `${internalLinks.length} internal link(s) found.`, score: 5, max: 5 });
            } else if (internalLinks.length === 1) {
                totalScore += 3;
                checks.push({ key: 'links', status: 'warning', message: 'Only 1 internal link. Add more.', score: 3, max: 5 });
            } else {
                checks.push({ key: 'links', status: 'error', message: 'No internal links. Link to related content.', score: 0, max: 5 });
            }

            // 9. Slug (5 pts)
            if (this.slug && kw) {
                const kwSlug = kw.replace(/\s+/g, '-');
                if (this.slug.includes(kwSlug)) {
                    totalScore += 5;
                    checks.push({ key: 'slug', status: 'pass', message: 'Keyword in URL slug ✓', score: 5, max: 5 });
                } else {
                    totalScore += 3;
                    checks.push({ key: 'slug', status: 'warning', message: 'Consider adding keyword to slug.', score: 3, max: 5 });
                }
            }

            // 10. First paragraph (5 pts)
            if (kw && wordCount > 0) {
                const first200 = plainText.toLowerCase().split(/\s+/).slice(0, 200).join(' ');
                if (first200.includes(kw)) {
                    totalScore += 5;
                    checks.push({ key: 'first_para', status: 'pass', message: 'Keyword in first paragraph ✓', score: 5, max: 5 });
                } else {
                    checks.push({ key: 'first_para', status: 'warning', message: 'Use keyword in the first paragraph.', score: 0, max: 5 });
                }
            }

            this.seoScore = Math.min(100, Math.max(0, totalScore));
            this.checks = checks;

            // Build suggestions from failed checks
            this.suggestions = checks
                .filter(c => c.status === 'error' || c.status === 'warning')
                .map(c => ({ type: c.status, message: c.message }));

            // Calculate readability
            this.readabilityScore = this.calcReadability(plainText);
        },

        calcReadability(text) {
            if (!text.trim()) return 0;
            const sentences = text.split(/[.!?]+/).filter(s => s.trim());
            if (sentences.length === 0) return 30;

            let score = 0;

            // Sentence length
            const longSentences = sentences.filter(s => s.trim().split(/\s+/).length > 20).length;
            const longPct = (longSentences / sentences.length) * 100;
            if (longPct <= 25) score += 40;
            else if (longPct <= 50) score += 25;
            else score += 10;

            // Paragraphs
            const paragraphs = text.split(/\n\s*\n/).filter(p => p.trim());
            if (paragraphs.length >= 3) score += 30;
            else if (paragraphs.length >= 2) score += 20;
            else score += 10;

            // Transition words
            const transitions = ['however', 'therefore', 'moreover', 'furthermore', 'additionally', 'meanwhile', 'consequently', 'for example', 'in addition'];
            const lowerText = text.toLowerCase();
            const transCount = transitions.filter(t => lowerText.includes(t)).length;
            if (transCount >= 3) score += 30;
            else if (transCount >= 1) score += 20;
            else score += 5;

            return Math.min(100, Math.max(0, score));
        },

        async optimizeContent() {
            this.optimizing = true;
            this.optimizationResults = [];

            try {
                const content = this.getContentText();
                const title = this.getTitleText();

                const response = await fetch('/admin/seo/optimize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        title: title,
                        content: content,
                        focus_keyword: this.focusKeyword,
                    }),
                });

                const data = await response.json();
                this.optimizationResults = data.suggestions || [];
            } catch (e) {
                this.optimizationResults = [{ category: 'error', priority: 'high', message: 'Could not analyze content. Please try again.' }];
            } finally {
                this.optimizing = false;
            }
        },

        scoreColor(score) {
            if (score >= 71) return '#22c55e';
            if (score >= 41) return '#eab308';
            return '#ef4444';
        },

        scoreTextClass(score) {
            if (score >= 71) return 'text-green-600';
            if (score >= 41) return 'text-yellow-600';
            return 'text-red-600';
        },

        stripTags(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        },

        countWords(text) {
            return text.trim().split(/\s+/).filter(w => w.length > 0).length;
        },

        escapeRegex(str) {
            return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        },
    }));
});
</script>
@endPushOnce
