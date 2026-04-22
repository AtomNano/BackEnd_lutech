<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes, \App\Traits\BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'nama_barang',
        'stok',
    ];
}
