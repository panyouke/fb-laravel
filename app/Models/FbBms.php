<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FbBms extends Model
{
    // app/Models/FbBm.php
    protected $casts = [
        'manager_token' => 'encrypted',
        'app_secret' => 'encrypted',
    ];

    protected $guarded = [];
}
