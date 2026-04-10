<?php
// ADDED: Image upload endpoint for rich text editor (Jodit)

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorUploadController extends Controller
{
    /**
     * Handle image uploads from the rich text editor.
     * Validates file type and size, stores securely in Laravel storage.
     * Also registers the file in the Media library so it appears in pickers.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:'.implode(',', config('uploads.editor_image_mimes', [])).'|max:'.config('uploads.max_upload_kb', 20480),
        ]);

        $file = $request->file('file');
        $path = $file->store('editor-images/' . date('Y/m'), 'public');

        // Register in Media table so it shows up in the admin media library
        Media::create([
            'filename'  => $file->getClientOriginalName(),
            'path'      => $path,
            'mime_type' => $file->getMimeType(),
            'size'      => $file->getSize(),
            'disk'      => 'public',
        ]);

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }
}
