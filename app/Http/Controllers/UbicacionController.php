<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use App\Models\Lugar;
use App\Models\UbicacionRealtime;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Asegúrate de tener barryvdh/laravel-dompdf instalado
use Illuminate\Support\Carbon;

class UbicacionController extends Controller
{



    /**
     * Muestra listado de ubicaciones.
     */
    public function index()
    {
        $ubicaciones = Ubicacion::with('lugar')->latest()->paginate(10);

        return view('admin.ubicacions.index', compact('ubicaciones'));
    }

    /**
     * Guarda una nueva ubicación (híbrido: registra siempre, asocia lugar si está cerca).
     */
    public function store(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'direccion' => 'nullable|string|max:255',
        ]);

        $lat = (float) $request->latitud;   // 👈 FIX
        $lon = (float) $request->longitud;  // 👈 FIX

        $lugarCercano = Lugar::where('activo', 1)->get()
            ->first(function ($lugar) use ($lat, $lon) {
                return $this->calcularDistancia(
                    $lat,
                    $lon,
                    (float) $lugar->latitud,  // 👈 FIX
                    (float) $lugar->longitud  // 👈 FIX
                ) <= $lugar->radio_metros;
            });

        Ubicacion::create([
            'visitor_id' => $request->visitor_id,
            'nombre' => $request->nombre,
            'celular' => $request->celular,
            'latitud' => $lat,
            'longitud' => $lon,
            'direccion' => $request->direccion,
            'lugar_id' => optional($lugarCercano)->id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Ubicación guardada.']);
    }


    /**
     * Calcula la distancia entre dos coordenadas (metros).
     */
    private function calcularDistancia($lat1, $lon1, $lat2, $lon2): float
    {
        $radioTierra = 6371000; // metros
        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) ** 2 +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $radioTierra * $c;
    }

    /**
     * Muestra detalle de una ubicación.
     */
    public function show($id)
    {
        $ubicacion = Ubicacion::with('lugar')->findOrFail($id);
        return view('admin.ubicacions.show', compact('ubicacion'));
    }

    /**
     * Muestra formulario para editar ubicación.
     */
    public function edit($id)
    {
        $ubicacion = Ubicacion::findOrFail($id);
        return view('admin.ubicacions.edit', compact('ubicacion'));
    }

    /**
     * Actualiza una ubicación existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'visitor_id' => 'sometimes|string|max:255',
            'nombre' => 'sometimes|string|max:255',
            'celular' => 'sometimes|string|max:20',
            'latitud' => 'sometimes|numeric|between:-90,90',
            'longitud' => 'sometimes|numeric|between:-180,180',
            'direccion' => 'nullable|string|max:255',
            'lugar_id' => 'nullable|exists:lugares,id',
        ]);

        $ubicacion = Ubicacion::findOrFail($id);

        $ubicacion->update($request->only([
            'visitor_id',
            'nombre',
            'celular',
            'latitud',
            'longitud',
            'direccion',
            'lugar_id'
        ]));

        return redirect()->route('admin.ubicacions.index')
            ->with('success', 'Ubicación actualizada con éxito.');
    }

    /**
     * Elimina una ubicación.
     */
    public function destroy($id)
    {
        $ubicacion = Ubicacion::findOrFail($id);
        $ubicacion->delete();

        return redirect()->route('admin.ubicacions.index')
            ->with('success', 'Ubicación eliminada con éxito.');
    }

    /**
     * API pública para obtener últimas 50 ubicaciones.
     */
    public function api()
    {
        return response()->json(
            Ubicacion::with('lugar')
                ->latest()
                ->take(50)
                ->get()
        );
    }
    public function tiempoReal()
    {
        $haceCincoMinutos = now()->subMinutes(5);

        $ultimas = UbicacionRealtime::where('created_at', '>=', $haceCincoMinutos)
            ->select('id', 'visitor_id', 'nombre', 'celular', 'latitud', 'longitud', 'direccion', 'created_at')
            ->orderByDesc('created_at')
            ->get()
            ->unique('visitor_id')
            ->values();

        return response()->json($ultimas);
    }
    public function guardarTiempoReal(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'direccion' => 'nullable|string|max:255',
        ]);

        UbicacionRealtime::create($request->all());

        return response()->json(['status' => 'success', 'message' => 'Ubicación TIEMPO REAL guardada.']);
    }

    public function reporteHoy(Request $request)
    {
        $hoy = Carbon::today();
        $inicio = $hoy->copy()->startOfDay();
        $fin = $hoy->copy()->endOfDay();
        $periodo = $hoy->format('d/m/Y');

        $ubicaciones = Ubicacion::with('lugar')
            ->whereBetween('created_at', [$inicio, $fin])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($ubicaciones->isEmpty()) {
            return back()->withErrors(['No hay registros de visitas para hoy.']);
        }

        if ($request->query('formato') === 'csv') {
            $filename = "reporte_ubicaciones_dia_{$hoy->format('Y_m_d')}.csv";

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename={$filename}",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function () use ($ubicaciones) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Nombre', 'Celular', 'Lugar', 'Latitud', 'Longitud', 'Dirección', 'Fecha']);

                foreach ($ubicaciones as $u) {
                    fputcsv($handle, [
                        $u->nombre,
                        $u->celular,
                        $u->lugar->nombre ?? 'N/A',
                        $u->latitud,
                        $u->longitud,
                        $u->direccion,
                        $u->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        // PDF por defecto
        $pdf = Pdf::loadView('admin.ubicacions.reporte_pdf', [
            'ubicaciones' => $ubicaciones,
            'tipo' => 'dia',
            'periodo' => $periodo
        ]);

        return $pdf->download("reporte_ubicaciones_dia_{$hoy->format('Y_m_d')}.pdf");
    }

    public function reporteMes(Request $request)
    {
        $hoy = Carbon::today();
        $inicio = $hoy->copy()->startOfMonth();
        $fin = $hoy->copy()->endOfMonth();
        $periodo = ucfirst($hoy->translatedFormat('F Y'));

        $ubicaciones = Ubicacion::with('lugar')
            ->whereBetween('created_at', [$inicio, $fin])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($ubicaciones->isEmpty()) {
            return back()->withErrors(['No hay registros de visitas para el mes actual.']);
        }

        if ($request->query('formato') === 'csv') {
            $filename = "reporte_ubicaciones_mes_{$hoy->format('Y_m')}.csv";

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename={$filename}",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function () use ($ubicaciones) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Nombre', 'Celular', 'Lugar', 'Latitud', 'Longitud', 'Dirección', 'Fecha']);

                foreach ($ubicaciones as $u) {
                    fputcsv($handle, [
                        $u->nombre,
                        $u->celular,
                        $u->lugar->nombre ?? 'N/A',
                        $u->latitud,
                        $u->longitud,
                        $u->direccion,
                        $u->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        // PDF por defecto
        $pdf = Pdf::loadView('admin.ubicacions.reporte_pdf', [
            'ubicaciones' => $ubicaciones,
            'tipo' => 'mes',
            'periodo' => $periodo
        ]);

        return $pdf->download("reporte_ubicaciones_mes_{$hoy->format('Y_m')}.pdf");
    }

}
