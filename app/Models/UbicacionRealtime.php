<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionRealtime extends Model
{
    use HasFactory;

    // ✅ ESTA LÍNEA ES LA CLAVE
    protected $table = 'ubicaciones_realtime';

    protected $fillable = [
        'visitor_id',
        'nombre',
        'celular',
        'latitud',
        'longitud',
        'direccion',
    ];
}
