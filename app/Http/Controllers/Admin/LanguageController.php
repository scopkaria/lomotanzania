<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Traits\HasBulkActions;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    use HasBulkActions;

    protected function bulkModel(): string { return Language::class; }
    protected function allowedBulkActions(): array { return ['delete', 'activate', 'deactivate']; }

    public function index()
    {
        $languages = Language::orderBy('sort_order')->get();
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.form', ['language' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:50',
            'code'        => 'required|string|max:5|unique:languages,code',
            'native_name' => 'nullable|string|max:50',
            'flag'        => 'nullable|string|max:10',
            'is_default'  => 'boolean',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data['is_default'] = $data['is_default'] ?? false;
        $data['is_active']  = $data['is_active'] ?? true;

        if ($data['is_default']) {
            Language::where('is_default', true)->update(['is_default' => false]);
        }

        Language::create($data);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully.');
    }

    public function edit(Language $language)
    {
        return view('admin.languages.form', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:50',
            'code'        => "required|string|max:5|unique:languages,code,{$language->id}",
            'native_name' => 'nullable|string|max:50',
            'flag'        => 'nullable|string|max:10',
            'is_default'  => 'boolean',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data['is_default'] = $data['is_default'] ?? false;
        $data['is_active']  = $data['is_active'] ?? true;

        if ($data['is_default']) {
            Language::where('is_default', true)->where('id', '!=', $language->id)->update(['is_default' => false]);
        }

        $language->update($data);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully.');
    }

    public function destroy(Language $language)
    {
        if ($language->is_default) {
            return redirect()->route('admin.languages.index')
                ->with('error', 'Cannot delete the default language.');
        }

        $language->delete();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully.');
    }
}
