@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Crear Nuevo Usuario</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select name="rol" class="form-select" required>
                        <option value="">Seleccione un rol</option>
                        @foreach($roles as $rol)
                        <option value="{{ $rol }}">{{ $rol }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Ver Lista de Usuarios
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection