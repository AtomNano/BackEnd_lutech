<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssetController extends Controller
{
    /**
     * Securely serve a gallery asset.
     */
    public function show(Gallery $gallery): StreamedResponse
    {
        // 1. Validate existence in the local (private) disk
        if (!Storage::disk('local')->exists($gallery->url)) {
            abort(404, 'Asset not found or unauthorized.');
        }

        return Storage::disk('local')->response($gallery->url);
    }

    /**
     * Securely serve a ticket attachment asset.
     */
    public function showTicketAttachment(TicketAttachment $attachment): StreamedResponse
    {
        if (!Storage::disk('local')->exists($attachment->file_path)) {
            abort(404, 'Attachment not found or unauthorized.');
        }

        return Storage::disk('local')->response($attachment->file_path, $attachment->file_name);
    }
}
