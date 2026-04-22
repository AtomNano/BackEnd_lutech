<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToWorkspace;

class Gallery extends Model
{
    use BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'type',    // image, video, instagram, youtube
        'url',     // storage path or external URL
        'caption',
    ];
}

