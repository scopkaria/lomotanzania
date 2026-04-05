<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuBuilderController extends Controller
{
    public function index()
    {
        $this->ensureDefaults();

        $menuItems = MenuItem::ordered()->get();

        return view('admin.appearance.menu-builder', compact('menuItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:500'],
            'open_in_new_tab' => ['nullable', 'boolean'],
        ]);

        MenuItem::create([
            'label' => $data['label'],
            'url' => $data['url'],
            'open_in_new_tab' => $request->boolean('open_in_new_tab'),
            'is_enabled' => true,
            'sort_order' => (int) MenuItem::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Menu item added successfully.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:500'],
            'is_enabled' => ['nullable', 'boolean'],
            'open_in_new_tab' => ['nullable', 'boolean'],
        ]);

        $menuItem->update([
            'label' => $data['label'],
            'url' => $menuItem->isSystemItem() ? $menuItem->url : ($data['url'] ?? null),
            'is_enabled' => $request->boolean('is_enabled'),
            'open_in_new_tab' => $request->boolean('open_in_new_tab'),
        ]);

        return back()->with('success', 'Menu item updated.');
    }

    public function sort(Request $request)
    {
        $data = $request->validate([
            'ordered_ids' => ['required', 'string'],
        ]);

        $ids = collect(explode(',', $data['ordered_ids']))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        foreach ($ids as $index => $id) {
            MenuItem::whereKey($id)->update(['sort_order' => $index + 1]);
        }

        return back()->with('success', 'Menu order saved.');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->isSystemItem()) {
            return back()->with('error', 'Core menu items can be disabled, not deleted.');
        }

        $menuItem->delete();

        return back()->with('success', 'Menu item removed.');
    }

    private function ensureDefaults(): void
    {
        $defaults = [
            ['label' => 'Home', 'slug' => 'home', 'sort_order' => 1],
            ['label' => 'Destinations', 'slug' => 'destinations', 'sort_order' => 2],
            ['label' => 'Safaris', 'slug' => 'safaris', 'sort_order' => 3],
            ['label' => 'Experiences', 'slug' => 'experiences', 'sort_order' => 4],
            ['label' => 'Blog', 'slug' => 'blog', 'sort_order' => 5],
            ['label' => 'About', 'slug' => 'about', 'sort_order' => 6],
            ['label' => 'Contact', 'slug' => 'contact', 'sort_order' => 7],
        ];

        foreach ($defaults as $item) {
            MenuItem::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'label' => $item['label'],
                    'is_enabled' => true,
                    'open_in_new_tab' => false,
                    'sort_order' => $item['sort_order'],
                ]
            );
        }
    }
}
