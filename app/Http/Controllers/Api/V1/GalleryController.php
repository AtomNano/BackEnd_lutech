<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    /**
     * List all gallery items.
     */
    public function index(): JsonResponse
    {
        $items = Gallery::latest()->get()->map(function ($item) {
            // Append full URL. If local, use the secure asset route.
            if (in_array($item->type, ['image', 'video'])) {
                $item->full_url = url("/api/v1/assets/{$item->id}");
            } else {
                $item->full_url = $item->url;
            }
            return $item;
        });

        return response()->json($items);
    }

    /**
     * Store a new gallery item (image, video, or social link).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:image,video,instagram,youtube',
            'caption' => 'nullable|string',
            'file' => 'required_if:type,image,video|file|max:20480', // Max 20MB for video
            'url' => 'required_if:type,instagram,youtube|string',
        ]);

        $type = $request->type;
        $url = $request->url;

        // Handle file uploads (Secure Local Storage)
        if (in_array($type, ['image', 'video'])) {
            $wsId = app(\App\Services\WorkspaceContext::class)->getWorkspaceId();
            $path = $request->file('file')->store("workspaces/{$wsId}/galleries", 'local');
            $url = $path;
        }

        $gallery = Gallery::create([
            'type' => $type,
            'url' => $url,
            'caption' => $request->caption,
        ]);

        return response()->json($gallery);
    }

    /**
     * Delete a gallery item.
     */
    public function destroy(Gallery $gallery): JsonResponse
    {
        // Cleanup file if it was an upload
        if (in_array($gallery->type, ['image', 'video'])) {
            if (Storage::disk('local')->exists($gallery->url)) {
                Storage::disk('local')->delete($gallery->url);
            }
        }
        
        $gallery->delete();

        return response()->json(['message' => 'Konten berhasil dihapus.']);
    }
}
