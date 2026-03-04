<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('tickets')
            ->orderByDesc('last_service_at')
            ->orderBy('nama');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($tier = $request->input('tier')) {
            $query->where('tier', $tier);
        }

        return CustomerResource::collection($query->paginate(20));
    }

    public function show(Customer $customer): CustomerResource
    {
        $customer->loadCount('tickets');
        $customer->load([
            'tickets' => function ($q) {
                $q->orderByDesc('created_at')->limit(20);
            }
        ]);

        return new CustomerResource($customer);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return (new CustomerResource($customer))->response()->setStatusCode(201);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return new CustomerResource($customer->fresh());
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        return response()->json(['message' => 'Pelanggan dihapus.']);
    }

    /**
     * POST /api/v1/customers/{customer}/add-points
     * Tambah poin manual (admin). Poin auto juga bisa dihitung dari tiket cost.
     */
    public function addPoints(Request $request, Customer $customer): JsonResponse
    {
        $request->validate(['points' => 'required|integer|min:1']);

        $customer->increment('points', $request->points);
        $customer->tier = $customer->recalculateTier();
        $customer->save();

        return response()->json([
            'message' => "Poin ditambahkan.",
            'points' => $customer->points,
            'tier' => $customer->tier,
        ]);
    }
}
