<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{

    public const TYPE_SELECT = [
        'default' => 'Default',
        'sabre'   => 'Sabre'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
