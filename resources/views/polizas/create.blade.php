@extends('adminlte::page')

@section('title', 'Subir Póliza')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Subir Pólizas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('companias.index') }}">Compañía</a></li>
        <li class="breadcrumb-item active">Registrar</li>
    </ol>

    <div class="row">
        <!-- Primera sección: Formulario de carga de pólizas -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Subir Póliza</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('polizas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="compania_id1" class="form-label">Compañía</label>
                            <select class="form-select" name="compania_id" id="compania_id1" required>
                                <option value="" disabled selected>Seleccione una compañía</option>
                                @foreach ($companias as $compania)
                                    <option value="{{ $compania->id }}">{{ $compania->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_seguro_id1" class="form-label">Tipo de Seguro</label>
                            <select class="form-select" name="tipo_seguro_id" id="tipo_seguro_id1" required>
                                <option value="" disabled selected>Seleccione un tipo de seguro</option>
                                @foreach ($seguros as $seguro)
                                    <option value="{{ $seguro->id }}">{{ $seguro->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pdf" class="form-label">Archivo PDF</label>
                            <input class="form-control" type="file" name="pdf[]" multiple required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Subir</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Segunda sección: captura de información faltante -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Pdf Extracción con OCR</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('polizas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                      
                        <div class="mb-3">
                            <label for="Polizas_imagen" class="form-label">Seleccionar Numero de Poliza</label>
                            <select  class ="form-select"  name="poliza_id" id="Polizas_imagen" required>
                                <option value="" disabled selected>Seleccione numero de Poliza</option>
                            
                            @foreach ($polizas as $poliza)
                                <option value="{{ $poliza->id }}">{{ $poliza->numero_poliza }}</option>
                            @endforeach
                        </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Convertir y Extraer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
@stop

@section('js')
<script>
// Agrega aquí los scripts necesarios para cargar y manipular los PDFs
</script>
@stop
