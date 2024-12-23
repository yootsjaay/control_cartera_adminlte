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
        $seguros = TipoSeguro::with('sub_tipo_seguros')->get(); // Eager Loading para optimizar consultas
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
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'subtipos' => 'array|nullable',
            'subtipos.*.nombre' => 'required_with:subtipos|string|max:255',
        ]);

        // Crear el tipo de seguro
        $tipoSeguro = TipoSeguro::create(['nombre' => $validatedData['nombre']]);

        // Crear los subtipos, si se proporcionan
        if (!empty($validatedData['subtipos'])) {
            $this->storeSubTipos($tipoSeguro, $validatedData['subtipos']);
        }

        return redirect()->route('seguros.index')->with('success', 'Tipo de seguro creado con éxito.');
    }

    // Método privado para guardar subtipos de seguro
    private function storeSubTipos(TipoSeguro $tipoSeguro, array $subtipos)
    {
        foreach ($subtipos as $subtipo) {
            $tipoSeguro->sub_tipo_seguros()->create([
                'nombre' => $subtipo['nombre'],
            ]);
        }
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
    // Obtener el tipo de seguro
    $tipoSeguro = TipoSeguro::findOrFail($id);
    
    // Actualizar el tipo de seguro
    $tipoSeguro->nombre = $request->nombre;
    $tipoSeguro->save();
    
    // Verificar y actualizar los subtipos
    foreach ($request->subtipos as $index => $subtipoData) {
        // Comprobar si el subtipo ya existe para este tipo de seguro
        $existingSubtipo = SubTipoSeguro::where('nombre', $subtipoData['nombre'])
            ->where('tipo_seguro_id', $tipoSeguro->id)
            ->first();

        if ($existingSubtipo) {
            // Si existe, solo actualizar
            $existingSubtipo->nombre = $subtipoData['nombre'];
            $existingSubtipo->save();
        } else {
            // Si no existe, crear un nuevo subtipo
            $newSubtipo = new SubTipoSeguro();
            $newSubtipo->nombre = $subtipoData['nombre'];
            $newSubtipo->tipo_seguro_id = $tipoSeguro->id;
            $newSubtipo->save();
        }
    }

    return redirect()->route('seguros.index')->with('success', 'Seguro actualizado correctamente');
}


    // Método privado para actualizar subtipos
    private function updateSubTipos(TipoSeguro $tipoSeguro, array $subtipos)
    {
        foreach ($subtipos as $subtipo) {
            if (isset($subtipo['id'])) {
                // Actualizar subtipo existente
                SubTipoSeguro::where('id', $subtipo['id'])
                    ->where('tipo_seguro_id', $tipoSeguro->id)
                    ->update(['nombre' => $subtipo['nombre']]);
            } else {
                // Crear nuevo subtipo
                $tipoSeguro->sub_tipo_seguros()->create([
                    'nombre' => $subtipo['nombre'],
                ]);
            }
        }
    }

    // Eliminar un tipo de seguro y sus subtipos
    public function destroy($id)
    {
        $tipoSeguro = TipoSeguro::findOrFail($id);

        // Laravel eliminará los subtipos automáticamente si la relación tiene `onDelete('cascade')`
        $tipoSeguro->delete();

        return redirect()->route('seguros.index')->with('success', 'Tipo de seguro eliminado con éxito.');
    }
}
