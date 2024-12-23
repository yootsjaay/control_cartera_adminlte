@extends('adminlte::page')

@section('title', 'Crear Nuevo Seguro')

@section('extra-styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/font-awesome@5.15.4/css/all.min.css" rel="stylesheet" />
<style>
    .subtipo-group {
        margin-bottom: 10px;
    }
    .remove-btn {
        background-color: #dc3545;
        color: white;
    }
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        border-radius: 10px;
        background-color: #f8f9fa;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Nuevo Seguro</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('seguros.index') }}">Lista</a></li>
        <li class="breadcrumb-item active">Crear Seguro</li>
    </ol>

    {{-- Mostrar errores de validación --}}
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

            {{-- Tipo de seguro --}}
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Tipo de Seguro</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>

            {{-- Subtipos dinámicos --}}
            <div class="mb-3">
                <label for="subtipos" class="form-label">Subtipos de Seguro (Opcional)</label>
                <div id="subtipos-container">
                    {{-- Plantilla de subtipo inicial --}}
                    <div class="input-group mb-2 subtipo-item">
                        <input type="text" name="subtipos[0][nombre]" class="form-control" placeholder="Nombre del Subtipo">
                        <button type="button" class="btn btn-danger remove-subtipo">Eliminar</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="add-subtipo">Agregar Subtipo</button>
            </div>

            {{-- Botón de envío --}}
            <button type="submit" class="btn btn-success">Guardar</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let subtipoIndex = 1;

        // Agregar subtipo
        document.getElementById('add-subtipo').addEventListener('click', function () {
            const container = document.getElementById('subtipos-container');

            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2', 'subtipo-item');
            div.innerHTML = `
                <input type="text" name="subtipos[${subtipoIndex}][nombre]" class="form-control" placeholder="Nombre del Subtipo">
                <button type="button" class="btn btn-danger remove-subtipo">Eliminar</button>
            `;
            container.appendChild(div);

            subtipoIndex++;
        });

        // Eliminar subtipo y mantener el índice sincronizado
        document.getElementById('subtipos-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-subtipo')) {
                const item = e.target.closest('.subtipo-item');
                item.remove();

                // Reindexar los nombres de los inputs
                const subtipoItems = document.querySelectorAll('.subtipo-item');
                subtipoItems.forEach((item, index) => {
                    const input = item.querySelector('input');
                    input.name = `subtipos[${index}][nombre]`;
                });

                subtipoIndex = subtipoItems.length; // Actualizar el índice
            }
        });
    });
</script>
@stop
