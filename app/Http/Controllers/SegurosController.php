<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoSeguro;
use App\Models\SubTipoSeguro;  // Asegúrate de importar el modelo correcto

class SegurosController extends Controller
{
    public function index()
    {
        $seguros = TipoSeguro::all();
        $subtipos = SubTipoSeguro::all(); // Asegúrate de cargar los subtipos correctamente
        return view('seguros.index', compact('seguros','subtipos'));
    }

    public function create()
    {
        return view('seguros.create');
    }

    public function store(Request $request)
{
    // Validar los datos recibidos
    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'cobertura_minima' => 'nullable|numeric|min:0',
        'cobertura_maxima' => 'nullable|numeric|min:0',
        'duracion' => 'nullable|integer|min:1',
        'prima_promedio' => 'nullable|numeric|min:0',
        'riesgo_asociado' => 'nullable|in:bajo,medio,alto',
        'requisitos' => 'nullable|string',
        'subtipos' => 'nullable|array', // Campo para los subtipos de seguros
        'subtipos.*' => 'nullable|string', // Cada subtipo será un string
    ]);

    // Crear un nuevo tipo de seguro
    $tipoSeguro = TipoSeguro::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'cobertura_minima' => $request->cobertura_minima,
        'cobertura_maxima' => $request->cobertura_maxima,
        'duracion' => $request->duracion,
        'prima_promedio' => $request->prima_promedio,
        'riesgo_asociado' => $request->riesgo_asociado,
        'requisitos' => $request->requisitos,
    ]);

    // Si se proporcionaron subtipos, crearlos
    if ($request->has('subtipos') && is_array($request->subtipos)) {
        foreach ($request->subtipos as $subtipo) {
            $tipoSeguro->subtipos()->create([
                'nombre' => $subtipo,
            ]);
        }
    }

    return redirect()->route('seguros.index')->with('success', 'Tipo de seguro creado con éxito.');
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