<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller

{
    /**
     * List all inventory items for the active workspace.
     */
    public function index(): JsonResponse
    {
        return response()->json(Inventory::orderBy('nama_barang')->get());
    }

    /**
     * Store a new inventory item.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
        ]);

        $item = Inventory::create($validated);
        return response()->json($item, 201);
    }

    /**
     * Update an inventory item.
     */
    public function update(Request $request, Inventory $inventory): JsonResponse
    {
        $validated = $request->validate([
            'nama_barang' => 'sometimes|string|max:255',
            'stok' => 'sometimes|integer|min:0',
        ]);

        $inventory->update($validated);
        return response()->json($inventory);
    }

    /**
     * PATCH /{id}/stock (Update stok saat barang terpakai)
     */
    public function adjustStock(Request $request, Inventory $inventory): JsonResponse
    {
        $request->validate(['qty_used' => 'required|integer|min:1']);
        
        if ($inventory->stok < $request->qty_used) {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }

        $inventory->decrement('stok', $request->qty_used);
        
        return response()->json([
            'message' => 'Stok dikurangi', 
            'stok_sekarang' => $inventory->stok
        ]);
    }

    /**
     * Delete an inventory item.
     */
    public function destroy(Inventory $inventory): JsonResponse
    {
        $inventory->delete();
        return response()->json(['message' => 'Item berhasil dihapus.']);
    }
}

