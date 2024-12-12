@extends('adminlte::page')

@section('title', 'Crear Nuevo Seguro')

@section('extra-styles')
    <!-- Incluir estilos adicionales específicos para esta vista -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Nuevo Seguro</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Crear Seguro</li>
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

    <div class="card mb-4" style="max-width: 700px; margin: 0 auto;">
        <div class="card-body">
            <form action="{{ route('seguros.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Seguro</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>


                <!-- Agregar los demás campos según tus necesidades -->

                <div class="mb-3">
                    <label for="subtipos" class="form-label">Subtipos de Seguro</label>
                    <input type="text" name="subtipos[]" id="subtipos" class="form-control" placeholder="Ingrese un subtipo">
                    <button type="button" id="add-subtipo" class="btn btn-primary mt-2">Agregar Subtipo</button>
                </div>

                <div id="subtipos-container"></div>

                <button type="submit" class="btn btn-success mt-3">Crear Seguro</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section ('js')
<script>
    document.getElementById('add-subtipo').addEventListener('click', function() {
        var container = document.getElementById('subtipos-container');
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'subtipos[]';
        input.classList.add('form-control', 'mb-2');
        input.placeholder = 'Ingrese un subtipo';
        container.appendChild(input);
    });
</script>
@stop
