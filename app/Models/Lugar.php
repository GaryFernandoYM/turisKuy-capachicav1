<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    protected $table = 'lugares'; // 👈 nombre correcto de la tabla

    protected $fillable = [
        'nombre',
        'descripcion',
        'latitud',
        'longitud',
        'radio_metros',
        'direccion',
        'ciudad',
        'region',
        'foto',
        'activo',
    ];
    public function ubicaciones()
    {
        return $this->hasMany(\App\Models\Ubicacion::class);
    }
}
