<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Http\Resources\TicketResource;

class TicketController extends Controller
{
    // GET / (Eager load customer & user)
    public function index()
    {
        $tickets = Ticket::with(['customer', 'technician'])->latest()->paginate(20);
        return TicketResource::collection($tickets);
    }

    // PATCH /{id}/status (Endpoint khusus)
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:open,in_progress,resolved,closed']);
        $ticket->update(['status' => $request->status]);
        return response()->json(['message' => 'Status diperbarui']);
    }
}
