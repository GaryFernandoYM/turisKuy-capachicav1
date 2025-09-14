<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use Illuminate\Http\Request;

class VisitaController extends Controller
{
    public function visitasPorLugar()
    {
        // Obtener lugares con visitas y conteo de visitas, con paginación
        $lugares = Lugar::withCount('ubicaciones')->paginate(10); // Paginación para los lugares

        // Obtener las visitas de cada lugar con paginación
        foreach ($lugares as $lugar) {
            $lugar->ubicaciones = $lugar->ubicaciones()->latest()->paginate(10); // Paginación para visitas
        }

        return view('visitas.index', compact('lugares'));
    }
}
