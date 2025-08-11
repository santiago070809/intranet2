@extends('layouts.app')

@section('title', 'Explorador y Nueva Noticia')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Explorador de Carpetas y Noticias</h2>

    {{-- ÉXITO --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORMULARIO: CREAR CARPETA --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-folder-plus"></i> Crear Carpeta
        </div>
        <div class="card-body">
            <form action="{{ route('noticias.crearCarpeta') }}" method="POST" class="row g-2">
                @csrf
                <input type="hidden" name="ruta_base" value="{{ $ruta }}">
                <div class="col-md-9">
                    <input type="text" name="nombre_carpeta" class="form-control" placeholder="Nombre de la nueva carpeta" required>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100"><i class="fas fa-plus-circle"></i> Crear</button>
                </div>
            </form>
        </div>
    </div>

    {{-- FORMULARIO: NAVEGAR ENTRE CARPETAS --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-folder-open"></i> Navegar Carpetas
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('noticias.create') }}">
                <label for="ruta">Carpeta actual:</label>
                <select name="ruta" id="ruta" class="form-select" onchange="this.form.submit()">
                    <option value="/">/ (raíz)</option>
                    @foreach ($secciones as $seccion)
                        <option value="{{ $seccion }}" {{ $ruta === $seccion ? 'selected' : '' }}>{{ $seccion }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    {{-- VISUALIZAR SUBCARPETAS COMO EXPLORADOR --}}
    <div class="mb-4">
        <h5 class="fw-bold">📁 Carpetas en: {{ $ruta }}</h5>
        @if(count($rutas))
            <ul class="list-group">
                @foreach ($rutas as $dir)
                    <li class="list-group-item">
                        <i class="fas fa-folder text-warning me-2"></i>
                        <a href="{{ route('noticias.create', ['ruta' => $dir]) }}">{{ basename($dir) }}</a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No hay subcarpetas en esta ruta.</p>
        @endif
    </div>

    {{-- FORMULARIO: CREAR NOTICIA EN CARPETA ACTUAL --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-newspaper"></i> Crear Noticia en: <strong>{{ $ruta }}</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('noticias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ruta_destino" value="{{ $ruta }}">
                <input type="hidden" name="categoria" value="{{ $ruta }}">

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <textarea name="contenido" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen destacada</label>
                    <input type="file" name="imagen" class="form-control">
                </div>

                <div class="mb-3">
    <label for="fecha_documento" class="form-label">Fecha del documento</label>
    <input type="date" name="fecha_documento" class="form-control">
</div>

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de noticia</label>
                    <select name="tipo" class="form-select" required>
                        <option value="reciente">Reciente</option>
                        <option value="varia">Varia</option>
                    </select>
                </div>

                <button class="btn btn-success w-100"><i class="fas fa-save"></i> Guardar Noticia</button>
            </form>
        </div>
    </div>

    {{-- ARCHIVOS EN LA CARPETA --}}
    <div>
        <h5 class="fw-bold">🗂 Archivos en: {{ $ruta }}</h5>
        @if(count($archivos))
            <ul class="list-group">
                @foreach ($archivos as $file)
                    <li class="list-group-item">
                        <i class="fas fa-file-alt text-secondary me-2"></i>
                        {{ basename($file) }}
                        <a href="{{ asset('storage/' . $file) }}" class="btn btn-sm btn-outline-primary float-end" target="_blank">
                            Ver
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No hay archivos en esta carpeta.</p>
        @endif
    </div>
</div>

@endsection
{{-- MENSAJES --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
