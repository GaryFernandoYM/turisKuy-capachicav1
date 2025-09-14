<x-layouts.app :title="__('Dashboard')">
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 flex-wrap">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
            📄 Generar Reporte de Ubicaciones
        </h2>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
            {{-- Tipo de reporte --}}
            <select id="tipo-reporte"
                    class="w-full sm:w-auto rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm px-4 py-2">
                <option value="" disabled selected>Tipo</option>
                <option value="hoy">📅 Hoy</option>
                <option value="mes">🗓️ Mes</option>
            </select>

            {{-- Formato --}}
            <select id="formato-reporte"
                    class="w-full sm:w-auto rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm px-4 py-2">
                <option value="" disabled selected>Formato</option>
                <option value="pdf">PDF</option>
                <option value="csv">CSV</option>
            </select>

            {{-- Botón --}}
            <button onclick="descargarReporte()"
                    class="w-full sm:w-auto px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition">
                🔽 Descargar
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="text-red-600 mt-4">
            @foreach ($errors->all() as $error)
                <p>⚠️ {{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>






<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mt-6">

    {{-- Componente estadístico reutilizable --}}
    @php
        $stats = [
            ['label' => 'Total Visitas', 'valor' => $totalVisitas, 'color' => 'from-blue-500 to-blue-700', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-3-3h-4v-4a3 3 0 00-3-3H5a3 3 0 00-3 3v4h4a3 3 0 013 3v2h5z"/>' ],
            ['label' => 'Visitantes Únicos', 'valor' => $totalVisitantesUnicos, 'color' => 'from-green-500 to-green-700', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1117.804 5.12M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>' ],
            ['label' => 'Lugares', 'valor' => $lugares->count(), 'color' => 'from-yellow-500 to-yellow-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 12l4.243-4.243M12 20a8 8 0 100-16 8 8 0 000 16z"/>' ],
            ['label' => 'Últimas Visitas', 'valor' => $ultimasVisitas->count(), 'color' => 'from-pink-500 to-pink-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z"/>' ],
            ['label' => 'Visitas este Mes', 'valor' => $visitasLugarMes, 'color' => 'from-purple-500 to-purple-700', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z"/>' ],
            ['label' => 'Visitantes este Mes', 'valor' => $visitantesUnicosLugarMes ?? 0, 'color' => 'from-indigo-500 to-indigo-700', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>' ],
        ];
    @endphp

    @foreach ($stats as $item)
    <div class="flex items-center p-5 bg-gradient-to-r {{ $item['color'] }} text-white rounded-xl shadow-lg h-full">
        <div class="flex-shrink-0">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                {!! $item['icon'] !!}
            </svg>
        </div>
        <div class="ml-4">
            <h3 class="text-sm uppercase font-semibold">{{ $item['label'] }}</h3>
            <p class="text-3xl font-bold">{{ $item['valor'] }}</p>
        </div>
    </div>
    @endforeach
</div>


{{-- Gráficos --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">

    {{-- Gráfico de Barras --}}
    <div class="w-full h-80 bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-4">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Top 5 Lugares Más Visitados</h2>
        <div id="chartBar" class="w-full h-full"></div>
    </div>

    {{-- Gráfico de Pastel --}}
    <div class="w-full h-80 bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-4">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Distribución de Visitas</h2>
        <div id="chartPie" class="w-full h-full"></div>
    </div>

</div>

{{-- Mapa de Calor --}}
<div class="w-full bg-gradient-to-r from-blue-50 to-white dark:from-gray-700 dark:to-gray-800 border border-blue-100 dark:border-gray-600 rounded-3xl shadow-2xl p-6 mt-8">
    <div class="flex items-center mb-4 space-x-3">
        <div class="p-3 bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-white rounded-full">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 11c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zm0 8c-2.5 0-4.5-2-4.5-4.5S9.5 10 12 10s4.5 2 4.5 4.5S14.5 19 12 19z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-blue-800 dark:text-white">Mapa de Calor de Visitas</h2>
            <p class="text-xs text-blue-500 dark:text-gray-300">Concentración geográfica de registros</p>
        </div>
    </div>
    <div id="mapaCalor" class="min-h-[350px] h-full w-full rounded-xl"></div>
</div>





    {{-- Librerías y Gráficos --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
  document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        const darkMode = document.documentElement.classList.contains('dark');

        const lugares = {!! json_encode($lugares) !!} || [];
        const labels = lugares.map(l => l.nombre);
        const datos = lugares.map(l => l.ubicaciones_count);

        const visitasPorDia = {!! json_encode($visitasPorDia) !!} || [];
        const fechas = visitasPorDia.map(v => v.fecha);
        const totales = visitasPorDia.map(v => v.total);

        if (labels.length === 0 || datos.length === 0) return;

        new ApexCharts(document.querySelector("#chartBar"), {
            chart: {
                type: 'bar',
                height: 300,
                background: 'transparent',
                toolbar: { show: false },
                theme: { mode: darkMode ? 'dark' : 'light' }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: false,
                    columnWidth: '55%'
                }
            },
            dataLabels: { enabled: false },
            series: [{ name: 'Visitas', data: datos }],
            xaxis: {
                categories: labels,
                labels: {
                    style: {
                        colors: darkMode ? '#f3f4f6' : '#374151',
                        fontSize: '12px'
                    }
                },
                axisBorder: {
                    color: darkMode ? '#4b5563' : '#d1d5db'
                },
                axisTicks: {
                    color: darkMode ? '#4b5563' : '#d1d5db'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: darkMode ? '#f3f4f6' : '#374151',
                        fontSize: '12px'
                    }
                }
            },
            colors: ['#3B82F6'],
            tooltip: {
                theme: darkMode ? 'dark' : 'light',
                y: { formatter: val => val + " visitas" }
            },
            grid: {
                borderColor: darkMode ? '#4b5563' : '#e5e7eb'
            }
        }).render();

        // Gráfico de Pastel
        new ApexCharts(document.querySelector("#chartPie"), {
            chart: {
                type: 'donut',
                height: 300,
                toolbar: { show: false },
                background: 'transparent',
                theme: { mode: darkMode ? 'dark' : 'light' }
            },
            series: datos,
            labels: labels,
            colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
            legend: {
                position: 'bottom',
                labels: {
                    colors: darkMode ? '#f3f4f6' : '#374151'
                }
            },
            tooltip: {
                theme: darkMode ? 'dark' : 'light'
            }
        }).render();

    }, 200); // Espera leve para asegurar que Blade ya cargó variables
});


 document.addEventListener('DOMContentLoaded', function () {
        const puntosCalor = {!! json_encode($puntosCalor) !!} ?? [];

        const mapa = L.map('mapaCalor').setView([-15.5, -70.0], 13); // Cambia coordenadas a tu zona
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(mapa);

        // Heat Layer
        if (puntosCalor.length > 0) {
        const heatPoints = puntosCalor
            .filter(p => p.latitud && p.longitud)
             .map(p => [parseFloat(p.latitud), parseFloat(p.longitud), 1]);
            L.heatLayer(heatPoints, { radius: 25 }).addTo(mapa);

            // Markers con popups
            puntosCalor.forEach(p => {
                const marker = L.circleMarker([p.latitud, p.longitud], {
                    radius: 6,
                    fillColor: '#2563eb',
                    color: '#fff',
                    weight: 1,
                    opacity: 1,
                    fillOpacity: 0.9
                }).addTo(mapa);

                let popupText = `<div style='font-size:14px'><b>Visitante:</b> ${p.nombre}<br>`;
                popupText += `<b>Celular:</b> ${p.celular}<br>`;
                if (p.lugar && p.lugar.nombre) {
                    popupText += `<b>Lugar:</b> ${p.lugar.nombre}</div>`;
                } else {
                    popupText += `<b>Lugar:</b> No asignado</div>`;
                }

                marker.bindPopup(popupText);
            });
        }
    });

    </script>
<script>
function descargarReporte() {
    const tipo = document.getElementById('tipo-reporte').value;
    const formato = document.getElementById('formato-reporte').value;

    if (!tipo || !formato) {
        alert("⚠️ Debes seleccionar el tipo y el formato del reporte.");
        return;
    }

    const ruta = `/reporte-${tipo}?formato=${formato}`;
    window.open(ruta, '_blank');
}
</script>



</x-layouts.app>
