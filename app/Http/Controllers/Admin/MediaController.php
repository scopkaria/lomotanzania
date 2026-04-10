<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        $media = $query->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files'   => 'required|array',
            'files.*' => 'required|file|mimes:'.implode(',', config('uploads.media_library_mimes', [])).'|max:'.config('uploads.max_upload_kb', 20480),
        ]);

        $uploaded = [];

        foreach ($request->file('files') as $file) {
            $path = $file->store('media', 'public');

            $uploaded[] = Media::create([
                'filename'  => $file->getClientOriginalName(),
                'path'      => $path,
                'mime_type' => $file->getMimeType(),
                'size'      => $file->getSize(),
                'disk'      => 'public',
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['media' => $uploaded]);
        }

        return back()->with('success', count($uploaded) . ' file(s) uploaded.');
    }

    public function update(Request $request, Media $medium)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
        ]);

        $medium->update($request->only('alt_text'));

        if ($request->wantsJson()) {
            return response()->json(['media' => $medium]);
        }

        return back()->with('success', 'Media updated.');
    }

    public function destroy(Media $medium)
    {
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Media deleted.');
    }

    /**
     * JSON endpoint for the media picker modal.
     * Returns paginated results so the admin can load ALL media.
     */
    public function json(Request $request)
    {
        $kind = (string) $request->input('kind', 'all');

        $query = Media::query();

        if ($kind === 'video') {
            $query->where('mime_type', 'like', 'video/%');
        } elseif ($kind === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        } elseif ($kind === 'media') {
            $query->where(function ($q) {
                $q->where('mime_type', 'like', 'image/%')
                    ->orWhere('mime_type', 'like', 'video/%');
            });
        }
        // kind === 'all' → no mime filter

        $query->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        $page    = max(1, (int) $request->input('page', 1));
        $perPage = 60;
        $items   = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data'         => $items->items(),
            'current_page' => $items->currentPage(),
            'last_page'    => $items->lastPage(),
            'total'        => $items->total(),
        ]);
    }

    /**
     * Bulk delete media items.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        $items = Media::whereIn('id', $request->input('ids'))->get();
        $count = 0;

        foreach ($items as $item) {
            Storage::disk($item->disk)->delete($item->path);
            $item->delete();
            $count++;
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'deleted' => $count]);
        }

        return back()->with('success', "{$count} file(s) deleted.");
    }
}
