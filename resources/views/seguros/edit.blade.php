@extends('adminlte::page')

@section('title', 'Editar Seguro')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Seguro</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Editar Seguro</li>
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

    <div class="card mb-4"  style="max-width: 700px; margin: 0 auto;">
        <div class="card-body">
            <form action="{{ route('seguros.update', $seguro->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="tipo_seguro" class="form-label">Tipo de Seguro</label>
                    <input type="text" class="form-control" id="tipo_seguro" name="tipo_seguro" value="{{ old('tipo_seguro', $seguro->tipo_seguro) }}" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion">{{ old('descripcion', $seguro->descripcion) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="cobertura_minima" class="form-label">Cobertura Mínima</label>
                    <input type="number" class="form-control" id="cobertura_minima" name="cobertura_minima" value="{{ old('cobertura_minima', $seguro->cobertura_minima) }}">
                </div>

                <div class="mb-3">
                    <label for="cobertura_maxima" class="form-label">Cobertura Máxima</label>
                    <input type="number" class="form-control" id="cobertura_maxima" name="cobertura_maxima" value="{{ old('cobertura_maxima', $seguro->cobertura_maxima) }}">
                </div>

                <div class="mb-3">
                    <label for="duracion" class="form-label">Duración (años)</label>
                    <input type="number" class="form-control" id="duracion" name="duracion" value="{{ old('duracion', $seguro->duracion) }}">
                </div>

                <div class="mb-3">
                    <label for="prima_promedio" class="form-label">Prima Promedio</label>
                    <input type="number" class="form-control" id="prima_promedio" name="prima_promedio" value="{{ old('prima_promedio', $seguro->prima_promedio) }}">
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection