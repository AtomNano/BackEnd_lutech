<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    // PATCH /{id}/stock (Update stok saat barang terpakai)
    public function adjustStock(Request $request, Inventory $inventory)
    {
        $request->validate(['qty_used' => 'required|integer|min:1']);
        if ($inventory->stok < $request->qty_used) {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }
        $inventory->decrement('stok', $request->qty_used);
        return response()->json(['message' => 'Stok dikurangi', 'stok_sekarang' => $inventory->stok]);
    }
}
