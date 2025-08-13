@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Editar Usuario</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <input type="text" name="name" class="form-control" value="{{ $usuario->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ $usuario->email }}" required>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select name="rol" class="form-select" required>
                        @foreach($roles as $rol)
                        <option value="{{ $rol }}" {{ $usuario->rol === $rol ? 'selected' : '' }}>{{ $rol }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                    <input type="password" name="password" class="form-control" minlength="6">
                </div>

                <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>
@endsection