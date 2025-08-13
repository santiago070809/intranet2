@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Mi Perfil</h2>

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
            <p><strong>Nombre:</strong> {{ $usuario->name }}</p>
            <p><strong>Correo:</strong> {{ $usuario->email }}</p>
            <p><strong>Contraseña:</strong> ••••••••</p>
        </div>
    </div>

    <hr>

    <h4 class="mt-4">Cambiar Contraseña</h4>
    <form action="{{ route('perfil.cambiarPassword') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="password_actual" class="form-label">Contraseña Actual</label>
            <input type="password" name="password_actual" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password_nueva" class="form-label">Nueva Contraseña</label>
            <input type="password" name="password_nueva" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password_nueva_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_nueva_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Actualizar Contraseña</button>
    </form>
</div>
@endsection