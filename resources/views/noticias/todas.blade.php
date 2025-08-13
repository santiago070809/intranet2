@extends('layouts.app')

@section('title', 'Todas las Publicaciones')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-5">Publicaciones por Categoría</h2>

    {{-- BOTÓN DE CREAR NOTICIA SEGÚN ROL --}}
    @auth
    @php
    $rutasPorRol = [
    'BIENESTAR Y DESARROLLO INSTITUCIONAL' => 'BIENESTAR Y DESARROLLO INSTITUCIONAL',
    'TALENTO_HUMANO' => 'GESTION TALENTO HUMANO',
    'CONOCIMIENTO' => 'GESTION DEL CONOCIMIENTO',
    'ORGANIZACIONAL' => 'GESTION ORGANIZACIONAL',
    'SEGURIDAD_SALUD' => 'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO',
    'TECNOLOGIA' => 'GESTION DE LA TECNOLOGIA',
    'SUPERADMIN' => null // podrá elegir cualquier carpeta
    ];

    $rolUsuario = Auth::user()->rol ?? null;
    $rutaAsignada = $rutasPorRol[$rolUsuario] ?? null;
    @endphp

    @if($rolUsuario === 'SUPERADMIN')
    <a href="{{ url('/noticias/create') }}" class="btn btn-success mb-4">
        <i class="fas fa-plus-circle"></i> Crear Noticia
    </a>
    @elseif($rutaAsignada)
    <a href="{{ url('/noticias/create?ruta=' . $rutaAsignada) }}" class="btn btn-success mb-4">
        <i class="fas fa-plus-circle"></i> Crear Noticia en mi carpeta
    </a>
    @endif
    @endauth

    @foreach($noticiasPorCategoria as $categoria => $noticias)
    <div class="mb-5">
        <h3 class="mb-3">{{ $categoria }}</h3>

        @if($noticias->isEmpty())
        <p class="text-muted">No hay noticias disponibles en esta categoría.</p>
        @else
        <div id="carousel-{{ Str::slug($categoria) }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($noticias->chunk(3) as $index => $chunk)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="row">
                        @foreach($chunk as $noticia)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                @if($noticia->imagen)
                                <img src="{{ asset('storage/' . $noticia->imagen) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $noticia->titulo }}</h5>
                                    <p class="card-text text-muted small">
                                        {{ \Carbon\Carbon::parse($noticia->fecha_documento)->format('d M Y') }}
                                    </p>
                                    <p class="card-text">{{ Str::limit($noticia->contenido, 80) }}</p>
                                    <a href="{{ route('noticias.ver', $noticia->id) }}" class="btn btn-sm btn-outline-success mt-auto">Leer más</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Mostrar flechas solo si no es la primera categoría y hay más de 3 noticias --}}
            @if(!$loop->first && $noticias->count() > 3)
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ Str::slug($categoria) }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ Str::slug($categoria) }}" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            @endif
        </div>

        {{-- Botón centrado con ícono y enlace según la categoría --}}
        <div class="text-center mt-3">
            @switch($categoria)
            @case('BIENESTAR Y DESARROLLO INSTITUCIONAL')
            <a href="{{ route('noticias.partes', ['categoria' => 'bienestar-y-desarrollo-institucional']) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                <i class="bi bi-people-fill me-2"></i> Ver todas las publicaciones de esta categoría
            </a>
            @break
            @case('GESTION TALENTO HUMANO')
            <a href="{{ route('noticias.partes', ['categoria' => 'gestion-talento-humano']) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                <i class="bi bi-briefcase-fill me-2"></i> Ver todas las publicaciones de esta categoría
            </a>
            @break
            @case('GESTION DEL CONOCIMIENTO')
            <a href="{{ route('noticias.partes', ['categoria' => 'gestion-del-conocimiento']) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                <i class="bi bi-file-earmark-text-fill me-2"></i> Ver todas las publicaciones de esta categoría
            </a>
            @break
            @case('GESTION ORGANIZACIONAL')
            <a href="{{ route('noticias.partes', ['categoria' => 'gestion-organizacional']) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                <i class="bi bi-folder-fill me-2"></i> Ver todas las publicaciones de esta categoría
            </a>
            @break
            @case('GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO')
            <a href="{{ route('noticias.partes', ['categoria' => 'grupo-de-seguridad-y-salud-en-el-trabajo']) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                <i class="bi bi-database-fill me-2"></i> Ver todas las publicaciones de esta categoría
            </a>
            @break
            @case('GESTION DE LA TECNOLOGIA')
            <a href="{{ route('noticias.partes', ['categoria' => 'gestion-de-la-tecnologia']) }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                <i class="bi bi-database-fill me-2"></i> Ver todas las publicaciones de esta categoría
            </a>
            @break
            @endswitch
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection