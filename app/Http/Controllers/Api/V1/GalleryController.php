<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // POST /
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // Max 2MB
            'title' => 'required|string'
        ]);

        $path = $request->file('image')->store('galleries', 'public');

        $gallery = Gallery::create([
            'image_path' => $path,
            'title' => $request->title,
        ]);
        return response()->json($gallery);
    }

    // DELETE /{id}
    public function destroy(Gallery $gallery)
    {
        // Hapus file fisik dari storage dulu
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete(); // Baru hapus record DB
        return response()->json(['message' => 'Gambar dihapus']);
    }
}
