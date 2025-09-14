<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Lugar;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del administrador.
     */
    public function index()
    {
        $user = Auth::user();

        // Seguridad: solo admins acceden
        if (!$user || !$user->is_admin) {
            Auth::logout();
            return redirect('/login')->with('error', 'Acceso no autorizado.');
        }

        // Obtener lugar seleccionado (si lo hay)
        $lugarId = request()->input('lugar_id');
        $lugarId = $lugarId === "" ? null : $lugarId; // 👈 permite opción "Todos"

        // KPIs globales
        $totalVisitas = Ubicacion::whereHas('lugar')->count();
        $totalVisitantesUnicos = Ubicacion::whereHas('lugar')
            ->distinct('visitor_id')
            ->count('visitor_id');

        // Lista de lugares (para selector)
        $lugares = Lugar::withCount('ubicaciones')
            ->orderByDesc('ubicaciones_count')
            ->limit(5)
            ->get(['id', 'nombre']);

        // Últimas visitas
        $ultimasVisitas = Ubicacion::with('lugar')
            ->whereHas('lugar')
            ->latest()
            ->limit(10)
            ->get();

        // Mapa de calor
        $puntosCalor = Ubicacion::with('lugar')
            ->whereHas('lugar')
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->select('visitor_id', 'nombre', 'celular', 'latitud', 'longitud', 'lugar_id')
            ->get();

        // Gráfico de visitas diarias globales
        $visitasPorDia = Ubicacion::whereHas('lugar')
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Inicializar KPIs de lugar
        $visitasLugarMes = 0;
        $visitantesUnicosLugarMes = 0;

        // Si se seleccionó un lugar → calcular KPIs del lugar
        if ($lugarId) {
            $mes = now()->month;
            $anio = now()->year;
            $inicioMes = Carbon::createFromDate($anio, $mes, 1)->startOfDay();
            $finMes = Carbon::createFromDate($anio, $mes, 1)->endOfMonth()->endOfDay();

            $visitasLugarMes = Ubicacion::where('lugar_id', $lugarId)
                ->whereBetween('created_at', [$inicioMes, $finMes])
                ->count();

            $visitantesUnicosLugarMes = Ubicacion::where('lugar_id', $lugarId)
                ->whereBetween('created_at', [$inicioMes, $finMes])
                ->distinct('visitor_id')
                ->count('visitor_id');
        }

        // Devolver todo a la vista
        return view('dashboard', compact(
            'totalVisitas',
            'totalVisitantesUnicos',
            'lugares',
            'ultimasVisitas',
            'puntosCalor',
            'visitasPorDia',
            'lugarId',
            'visitasLugarMes',
            'visitantesUnicosLugarMes'
        ));
    }
}
