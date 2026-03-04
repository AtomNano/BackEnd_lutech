<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // GET /v1/tickets — list semua tiket (paginated, filter status)
    public function index(Request $request)
    {
        $query = Ticket::with(['customer', 'technician'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->whereHas(
                'customer',
                fn($q) =>
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")
            )->orWhere('subject', 'like', "%{$search}%");
        }

        return TicketResource::collection($query->paginate(20));
    }

    // GET /v1/tickets/{id}
    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket->load(['customer', 'technician']));
    }

    // POST /v1/tickets — buat tiket baru + customer baru secara otomatis
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'jenis_device' => 'required|string|in:LAPTOP,PC,PRINTER,HANDPHONE,LAINNYA',
            'merk_device' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'estimasi' => 'nullable|integer|min:0',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        // Buat atau cari customer berdasarkan whatsapp
        $customer = Customer::firstOrCreate(
            ['whatsapp' => $data['whatsapp']],
            ['nama' => $data['nama'], 'whatsapp' => $data['whatsapp']]
        );
        $customer->update(['nama' => $data['nama']]);

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'user_id' => $request->user()->id,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'jenis_device' => $data['jenis_device'],
            'merk_device' => $data['merk_device'],
            'estimasi' => $data['estimasi'] ?? 0,
            'biaya_final' => 0,
            'status' => 'open',
            'priority' => $data['priority'] ?? 'medium',
        ]);

        return new TicketResource($ticket->load(['customer', 'technician']));
    }

    // PATCH /v1/tickets/{id}/status
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->update(['status' => $request->status]);

        return new TicketResource($ticket->load(['customer', 'technician']));
    }

    // PATCH /v1/tickets/{id}/cost — update biaya estimasi & final
    public function updateCost(Request $request, Ticket $ticket)
    {
        $request->validate([
            'estimasi' => 'nullable|integer|min:0',
            'biaya_final' => 'nullable|integer|min:0',
        ]);

        $ticket->update($request->only(['estimasi', 'biaya_final']));

        return new TicketResource($ticket->fresh(['customer', 'technician']));
    }

    // DELETE /v1/tickets/{id}
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response()->json(['message' => 'Tiket dihapus.']);
    }

    // GET /v1/track/{query} — PUBLIC (Pelacakan)
    public function trackPublic($query)
    {
        // Bersihkan tanda '#' jika ada
        $cleanQuery = ltrim($query, '#');

        // Cari tiket berdasarkan potongan ID (sejak user melihat ID pendek misal 019cae00)
        $ticket = Ticket::with(['customer', 'technician'])
            ->where('id', 'LIKE', $cleanQuery . '%')
            ->first();

        // Jika tidak ketemu berdasarkan ID, coba cari berdasar WA customer
        if (!$ticket) {
            $ticket = Ticket::with(['customer', 'technician'])
                ->whereHas('customer', function ($q) use ($cleanQuery) {
                    $q->where('whatsapp', $cleanQuery);
                })
                ->orderBy('created_at', 'desc')
                ->first();
        }

        if (!$ticket) {
            return response()->json(['message' => 'Tiket atau Nomor WhatsApp tidak ditemukan.'], 404);
        }

        return new TicketResource($ticket);
    }
}
