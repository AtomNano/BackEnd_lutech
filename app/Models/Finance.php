<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Finance extends Model
{
    use SoftDeletes, BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'user_id',
        'finance_account_id',
        'type',
        'category',
        'amount',
        'status',
        'source',
        'ai_metadata',
        'attachment_path',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'ai_metadata' => 'array',
    ];

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function financeAccount(): BelongsTo
    {
        return $this->belongsTo(FinanceAccount::class);
    }
}
