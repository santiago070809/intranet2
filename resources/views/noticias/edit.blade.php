@extends('layouts.app')

@section('title', 'Editar Noticia')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Editar Noticia</h2>

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
            <form action="{{ route('noticias.update', $noticia->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" class="form-control" value="{{ $noticia->titulo }}" required>
                </div>

                <div class="mb-3">
                    <label for="contenido" class="form-label">Contenido</label>
                    <textarea name="contenido" class="form-control" rows="4" required>{{ $noticia->contenido }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select name="categoria" class="form-select" required>
                        @foreach($secciones as $seccion)
                        <option value="{{ $seccion }}" {{ $noticia->categoria === $seccion ? 'selected' : '' }}>{{ $seccion }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fecha_documento" class="form-label">Fecha del documento</label>
                    <input type="date" name="fecha_documento" class="form-control" value="{{ $noticia->fecha_documento }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de noticia</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tipo[]" value="banner" {{ in_array('banner', (array) $noticia->tipo) ? 'checked' : '' }}>
                        <label class="form-check-label">Banner</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tipo[]" value="varia" {{ in_array('varia', (array) $noticia->tipo) ? 'checked' : '' }}>
                        <label class="form-check-label">Varia</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen (opcional)</label>
                    <input type="file" name="imagen" class="form-control">
                    @if($noticia->imagen)
                    <small>Imagen actual: <a href="{{ asset('storage/'.$noticia->imagen) }}" target="_blank">Ver</a></small>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="archivos" class="form-label">Archivos adicionales (opcional)</label>
                    <input type="file" name="archivos[]" class="form-control" multiple
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.mp3,.mp4,.wav,.avi">

                    @if($noticia->archivos && count($noticia->archivos))
                    <small>Archivos actuales:</small>
                    <ul>
                        @foreach($noticia->archivos as $archivo)
                        <li>
                            <a href="{{ asset('storage/' . $archivo) }}" target="_blank">{{ basename($archivo) }}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>
@endsection