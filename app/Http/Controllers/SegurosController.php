<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoSeguro;
class SegurosController extends Controller
{
    public function index()
    {
        $seguros = TipoSeguro::all();
        return view('seguros.index', compact('seguros'));
    }

    public function tiposSeguros($id)
{
    $compania = Compania::with('tiposSeguros')->findOrFail($id);
    return response()->json($compania->tiposSeguros);
}

    public function create()
    {
        return view('seguros.create');
    }

    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255', // Actualiza el nombre de acuerdo a tu esquema
            'descripcion' => 'nullable|string',
            'cobertura_minima' => 'nullable|numeric|min:0',
            'cobertura_maxima' => 'nullable|numeric|min:0',
            'duracion' => 'nullable|integer|min:1',
            'prima_promedio' => 'nullable|numeric|min:0',
            'riesgo_asociado' => 'nullable|in:bajo,medio,alto',
            'requisitos' => 'nullable|string',
        ]);

        // Crear un nuevo seguro con los datos validados
        TipoSeguro::create([
            'nombre' => $request->nombre, // Actualiza el nombre de acuerdo a tu esquema
            'descripcion' => $request->descripcion,
            'cobertura_minima' => $request->cobertura_minima,
            'cobertura_maxima' => $request->cobertura_maxima,
            'duracion' => $request->duracion,
            'prima_promedio' => $request->prima_promedio,
            'riesgo_asociado' => $request->riesgo_asociado,
            'requisitos' => $request->requisitos,
        ]);

        return redirect()->route('seguros.index')->with('success', 'Tipo de seguro creado con éxito.');
    }

    public function edit(TipoSeguro $seguro)
    {
        return view('seguros.edit', compact('seguro'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255', // Actualiza el nombre de acuerdo a tu esquema
            'descripcion' => 'nullable|string',
            'cobertura_minima' => 'nullable|numeric|min:0',
            'cobertura_maxima' => 'nullable|numeric|min:0',
            'duracion' => 'nullable|integer|min:1',
            'prima_promedio' => 'nullable|numeric|min:0',
            'riesgo_asociado' => 'nullable|in:bajo,medio,alto',
            'requisitos' => 'nullable|string',
        ]);

        // Buscar el seguro por ID
        $seguro = TipoSeguro::findOrFail($id);

        // Actualizar los datos del seguro
        $seguro->update([
            'nombre' => $request->nombre, // Actualiza el nombre de acuerdo a tu esquema
            'descripcion' => $request->descripcion,
            'cobertura_minima' => $request->cobertura_minima,
            'cobertura_maxima' => $request->cobertura_maxima,
            'duracion' => $request->duracion,
            'prima_promedio' => $request->prima_promedio,
            'riesgo_asociado' => $request->riesgo_asociado,
            'requisitos' => $request->requisitos,
        ]);

        // Redirigir a la página de lista con un mensaje de éxito
        return redirect()->route('seguros.index')->with('success', 'Seguro actualizado correctamente.');
    }

    // Método para eliminar un seguro
    public function destroy($id)
    {
        // Buscar el seguro por ID y eliminarlo
        $seguro = TipoSeguro::findOrFail($id);
        $seguro->delete();

        // Redirigir a la página de lista con un mensaje de éxito
        return redirect()->route('seguros.index')->with('success', 'Seguro eliminado correctamente.');
    }
}