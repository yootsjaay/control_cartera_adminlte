@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar Usuario</h1>
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del Usuario</h3>
        </div>
        <!-- Formulario de Edición -->
        <form action="{{ route('user.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Indica el método PUT para actualización -->

            <div class="card-body">
                <!-- Campo Nombre -->
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Ingrese el nombre" value="{{ old('name', $usuario->name) }}">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Email -->
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Ingrese el correo electrónico" value="{{ old('email', $usuario->email) }}">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo Contraseña -->
                <div class="form-group">
                    <label for="password">Contraseña (dejar en blanco para no cambiarla)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Ingrese la nueva contraseña">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmación de Contraseña -->
                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirme la nueva contraseña">
                    @error('password_confirmation')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Selector de Roles -->
                <div class="form-group">
                    <label for="roles">Roles</label>
                    <select class="form-control @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple>
                        <option value="">Seleccione un rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $usuario->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- Fin del cuerpo de la tarjeta -->

            <!-- Botones de Acción -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    {{-- Estilos adicionales si los necesitas --}}
@stop

@section('js')
    {{-- Scripts adicionales si los necesitas --}}
@stop
