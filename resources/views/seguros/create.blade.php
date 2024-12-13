@extends('adminlte::page')

@section('title', 'Crear Nuevo Seguro')

@section('extra-styles')
    <!-- Estilos adicionales específicos para esta vista -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@5.15.4/css/all.min.css" rel="stylesheet" />
    <style>
        .subtipo-group {
            position: relative;
            margin-bottom: 10px;
        }
        .remove-btn {
            position: absolute;
            right: 0;
            top: 5px;
            background-color: #dc3545;
            color: white;
        }
        .add-btn-container {
            margin-top: 20px;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Nuevo Seguro</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('seguros.index') }}"> Lista</a></li>
        <li class="breadcrumb-item active">Crear Seguros<li>
    </ol>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('seguros.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Seguro</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>

            <!-- Sección de Subtipos -->
            <div class="mb-3">
                <label for="subtipos" class="form-label">Tipo Seguro</label>
                <div class="subtipo-group">
                    <input type="text" name="subtipos[]" class="form-control" placeholder="Ingrese un tipo seguro">
                    <button type="button" id="add-subtipo" class="btn btn-primary mt-2"><i class="fas fa-plus"></i> Agregar Tipo</button>
                </div>
                <div id="subtipos-container"></div>
            </div>

            <div class="add-btn-container">
                <button type="submit" class="btn btn-success btn-lg w-100">Crear Seguro</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.getElementById('add-subtipo').addEventListener('click', function() {
        var container = document.getElementById('subtipos-container');
        
        // Crear el nuevo grupo de subtipo
        var inputGroup = document.createElement('div');
        inputGroup.classList.add('subtipo-group');
        
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'subtipos[]';
        input.classList.add('form-control');
        input.placeholder = 'Ingrese un subtipo';
        
        // Crear botón de eliminar
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'remove-btn', 'btn-sm');
        removeButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
        
        // Eliminar el campo cuando se haga clic en el botón
        removeButton.addEventListener('click', function() {
            inputGroup.remove();
        });
        
        // Añadir el input y el botón de eliminar al contenedor
        inputGroup.appendChild(input);
        inputGroup.appendChild(removeButton);
        container.appendChild(inputGroup);
    });
</script>
@stop
