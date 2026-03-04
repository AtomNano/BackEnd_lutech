<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'nama',
        'whatsapp',
        'email',
        'address',
        'notes',
        'points',
        'tier',
        'total_spent',
        'service_count',
        'last_service_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'service_count' => 'integer',
        'total_spent' => 'decimal:2',
        'last_service_at' => 'datetime',
    ];

    /** Hitung tier berdasarkan service_count dan total_spent. */
    public function recalculateTier(): string
    {
        $count = $this->service_count;
        $spent = (float) $this->total_spent;

        if ($count >= 15 || $spent >= 2_000_000)
            return 'platinum';
        if ($count >= 7 || $spent >= 750_000)
            return 'gold';
        if ($count >= 3 || $spent >= 300_000)
            return 'silver';
        return 'regular';
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
