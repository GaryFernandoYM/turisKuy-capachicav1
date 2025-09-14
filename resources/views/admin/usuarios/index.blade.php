<x-layouts.app :title="__('Usuarios')">
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- ✅ Tabla en escritorio -->
            <div class="hidden sm:block">
                <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700 text-sm text-left text-zinc-700 dark:text-zinc-100">
                        <thead class="bg-zinc-100 dark:bg-zinc-700 text-xs font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Correo</th>
                                <th class="px-6 py-3">Registrado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse ($usuarios as $usuario)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                                    <td class="px-6 py-4">{{ $usuario->id }}</td>
                                    <td class="px-6 py-4">{{ $usuario->name }}</td>
                                    <td class="px-6 py-4">{{ $usuario->email }}</td>
                                    <td class="px-6 py-4">{{ $usuario->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ✅ Cards en móvil -->
            <div class="sm:hidden grid grid-cols-1 gap-4 mt-6">
                @forelse ($usuarios as $usuario)
                    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-5">
                        <div class="mb-3">
                            <h3 class="text-lg font-semibold text-zinc-800 dark:text-white">{{ $usuario->name }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $usuario->email }}</p>
                        </div>
                        <div class="text-sm text-zinc-700 dark:text-zinc-300 space-y-1">
                            <p><span class="font-medium">ID:</span> {{ $usuario->id }}</p>
                            <p><span class="font-medium">Registrado:</span> {{ $usuario->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-zinc-500 dark:text-zinc-400">
                        No hay usuarios registrados.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-layouts.app>
