<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance;

class FinanceController extends Controller
{
    // GET /summary (Endpoint khusus chart Dashboard)
    public function summary()
    {
        // Hindari meload semua baris data ke memori (jangan pakai ::all()). Gunakan agregasi DB.
        $currentMonth = now()->format('Y-m');
        $summary = Finance::where('tanggal', 'like', "{$currentMonth}%")
            ->selectRaw('tipe, sum(nominal) as total')
            ->groupBy('tipe')
            ->pluck('total', 'tipe'); // Output: ['in' => 5000000, 'out' => 1200000]

        return response()->json($summary);
    }
}
