<x-layouts.app :title="__('Lugares')">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Título y botón de agregar lugar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h2 class="text-2xl font-semibold text-blue-900 dark:text-white">
                📍 Lugares registrados
            </h2>

            <a href="{{ route('lugares.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg shadow-md transition duration-200 ease-in-out">
                ➕ Agregar nuevo lugar
            </a>
        </div>

        <!-- Mensaje si no hay lugares registrados -->
        @if($lugares->isEmpty())
            <div class="text-center text-zinc-600 dark:text-zinc-400">
                No hay lugares registrados aún.
            </div>
        @else
            <!-- Grid de lugares -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($lugares as $lugar)
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md overflow-hidden border border-zinc-200 dark:border-zinc-700 hover:shadow-xl transition-shadow duration-300 relative">
                    <!-- Imagen del lugar -->
                    @if($lugar->foto)
                        <img src="{{ asset('storage/' . $lugar->foto) }}" alt="Foto del lugar" class="w-full h-48 object-cover rounded-t-xl">
                    @endif

                    <!-- Botón de edición en la parte superior derecha -->
                    <a href="{{ route('lugares.edit', $lugar->id) }}" class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-2 rounded-full hover:bg-blue-700 transition duration-200 ease-in-out">
                        ✏️
                    </a>

                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-blue-900 dark:text-white">{{ $lugar->nombre }}</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $lugar->descripcion }}</p>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                            📍 {{ $lugar->ciudad }}, {{ $lugar->region }}
                        </div>

                        <!-- Estado habilitado/deshabilitado -->
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-sm font-semibold {{ $lugar->activo ? 'text-blue-600' : 'text-gray-500' }}">
                                {{ $lugar->activo ? 'Habilitado' : 'Deshabilitado' }}
                            </span>

                            <!-- Botón de habilitar/deshabilitar -->
                            <form action="{{ route('lugares.updateEstado', $lugar->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="px-4 py-2 text-white {{ $lugar->activo ? 'bg-gray-600 hover:bg-gray-700' : 'bg-blue-600 hover:bg-blue-700' }} rounded-lg shadow-sm transition duration-300 ease-in-out">
                                    {{ $lugar->activo ? 'Deshabilitar' : 'Habilitar' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
