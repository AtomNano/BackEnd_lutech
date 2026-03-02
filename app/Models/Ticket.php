<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'user_id',
        'subject',
        'description',
        'jenis_device',
        'merk_device',
        'estimasi',
        'biaya_final',
        'status',
        'priority',
    ];

    protected $casts = [
        'estimasi' => 'integer',
        'biaya_final' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
