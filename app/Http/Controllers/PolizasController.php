<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Compania;
use App\Models\TipoSeguro;
use App\Models\Poliza;
use App\Models\Agente;
use App\Models\PagosSubsecuente;
use DateTime;
use Exception;

use Smalot\PdfParser\Parser;


class PolizasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $polizas = Poliza::all();
        $companias= Compania::all();
        $seguros = TipoSeguro::all();
        return view('polizas.index', compact('polizas', 'companias', 'seguros'));
    }

            

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $companias = Compania::all();
        $seguros = TipoSeguro::all();
        
        return view('polizas.create', compact('clientes', 'companias', 'seguros'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar pdf
        $request->validate([
           'pdf.*' => 'mimes:pdf|max:10000',
            'compania_id' => 'required|exists:companias,id',
            'tipo_seguro_id' => 'required|exists:tipo_seguros,id',
        ]);
    
        $compania_id = $request->input('compania_id');
        $compania = Compania::find($compania_id);
    
        foreach ($request->file('pdf') as $file) {
            // Almacenar el archivo PDF en el storage
            $pdfPath = $file->store('Polizas', 'public');
    
            // Obtener el archivo PDF subido y usar la librería de parser
            $parser = new \Smalot\PdfParser\Parser(); // Asegúrate de tener el parser importado correctamente
    
            try {
                $pdfParsed = $parser->parseFile(storage_path('app/public/' . $pdfPath));
                $pages = $pdfParsed->getPages();
                $allText = '';
    
                $selectedPages = [0, 3]; // Páginas del 1 al 4 
                foreach ($pages as $index => $page) {
                    if (in_array($index, $selectedPages)) {
                        $allText .= "Pagina " . ($index + 1) . "\n";
                        $allText .= $page->getText() . "\n";
    
                        if ($index == 3) {
                            $datosPago = $this->extraerDatosHdi($page->getText());
                            \Log::info('Datos Extraidos Exitosamente', $datosPago);
                        }
                    }
                }
    
                // Selecciona compañías
                switch ($compania->nombre) {
                    case 'HDI Seguros':
                        $datos = $this->extraerDatosHdi($allText);
                        break;
                    case 'Banorte Seguros': 
                        $datos = $this->extraerDatosBanorte($allText);
                        break;
                    // Agregar casos para cada compañía
                    default:
                        \Log::error('Compañía de seguros no identificada: ' . $compania->nombre);
                        return redirect()->back()->with('error', 'Compañía de seguros no identificada');
                }
    
                $cliente = Cliente::firstOrCreate(
                    ['rfc' => $datos['rfc']],
                    ['nombre_completo' => $datos['nombre_cliente']]
                );
    
                if (preg_match('/Vigencia:\s*Desde las 12:00 hrs\. del\s*(\d{2}\/\d{2}\/\d{4})\s*Hasta las 12:00 hrs\. del\s*(\d{2}\/\d{2}\/\d{4})/', $allText, $matches)) {
                    $datos['vigencia_inicio'] = $this->convertirFecha($matches[1]);
                    $datos['vigencia_fin'] = $this->convertirFecha($matches[2]);
                } else {
                    \Log::warning('No se encontró la vigencia en el PDF. Archivo: ' . $pdfPath);
                }
    
                $agente = Agente::firstOrCreate(
                    ['numero_agentes' => $datos['numero_agente']],
                    ['nombre_agentes' => $datos['nombre_agente']]
                );
    
                // Mostrar el texto extraído para depuración
               // dd($pdfParsed); // Descomenta esto si necesitas hacer pruebas
    
                // Guardar los datos en la base de datos si es necesario
                Poliza::create([
                    'cliente_id' => $cliente->id,
                    'compania_id' => $compania_id,
                    'agente_id' => $agente->id,
                    'tipo_seguro_id' => $request->input('tipo_seguro_id'),
                    'numero_poliza' => $datos['numero_poliza'] ?? 'No disponible',
                    'vigencia_inicio' => $datos['vigencia_inicio'] ?? null,
                    'vigencia_fin' => $datos['vigencia_fin'] ?? null,
                    'forma_pago' => $datos['forma_pago'] ?? 'No especificada',
                    'total_a_pagar' => isset($datos['total_pagar']) ? floatval(str_replace(',', '', $datos['total_pagar'])) : 0,
                    'archivo_pdf' => $pdfPath,
                    'pagos_capturados' => false, // Indicador que aun no se ha capturado los pagos subsecuentes
                ]);
    
                return response()->json([
                    'pdf_text' => $allText,
                    'message' => 'PDF procesado con éxito',
                ]);
            } catch (\Exception $e) {
                // Manejar errores en el parsing del PDF
                return response()->json([
                    'error' => 'Hubo un problema al procesar el archivo PDF.',
                    'details' => $e->getMessage(),
                ], 500);
            }
        }
    }
// Función para convertir la fecha
public function convertirFecha($fecha)
{
    try {
        $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha);
        if ($fechaObj === false) {
            // Manejo de error si el formato no es válido
            return null;
        }
        return $fechaObj->format('Y-m-d');
    } catch (Exception $e) {
        return null;
    }
}


    public function extraerDatosHdi($text) {
        $datos = [];
    
        // Extraer número de póliza
        if (preg_match('/Póliza:\s*([0-9\-]+)/', $text, $matches)) {
            $datos['numero_poliza'] = trim($matches[1]);
        } else {
            $datos['numero_poliza'] = 'No encontrado';
        }
    
        // Extraer nombre del cliente
        if (preg_match('/\n([A-Z\s]+)\n\s*RFC:/', $text, $matches)) {
            $datos['nombre_cliente'] = trim($matches[1]);
        } else {
            $datos['nombre_cliente'] = 'No encontrado';
        }
    
        // Extraer RFC
        if (preg_match('/RFC:\s*([A-Z0-9]+)/', $text, $matches)) {
            $datos['rfc'] = $matches[1];
        } else {
            $datos['rfc'] = 'No encontrado';
        }
    
        // Extraer marca, modelo y año del vehículo
        if (preg_match('/([A-Z\s]+)\s*,\s*([A-Z\s]+)\s*([0-9]{4})/', $text, $matches)) {
            $marca = trim($matches[1]);
            if (strpos($marca, 'NO APLICA') !== false) {
                $marca = str_replace('NO APLICA', '', $marca);
                $marca = trim($marca);
            }
            $datos['marca'] = $marca;
            $datos['modelo'] = trim($matches[2]);
            $datos['anio'] = trim($matches[3]);
        } else {
            $datos['marca'] = 'No encontrado';
            $datos['modelo'] = 'No encontrado';
            $datos['anio'] = 'No encontrado';
        }
    
        // Forma de pago
        $formas_pago = ['SEMESTRAL EFECTIVO', 'TRIMESTRAL EFECTIVO', 'ANUAL EFECTIVO', 'MENSUAL EFECTIVO'];
        foreach ($formas_pago as $forma) {
            if (preg_match('/' . preg_quote($forma, '/') . '/i', $text)) {
                $datos['forma_pago'] = $forma;
                break;
            }
        }
        if (!isset($datos['forma_pago'])) {
            $datos['forma_pago'] = 'NO APLICA';
        }
    
        // Extraer el total a pagar
        if (preg_match('/([0-9,]+\.\d{2})\s*Total a Pagar/', $text, $matches)) {
            $datos['total_pagar'] = trim($matches[1]);
        } else {
            $datos['total_pagar'] = 'No encontrado';
        }
    
        // Extraer agente (número y nombre)
        if (preg_match('/Agente:\s*([0-9]+)\s*([A-Z\s]+)\s*(?=\n\s*Descripción|$)/', $text, $matches)) {
            $datos['numero_agente'] = trim($matches[1]);
            $nombre_agente = trim(preg_replace('/\s+/', ' ', $matches[2]));
            $datos['nombre_agente'] = $nombre_agente;
        } else {
            $datos['numero_agente'] = 'No encontrado';
            $datos['nombre_agente'] = 'No encontrado';
        }
    
        // Extraer recibos (fechas de pago, importes, vigencia)
        $pattern = '/(\d{2}-\w{3}-\d{4})al\d+\s+([\d,]+\.\d{2})\s+(\d{2}-\w{3}-\d{4})\s+(\d{2}-\w{3}-\d{4})/';
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
        
        $recibos = [];
        foreach ($matches as $match) {
            $recibos[] = [
                'fecha_pago' => $this->convertirFecha($match[1]),
                'importe' => floatval(str_replace(',', '', $match[2])),
                'vigencia_inicio' => $this->convertirFecha($match[3]),
                'vigencia_fin' => $this->convertirFecha($match[4]),
            ];
        }
        
        // Agregar los recibos a los datos extraídos
        $datos['recibos'] = $recibos;
    
        // Retornar todos los datos extraídos
        return $datos;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

  
}
