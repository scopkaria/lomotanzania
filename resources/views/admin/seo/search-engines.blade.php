<x-app-layout>
    <div class="max-w-6xl space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Search Engines</h1>
                <p class="mt-1 text-sm text-gray-500">Manage verification tags, sitemap submission, and indexing readiness for Google, Bing, Yandex, and Baidu.</p>
            </div>
            <a href="{{ route('sitemap.xml') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 21l-7.5-4.5-7.5 4.5V5.25A2.25 2.25 0 016.75 3h10.5A2.25 2.25 0 0119.5 5.25V21Z"/></svg>
                Open Sitemap
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Indexable URLs</p>
                <p class="mt-2 text-3xl font-bold text-[#083321]">{{ $stats['indexable_urls'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Safaris, destinations, pages, and blog posts</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Verified Engines</p>
                <p class="mt-2 text-3xl font-bold text-[#083321]">{{ $stats['verified_engines'] }}/4</p>
                <p class="mt-1 text-xs text-gray-500">Verification tags saved and ready to publish</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Published Safaris</p>
                <p class="mt-2 text-3xl font-bold text-[#083321]">{{ $stats['published_safaris'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Primary product pages available for indexing</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Sitemap Status</p>
                <p class="mt-2 text-lg font-bold text-[#083321]">{{ $stats['sitemap_ready'] ? 'Ready to submit' : 'Needs attention' }}</p>
                <p class="mt-1 text-xs text-gray-500 break-all">{{ $stats['sitemap_url'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.4fr,0.9fr]">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-gray-900">Verification & Submission</h2>
                    <p class="mt-1 text-sm text-gray-500">Paste the verification values from each webmaster tool. The corresponding meta tags are added automatically on the frontend.</p>
                </div>

                <form action="{{ route('admin.seo.search-engines.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="google_search_console" class="mb-1 block text-sm font-medium text-gray-700">Google Search Console</label>
                        <input id="google_search_console" name="google_search_console" type="text" value="{{ old('google_search_console', $setting->google_search_console) }}" placeholder="google-site-verification content" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-[#083321] focus:ring-[#083321]">
                    </div>

                    <div>
                        <label for="bing_webmaster_code" class="mb-1 block text-sm font-medium text-gray-700">Bing Webmaster Tools</label>
                        <input id="bing_webmaster_code" name="bing_webmaster_code" type="text" value="{{ old('bing_webmaster_code', $setting->bing_webmaster_code) }}" placeholder="msvalidate.01 content" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-[#083321] focus:ring-[#083321]">
                    </div>

                    <div>
                        <label for="yandex_verification_code" class="mb-1 block text-sm font-medium text-gray-700">Yandex Webmaster</label>
                        <input id="yandex_verification_code" name="yandex_verification_code" type="text" value="{{ old('yandex_verification_code', $setting->yandex_verification_code) }}" placeholder="yandex-verification content" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-[#083321] focus:ring-[#083321]">
                    </div>

                    <div>
                        <label for="baidu_verification_code" class="mb-1 block text-sm font-medium text-gray-700">Baidu</label>
                        <input id="baidu_verification_code" name="baidu_verification_code" type="text" value="{{ old('baidu_verification_code', $setting->baidu_verification_code) }}" placeholder="baidu-site-verification content" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-[#083321] focus:ring-[#083321]">
                    </div>

                    <div>
                        <label for="sitemap_url" class="mb-1 block text-sm font-medium text-gray-700">Sitemap URL</label>
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <input id="sitemap_url" type="text" readonly value="{{ $stats['sitemap_url'] }}" class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-600 shadow-sm">
                            <button type="button" onclick='navigator.clipboard.writeText(@json($stats["sitemap_url"]))' class="rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">Copy</button>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button type="submit" class="rounded-xl bg-[#083321] px-5 py-3 text-sm font-semibold text-white hover:bg-[#0a4a30]">Save Search Engines</button>
                        <a href="https://search.google.com/search-console" target="_blank" rel="noopener" class="rounded-xl border border-gray-200 px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">Open Google</a>
                        <a href="https://www.bing.com/webmasters" target="_blank" rel="noopener" class="rounded-xl border border-gray-200 px-5 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50">Open Bing</a>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Indexing Readiness</h2>
                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                            <span>Google</span>
                            <span class="font-semibold {{ $setting->google_search_console ? 'text-green-600' : 'text-amber-600' }}">{{ $setting->google_search_console ? 'Verified tag set' : 'Pending' }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                            <span>Bing</span>
                            <span class="font-semibold {{ $setting->bing_webmaster_code ? 'text-green-600' : 'text-amber-600' }}">{{ $setting->bing_webmaster_code ? 'Verified tag set' : 'Pending' }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                            <span>Yandex</span>
                            <span class="font-semibold {{ $setting->yandex_verification_code ? 'text-green-600' : 'text-amber-600' }}">{{ $setting->yandex_verification_code ? 'Verified tag set' : 'Pending' }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3">
                            <span>Baidu</span>
                            <span class="font-semibold {{ $setting->baidu_verification_code ? 'text-green-600' : 'text-amber-600' }}">{{ $setting->baidu_verification_code ? 'Verified tag set' : 'Pending' }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Basic SEO Stats</h2>
                    <dl class="mt-4 space-y-3 text-sm text-gray-600">
                        <div class="flex items-center justify-between"><dt>Destinations</dt><dd class="font-semibold text-gray-900">{{ $stats['destinations'] }}</dd></div>
                        <div class="flex items-center justify-between"><dt>Countries</dt><dd class="font-semibold text-gray-900">{{ $stats['countries'] }}</dd></div>
                        <div class="flex items-center justify-between"><dt>Pages</dt><dd class="font-semibold text-gray-900">{{ $stats['pages'] }}</dd></div>
                        <div class="flex items-center justify-between"><dt>Blog posts</dt><dd class="font-semibold text-gray-900">{{ $stats['posts'] }}</dd></div>
                    </dl>
                    <p class="mt-4 rounded-xl bg-[#083321]/5 px-4 py-3 text-xs text-gray-600">Tip: after saving the verification codes, submit <span class="font-semibold">{{ $stats['sitemap_url'] }}</span> in each webmaster console to speed up crawling.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
