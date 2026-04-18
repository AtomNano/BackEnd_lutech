<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'type',    // image, video, instagram, youtube
        'url',     // storage path or external URL
        'caption',
    ];
}
