<x-layouts.app :title="__('Visitas por Lugar')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h2 class="text-2xl font-bold text-zinc-800 dark:text-white mb-6">👥 Visitas por Lugar</h2>

        @if($lugares->isEmpty())
            <div class="text-center text-zinc-500 dark:text-zinc-400">
                No hay lugares registrados aún.
            </div>
        @else
            <!-- Tabla para escritorio -->
            <div class="hidden sm:block">
                <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <table class="min-w-full text-sm text-left text-zinc-700 dark:text-zinc-100">
                        <thead class="bg-zinc-100 dark:bg-zinc-700 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Lugar</th>
                                <th class="px-6 py-3">Dirección</th>
                                <th class="px-6 py-3 text-center">Visitas</th>
                                <th class="px-6 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach ($lugares as $lugar)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                                    <td class="px-6 py-4 text-sm text-zinc-900 dark:text-white">{{ $lugar->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-300">{{ $lugar->direccion }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-blue-600 dark:text-blue-400">{{ $lugar->ubicaciones_count }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="toggleVisitors('visitors-{{ $lugar->id }}')" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition-all">
                                            Ver visitantes
                                        </button>
                                    </td>
                                </tr>
                                <tr id="visitors-{{ $lugar->id }}" style="display: none;">
                                    <td colspan="4" class="px-6 py-4 bg-zinc-50 dark:bg-zinc-900">
                                        <h4 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Visitantes:</h4>
                                        @if($lugar->ubicaciones->isEmpty())
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">No hay visitantes registrados.</p>
                                        @else
                                            <ul class="list-disc list-inside text-sm text-zinc-700 dark:text-zinc-200 space-y-1">
                                                @foreach($lugar->ubicaciones as $visita)
                                                    <li class="flex items-center">
                                                        <span class="mr-2 text-sm">{{ $visita->nombre }}</span>
                                                        <span class="text-xs text-zinc-500">{{ $visita->celular }}</span>
                                                        <span class="ml-2 text-xs text-zinc-500">{{ $visita->created_at->format('d/m/Y H:i') }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <!-- Paginación de visitas -->
                                            <div class="mt-4">
                                                {{ $lugar->ubicaciones->links() }} <!-- Paginación moderna de Tailwind -->
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación de lugares -->
                <div class="mt-6">
                    {{ $lugares->links() }} <!-- Paginación moderna de Tailwind -->
                </div>
            </div>

            <!-- Cards para móvil -->
            <div class="sm:hidden grid grid-cols-1 gap-4 mt-6">
                @foreach ($lugares as $lugar)
                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-5">
                        <h3 class="text-lg font-semibold text-zinc-800 dark:text-white">{{ $lugar->nombre }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-300">{{ $lugar->direccion }}</p>
                        <p class="text-sm font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $lugar->ubicaciones_count }} visitas</p>

                        @if(!$lugar->ubicaciones->isEmpty())
                            <details class="mt-3">
                                <summary class="text-sm cursor-pointer text-indigo-600 dark:text-indigo-400">Ver visitantes</summary>
                                <ul class="list-disc list-inside mt-2 space-y-1 text-sm text-zinc-700 dark:text-zinc-200">
                                    @foreach($lugar->ubicaciones as $visita)
                                        <li class="flex items-center">
                                            <span class="mr-2 text-sm">{{ $visita->nombre }}</span>
                                            <span class="text-xs text-zinc-500">{{ $visita->celular }}</span>
                                            <span class="ml-2 text-xs text-zinc-500">{{ $visita->created_at->format('d/m/Y H:i') }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <!-- Paginación de visitas -->
                                <div class="mt-4">
                                    {{ $lugar->ubicaciones->links() }} <!-- Paginación moderna de Tailwind -->
                                </div>
                            </details>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function toggleVisitors(id) {
            const row = document.getElementById(id);
            if (row) {
                row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
            }
        }
    </script>
</x-layouts.app>
