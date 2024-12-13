<?php

namespace App\Http\Controllers;

use App\Models\TipoSeguro;
use App\Models\SubTipoSeguro;
use Illuminate\Http\Request;

class SegurosController extends Controller
{
    // Mostrar todos los tipos de seguro y sus subtipos
    public function index()
    {
        $seguros = TipoSeguro::with('sub_tipo_seguros')->get(); // Cargar los subtipos junto con los tipos de seguro
        return view('seguros.index', compact('seguros'));
    }

    // Mostrar el formulario para crear un nuevo tipo de seguro
    public function create()
    {
        return view('seguros.create');
    }

    // Guardar un nuevo tipo de seguro y sus subtipos
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'subtipos' => 'nullable|array',
            'subtipos.*' => 'required|string|max:255|distinct', // Validar que los subtipos sean únicos
        ]);

        // Crear el tipo de seguro
        $tipoSeguro = TipoSeguro::create([
            'nombre' => $request->nombre,
        ]);

        // Crear los subtipos si se proporcionaron
        if ($request->has('subtipos') && is_array($request->subtipos)) {
            foreach ($request->subtipos as $subtipoNombre) {
                SubTipoSeguro::firstOrCreate(
                    ['nombre' => $subtipoNombre], // Buscar el subtipo por nombre
                    ['tipo_seguro_id' => $tipoSeguro->id] // Asociar el subtipo al tipo de seguro
                );
            }
        }

        return redirect()->route('seguros.index')->with('success', 'Tipo de seguro creado con éxito.');
    }

    // Mostrar el formulario para editar un tipo de seguro y sus subtipos
    public function edit($id)
    {
        $tipoSeguro = TipoSeguro::with('sub_tipo_seguros')->findOrFail($id); // Obtener el tipo de seguro con sus subtipos
        return view('seguros.edit', compact('tipoSeguro'));
    }

    // Actualizar el tipo de seguro y sus subtipos
    public function update(Request $request, $id)
    {
        // Validar los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'subtipos' => 'nullable|array',
            'subtipos.*' => 'required|string|max:255|distinct', // Validar que los subtipos sean únicos
        ]);

        // Obtener el tipo de seguro
        $tipoSeguro = TipoSeguro::findOrFail($id);
        $tipoSeguro->update([
            'nombre' => $request->nombre,
        ]);

        // Actualizar los subtipos si se proporcionaron
        if ($request->has('subtipos') && is_array($request->subtipos)) {
            // Eliminar los subtipos existentes antes de añadir los nuevos
            $tipoSeguro->sub_tipo_seguros()->delete();

            foreach ($request->subtipos as $subtipoNombre) {
                SubTipoSeguro::firstOrCreate(
                    ['nombre' => $subtipoNombre], // Buscar el subtipo por nombre
                    ['tipo_seguro_id' => $tipoSeguro->id] // Asociar el subtipo al tipo de seguro
                );
            }
        }

        return redirect()->route('seguros.index')->with('success', 'Tipo de seguro actualizado con éxito.');
    }

    // Eliminar un tipo de seguro y sus subtipos
    public function destroy($id)
    {
        $tipoSeguro = TipoSeguro::findOrFail($id);

        // Eliminar los subtipos asociados
        $tipoSeguro->sub_tipo_seguros()->delete();

        // Eliminar el tipo de seguro
        $tipoSeguro->delete();

        return redirect()->route('seguros.index')->with('success', 'Tipo de seguro eliminado con éxito.');
    }
}
