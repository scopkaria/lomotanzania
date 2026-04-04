<x-app-layout>
    <x-slot name="header">Safari Planner Settings</x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-3xl">
        <p class="text-sm text-gray-500 mb-8">Customise the title and description shown on each step of the safari planner form.</p>

        <form action="{{ route('admin.planner-settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            @foreach($steps as $key => $label)
                @php $setting = $settings->get($key); @endphp
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-brand-dark uppercase tracking-wide mb-4">{{ $label }}</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="steps[{{ $key }}][title]"
                                   value="{{ old("steps.{$key}.title", $setting->title ?? '') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm"
                                   placeholder="Step title">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="steps[{{ $key }}][description]" rows="2"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm"
                                      placeholder="Step description">{{ old("steps.{$key}.description", $setting->description ?? '') }}</textarea>
                        </div>

                        @if($key === 'budget')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Budget Ranges <span class="text-xs text-gray-400">(one per line)</span></label>
                                <textarea name="steps[{{ $key }}][options]" rows="4"
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-gold focus:ring-brand-gold text-sm font-mono"
                                          placeholder="$2,000 – $5,000&#10;$5,000 – $10,000&#10;$10,000 – $20,000&#10;$20,000+">{{ old("steps.{$key}.options", $setting && $setting->options ? implode("\n", $setting->options) : '') }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="pt-4 border-t border-gray-200">
                <button type="submit"
                        class="px-6 py-2.5 bg-brand-gold text-brand-dark text-sm font-bold uppercase tracking-wide rounded-lg hover:brightness-90 transition">
                    Save Planner Settings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
