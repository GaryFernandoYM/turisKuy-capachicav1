<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LugarController extends Controller
{
    // Método para mostrar todos los lugares registrados
    public function index()
    {
        $lugares = Lugar::all();  // Obtiene todos los lugares
        return view('lugares.index', compact('lugares')); // Pasa los lugares a la vista 'index'
    }

    // Método para mostrar el formulario de creación de un lugar
    public function create()
    {
        return view('lugares.create'); // Muestra el formulario de creación
    }

    // Método para almacenar un nuevo lugar
    public function store(Request $request)
    {
        // Validación de los datos enviados por el formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Subir la foto si se proporciona
        $fotoPath = $this->uploadFoto($request);

        // Crear el lugar en la base de datos
        Lugar::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'latitud' => $validated['latitud'],
            'longitud' => $validated['longitud'],
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'region' => $request->region,
            'foto' => $fotoPath,
            'activo' => $request->activo ? true : false,
        ]);

        return redirect()->route('lugares.index')->with('success', 'Lugar registrado con éxito.');
    }

    // Método para actualizar el estado (habilitado/deshabilitado) del lugar
    public function updateEstado($id)
    {
        $lugar = Lugar::findOrFail($id);
        $lugar->activo = !$lugar->activo; // Cambia el estado entre habilitado y deshabilitado
        $lugar->save();

        return redirect()->route('lugares.index')->with('success', 'Estado del lugar actualizado.');
    }

    // Método para mostrar el formulario de edición de un lugar
    public function edit($id)
    {
        $lugar = Lugar::findOrFail($id);
        return view('lugares.edit', compact('lugar'));
    }

    // Método para actualizar los datos de un lugar
    public function update(Request $request, $id)
    {
        // Validar los datos enviados por el formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Encuentra el lugar a actualizar
        $lugar = Lugar::findOrFail($id);
        $lugar->fill($validated); // Llenar los campos con los datos validados

        // Si se sube una nueva foto, se guarda y se actualiza el campo
        $lugar->foto = $this->uploadFoto($request, $lugar->foto);

        // Actualiza el estado activo
        $lugar->activo = $request->activo ? true : false;
        $lugar->save();

        return redirect()->route('lugares.index')->with('success', 'Lugar actualizado con éxito.');
    }

    /**
     * Subir la foto del lugar, si se proporciona.
     *
     * @param Request $request
     * @param string|null $oldFoto
     * @return string|null
     */
    private function uploadFoto(Request $request, $oldFoto = null)
    {
        if ($request->hasFile('foto')) {
            // Si hay una foto vieja, eliminarla
            if ($oldFoto) {
                Storage::delete('public/' . $oldFoto);
            }

            // Guardar la nueva foto
            return $request->file('foto')->store('lugares', 'public');
        }

        return $oldFoto; // Si no hay foto, devolver la foto anterior
    }
}
