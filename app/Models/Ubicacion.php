<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';

    protected $fillable = [
        'visitor_id',
        'lugar_id',
        'nombre',
        'celular',
        'latitud',
        'longitud',
        'direccion',
    ];
    public function lugar()
    {
        return $this->belongsTo(\App\Models\Lugar::class, 'lugar_id');
    }
}
