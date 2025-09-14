<?php

namespace App\Http\Controllers;

use App\Models\Livereal;
use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    public function guardar(Request $request)
    {
        Log::info('📥 /api/ubicacion recibido', [
            'ip' => request()->ip(),
            'payload' => $request->all()
        ]);

        \App\Models\Livereal::updateOrInsert(
            ['visitor_id' => $request->visitor_id],
            [
                'latitud' => $request->lat,
                'longitud' => $request->lon,
                'timestamp' => now(),
                'updated_at' => now()
            ]
        );

        return response()->json(['status' => 'ok']);
    }


    public function verUbicaciones()
    {
        $lugares = \App\Models\Lugar::select('nombre', 'latitud', 'longitud', 'direccion')->get();
        return view('admin.mapa-visitantes', compact('lugares'));
    }


    public function ubicacionesJson()
    {
        $umbralSegundos = 60;

        // Elimina visitantes inactivos automáticamente
        Livereal::where('updated_at', '<', now()->subSeconds($umbralSegundos))->delete();

        return response()->json(
            Livereal::select('visitor_id', 'latitud', 'longitud', 'updated_at')->get()
        );
    }
    public function visitasPorLugar()
    {
        // Obtener lugares con sus coordenadas (latitud, longitud)
        $lugares = Lugar::all(); // Obtén todos los lugares

        return view('visitas.index', compact('lugares'));
    }
}
