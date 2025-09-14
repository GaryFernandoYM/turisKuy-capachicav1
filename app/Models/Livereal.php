<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livereal extends Model
{
    //
    protected $table = 'livereal';

    protected $fillable = [
        'visitor_id',
        'latitud',
        'longitud',
        'timestamp'
    ];
}
