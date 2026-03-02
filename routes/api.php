<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\WorkspaceController;
use App\Http\Controllers\Api\V1\FinanceController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\GalleryController;

// ═══════════════════════════════════════════════════════════════
//  PUBLIC ROUTES  (tanpa auth)
// ═══════════════════════════════════════════════════════════════
Route::post('/login', [AuthController::class, 'login']);

// Gallery public (landing page)
Route::get('/v1/galleries', function () {
    return \App\Models\Gallery::latest()->get();
});

// ═══════════════════════════════════════════════════════════════
//  PROTECTED ROUTES  (Sanctum Bearer Token)
//  Semua endpoint di bawah prefix /api/v1/...
// ═══════════════════════════════════════════════════════════════
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    // ── Tickets ───────────────────────────────────────────────
    Route::get('tickets', [TicketController::class, 'index']);
    Route::post('tickets', [TicketController::class, 'store']);
    Route::get('tickets/{ticket}', [TicketController::class, 'show']);
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus']);
    Route::patch('tickets/{ticket}/cost', [TicketController::class, 'updateCost']);
    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy']);


    // ── Customers ─────────────────────────────────────────────
    Route::get('customers', [CustomerController::class, 'index']);
    Route::post('customers', [CustomerController::class, 'store']);

    // ── Inventory ─────────────────────────────────────────────
    Route::patch('inventory/{inventory}/stock', [InventoryController::class, 'adjustStock']);

    // ── Gallery (auth required untuk upload/delete) ───────────
    Route::post('galleries', [GalleryController::class, 'store']);
    Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy']);

    // ── Workspace ─────────────────────────────────────────────
    Route::get('workspaces', [WorkspaceController::class, 'index']);
    Route::post('workspaces', [WorkspaceController::class, 'store']);
    Route::patch('workspaces/{workspace}/default', [WorkspaceController::class, 'setDefault']);
    Route::delete('workspaces/{workspace}', [WorkspaceController::class, 'destroy']);

    // ── Finance (nested di bawah workspace) ───────────────────
    Route::prefix('workspaces/{workspace}')->group(function () {
        // ⚠️ summary HARUS sebelum {finance} agar tidak konflik routing
        Route::get('finances/summary', [FinanceController::class, 'summary']);
        Route::get('finances', [FinanceController::class, 'index']);
        Route::post('finances', [FinanceController::class, 'store']);
        Route::put('finances/{finance}', [FinanceController::class, 'update']);
        Route::delete('finances/{finance}', [FinanceController::class, 'destroy']);
    });
});
