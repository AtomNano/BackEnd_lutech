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
use App\Http\Controllers\Api\V1\ProfileController;

// ═══════════════════════════════════════════════════════════════
//  PUBLIC ROUTES  (tanpa auth)
// ═══════════════════════════════════════════════════════════════
Route::post('/login', [AuthController::class, 'login']);

// Gallery public (landing page)
Route::get('/v1/galleries', function () {
    return \App\Models\Gallery::latest()->get();
});

// Ticket Tracking Public
Route::get('/v1/track/{query}', [TicketController::class, 'trackPublic']);

// ═══════════════════════════════════════════════════════════════
//  PROTECTED ROUTES  (Sanctum Bearer Token)
//  Semua endpoint di bawah prefix /api/v1/...
// ═══════════════════════════════════════════════════════════════
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Auth User Details (with Avatar URL)
    Route::get('/user', function (Request $request) {
        $u = $request->user();
        return array_merge($u->toArray(), [
            'avatar_url' => $u->avatar ? asset('storage/' . $u->avatar) : null,
        ]);
    });

    // Profile Settings
    Route::post('/profile', [ProfileController::class, 'update']);

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
    Route::get('customers/{customer}', [CustomerController::class, 'show']);
    Route::put('customers/{customer}', [CustomerController::class, 'update']);
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy']);
    Route::post('customers/{customer}/add-points', [CustomerController::class, 'addPoints']);

    // ── Inventory ─────────────────────────────────────────────
    Route::patch('inventory/{inventory}/stock', [InventoryController::class, 'adjustStock']);

    // ── Gallery (auth required untuk upload/delete) ───────────
    Route::post('galleries', [GalleryController::class, 'store']);
    Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy']);

    // ── Workspace ─────────────────────────────────────────────
    Route::get('workspaces', [WorkspaceController::class, 'index']);
    Route::post('workspaces', [WorkspaceController::class, 'store']);
    Route::patch('workspaces/{workspace}', [WorkspaceController::class, 'update']);
    Route::patch('workspaces/{workspace}/default', [WorkspaceController::class, 'setDefault']);
    Route::post('workspaces/{workspace}/verify-pin', [WorkspaceController::class, 'verifyPin']);
    Route::post('workspaces/{workspace}/set-pin', [WorkspaceController::class, 'setPin']);
    Route::delete('workspaces/{workspace}', [WorkspaceController::class, 'destroy']);

    // ── Finance & Categories (nested di bawah workspace) ───────────────────
    Route::prefix('workspaces/{workspace}')->group(function () {
        // Accounts (Dompet/Rekening)
        Route::get('finance-accounts', [\App\Http\Controllers\Api\V1\FinanceAccountController::class, 'index']);
        Route::post('finance-accounts', [\App\Http\Controllers\Api\V1\FinanceAccountController::class, 'store']);
        Route::patch('finance-accounts/{financeAccount}', [\App\Http\Controllers\Api\V1\FinanceAccountController::class, 'update']);
        Route::delete('finance-accounts/{financeAccount}', [\App\Http\Controllers\Api\V1\FinanceAccountController::class, 'destroy']);

        // Categories
        Route::get('finance-categories', [\App\Http\Controllers\Api\V1\FinanceCategoryController::class, 'index']);
        Route::post('finance-categories', [\App\Http\Controllers\Api\V1\FinanceCategoryController::class, 'store']);
        Route::delete('finance-categories/{category}', [\App\Http\Controllers\Api\V1\FinanceCategoryController::class, 'destroy']);

        // Finances
        // ⚠️ summary HARUS sebelum {finance} agar tidak konflik routing
        Route::get('finances/summary', [FinanceController::class, 'summary']);
        Route::get('finances', [FinanceController::class, 'index']);
        Route::post('finances', [FinanceController::class, 'store']);
        Route::put('finances/{finance}', [FinanceController::class, 'update']);
        Route::delete('finances/{finance}', [FinanceController::class, 'destroy']);

        // Financial Goals
        Route::get('financial-goals', [\App\Http\Controllers\Api\V1\FinancialGoalController::class, 'index']);
        Route::post('financial-goals', [\App\Http\Controllers\Api\V1\FinancialGoalController::class, 'store']);
        Route::put('financial-goals/{goal}', [\App\Http\Controllers\Api\V1\FinancialGoalController::class, 'update']);
        Route::delete('financial-goals/{goal}', [\App\Http\Controllers\Api\V1\FinancialGoalController::class, 'destroy']);
    });
});
