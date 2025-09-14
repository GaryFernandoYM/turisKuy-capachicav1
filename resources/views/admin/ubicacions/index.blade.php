<x-layouts.app :title="__('Ubicaciones')">
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Ubicaciones') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white dark:bg-zinc-800 shadow-xl sm:rounded-lg p-6">

                <div class="mb-4">
                    <p class="text-zinc-800 dark:text-zinc-100 text-sm">
                        Lista de ubicaciones registradas por los visitantes. Incluye latitud, longitud y dirección estimada.
                    </p>
                </div>

                <!-- Tabla en escritorio -->
                <div class="hidden sm:block">
                    <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                        <table class="min-w-full text-sm text-left text-zinc-700 dark:text-zinc-100">
                            <thead class="bg-zinc-100 dark:bg-zinc-700 uppercase text-xs font-semibold tracking-wider">
                                <tr>
                                    <th class="px-6 py-3">#</th> <!-- Modificado para mostrar el contador -->
                                    <th class="px-6 py-3">Nombre</th>
                                    <th class="px-6 py-3">Celular</th>
                                    <th class="px-6 py-3">Latitud</th>
                                    <th class="px-6 py-3">Longitud</th>
                                    <th class="px-6 py-3">Dirección</th>
                                    <th class="px-6 py-3">Fecha</th>
                                    <th class="px-6 py-3">Mapa</th>
                                </tr>
                            </thead>
                            <tbody id="ubicaciones-body" class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @forelse ($ubicaciones as $index => $ubicacion) <!-- Aquí se agrega el índice -->
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td> <!-- Contador secuencial -->
                                        <td class="px-6 py-4">{{ $ubicacion->nombre }}</td>
                                        <td class="px-6 py-4">{{ $ubicacion->celular }}</td>
                                        <td class="px-6 py-4">{{ $ubicacion->latitud }}</td>
                                        <td class="px-6 py-4">{{ $ubicacion->longitud }}</td>
                                        <td class="px-6 py-4">{{ $ubicacion->direccion }}</td>
                                        <td class="px-6 py-4">{{ $ubicacion->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <a href="https://maps.google.com/?q={{ $ubicacion->latitud }},{{ $ubicacion->longitud }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">Ver</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                            No hay ubicaciones registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $ubicaciones->links() }} <!-- Agregar links de paginación de Tailwind -->
                    </div>
                </div>

                <!-- Cards en móvil -->
                <div class="sm:hidden grid grid-cols-1 gap-4 mt-6" id="ubicaciones-cards">
                    @forelse ($ubicaciones as $ubicacion)
                        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-5">
                            <h3 class="text-lg font-semibold text-zinc-800 dark:text-white">{{ $ubicacion->nombre }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">{{ $ubicacion->celular }}</p>
                            <div class="text-sm text-zinc-700 dark:text-zinc-300 space-y-1">
                                <p><span class="font-medium">Latitud:</span> {{ $ubicacion->latitud }}</p>
                                <p><span class="font-medium">Longitud:</span> {{ $ubicacion->longitud }}</p>
                                <p><span class="font-medium">Dirección:</span> {{ $ubicacion->direccion }}</p>
                                <p><span class="font-medium">Fecha:</span> {{ $ubicacion->created_at->format('d/m/Y H:i') }}</p>
                                <p><a href="https://maps.google.com/?q={{ $ubicacion->latitud }},{{ $ubicacion->longitud }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">Ver en mapa</a></p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-zinc-500 dark:text-zinc-400 col-span-full">
                            No hay ubicaciones registradas.
                        </div>
                    @endforelse
                </div>

                <!-- Paginación en móvil -->
                <div class="sm:hidden mt-6">
                    {{ $ubicaciones->links() }} <!-- Paginación en versión móvil también -->
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>

@push('scripts')
<script>
function actualizarTablaUbicaciones() {
  fetch('/api/ubicaciones')
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById('ubicaciones-body');
      const container = document.getElementById('ubicaciones-cards');

      if (tbody) tbody.innerHTML = '';
      if (container) container.innerHTML = '';

      data.forEach((u, index) => { // Añadimos el índice aquí para el contador
        const fila = `
          <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
            <td class="px-6 py-4">${index + 1}</td> <!-- Mostramos el contador -->
            <td class="px-6 py-4">${u.nombre}</td>
            <td class="px-6 py-4">${u.celular}</td>
            <td class="px-6 py-4">${u.latitud}</td>
            <td class="px-6 py-4">${u.longitud}</td>
            <td class="px-6 py-4">${u.direccion}</td>
            <td class="px-6 py-4">${new Date(u.created_at).toLocaleString('es-PE')}</td>
            <td class="px-6 py-4">
              <a href="https://maps.google.com/?q=${u.latitud},${u.longitud}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">Ver</a>
            </td>
          </tr>`;

        const card = `
          <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-5">
            <h3 class="text-lg font-semibold text-zinc-800 dark:text-white">${u.nombre}</h3>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">${u.celular}</p>
            <div class="text-sm text-zinc-700 dark:text-zinc-300 space-y-1">
              <p><span class="font-medium">Latitud:</span> ${u.latitud}</p>
              <p><span class="font-medium">Longitud:</span> ${u.longitud}</p>
              <p><span class="font-medium">Dirección:</span> ${u.direccion}</p>
              <p><span class="font-medium">Fecha:</span> ${new Date(u.created_at).toLocaleString('es-PE')}</p>
              <p><a href="https://maps.google.com/?q=${u.latitud},${u.longitud}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">Ver en mapa</a></p>
            </div>
          </div>`;

        if (tbody) tbody.insertAdjacentHTML('beforeend', fila);
        if (container) container.insertAdjacentHTML('beforeend', card);
      });
    })
    .catch(err => console.error('Error al cargar ubicaciones:', err));
}

document.addEventListener('DOMContentLoaded', () => {
  actualizarTablaUbicaciones();
  setInterval(actualizarTablaUbicaciones, 10000);
});
</script>
@endpush
