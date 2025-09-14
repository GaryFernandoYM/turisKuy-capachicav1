<x-layouts.app :title="__('Editar lugar')">
    <div class="max-w-3xl mx-auto px-6 py-8 bg-white dark:bg-zinc-900 shadow-2xl rounded-2xl">
        <!-- Alerta de éxito -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-xl mb-6 flex items-center space-x-3">
                <i class="bi bi-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <h2 class="text-3xl font-bold text-zinc-800 dark:text-white mb-6 text-center">✏️ Editar lugar</h2>

        <form action="{{ route('lugares.update', $lugar->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT') <!-- Método PUT para actualizar -->

            <!-- Buscador con autocompletado -->
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Buscar lugar</label>
                <input type="text" id="nominatim-search" placeholder="Ej: Plaza de Armas de Puno"
                    class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none p-3"
                    autocomplete="off" value="{{ old('nombre', $lugar->nombre) }}">

                <ul id="suggestions"
                    class="absolute z-10 bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-white border border-zinc-300 dark:border-zinc-700 rounded-xl mt-1 shadow-lg max-h-48 overflow-auto hidden">
                </ul>

                <span id="loading" class="text-sm text-blue-500 hidden">Buscando ubicación...</span>
            </div>

            <!-- Mapa -->
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Selecciona en el mapa</label>
                <div id="map" class="w-full h-64 rounded-xl border border-zinc-300 dark:border-zinc-700 shadow"></div>
                <p id="resolved-address" class="mt-2 text-sm text-green-500"></p>
            </div>

            <!-- Coordenadas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Latitud</label>
                    <input type="text" id="latitud" name="latitud" required readonly
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-white p-3" value="{{ old('latitud', $lugar->latitud) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Longitud</label>
                    <input type="text" id="longitud" name="longitud" required readonly
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-white p-3" value="{{ old('longitud', $lugar->longitud) }}">
                </div>
            </div>

            <!-- Datos -->
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Nombre del lugar</label>
                <input type="text" name="nombre" id="nombre" required
                    class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-white p-3" value="{{ old('nombre', $lugar->nombre) }}">
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                    class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-white p-3">{{ old('descripcion', $lugar->descripcion) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Dirección</label>
                    <input type="text" name="direccion" id="direccion"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-white p-3" value="{{ old('direccion', $lugar->direccion) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-white p-3" value="{{ old('ciudad', $lugar->ciudad) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Región</label>
                    <input type="text" name="region" id="region"
                        class="w-full rounded-xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-800 dark:text-white p-3" value="{{ old('region', $lugar->region) }}">
                </div>
            </div>

            <!-- Foto -->
            <div class="w-full">
                <label for="foto" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-2">
                    📷 Foto
                </label>
                <input
                    type="file"
                    name="foto"
                    id="foto"
                    accept="image/*"
                    class="block w-full cursor-pointer rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-800 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition"
                >
            </div>

            <!-- Activo -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" name="activo"
                    class="form-checkbox text-blue-600 dark:bg-zinc-800 dark:border-zinc-600 rounded focus:ring-blue-500" {{ old('activo', $lugar->activo) ? 'checked' : '' }}>
                <span class="text-sm text-zinc-700 dark:text-zinc-300">Lugar activo</span>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('lugares.index') }}"
                    class="px-4 py-2 rounded-xl border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-white bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition">Cancelar</a>
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition shadow-md">Actualizar lugar</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const map = L.map('map').setView([{{ old('latitud', $lugar->latitud) ?? -15.5 }}, {{ old('longitud', $lugar->longitud) ?? -70.0 }}], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let marker = L.marker([{{ old('latitud', $lugar->latitud) ?? -15.5 }}, {{ old('longitud', $lugar->longitud) ?? -70.0 }}]).addTo(map);

            const input = document.getElementById('nominatim-search');
            const suggestions = document.getElementById('suggestions');
            const loading = document.getElementById('loading');
            const direccion = document.getElementById('direccion');
            const ciudad = document.getElementById('ciudad');
            const region = document.getElementById('region');
            const latitud = document.getElementById('latitud');
            const longitud = document.getElementById('longitud');
            const resolvedAddress = document.getElementById('resolved-address');

            const fillAddressFields = (data, lat, lon) => {
                direccion.value = data.display_name ?? '';
                ciudad.value = data.address.city ?? data.address.town ?? data.address.village ?? '';
                region.value = data.address.state ?? '';
                latitud.value = lat;
                longitud.value = lon;
                resolvedAddress.innerText = data.display_name ?? '';
            };

            // Tu código para geolocalización y autocompletado sigue aquí...

        });
    </script>
    @endpush
</x-layouts.app>
