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
            'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg,pdf,mp4|max:10240',
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
     */
    public function json(Request $request)
    {
        $query = Media::where(function ($q) {
            $q->where('mime_type', 'like', 'image/%');
        })->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        return response()->json($query->limit(60)->get());
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
