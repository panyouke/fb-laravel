<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FbBms extends Model
{
    // app/Models/FbBm.php
    protected $casts = [
        'manager_token' => 'encrypted',
        'app_secret' => 'encrypted',
    ];

    protected $guarded = [];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
