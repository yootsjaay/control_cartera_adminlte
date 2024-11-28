@extends('adminlte::page')

@section('title', 'Crear Compañía')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Compañía</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('companias.index') }}">Compañía</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>

    <div class="card mb-4" style="max-width: 700px; margin: 0 auto;">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Crear Compañía
        </div>
        <div class="card-body">
            <div class="form-container">
                <form action="{{ route('companias.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="nombre">Nombre de la Compañía</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
                        @if ($errors->has('nombre'))
                            <span class="text-danger">{{ $errors->first('nombre') }}</span>
                        @endif
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary mt-3">Crear Compañía</button>
                    </div>
                </form>
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
        console.log('Página lista.');
    </script>
@stop
