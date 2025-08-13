@extends('layouts.app')

@section('title', 'Todas las Publicaciones')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-5">Publicaciones por Categoría</h2>

    {{-- Mensaje cuando no hay resultados en la búsqueda --}}
    @if($sinResultados)
        <div class="alert alert-warning text-center">
            No encontramos publicaciones que coincidan con <strong>"{{ $search }}"</strong>.
        </div>
    @endif

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
        'SUPERADMIN' => null
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
    @php
        // Mapa de nombres especiales a slugs fijos
        $slugPersonalizado = [
            'BIENESTAR Y DESARROLLO INSTITUCIONAL' => 'bienestar-y-desarrollo-institucional',
            'GESTION TALENTO HUMANO' => 'gestion-talento-humano',
            'GESTION DEL CONOCIMIENTO' => 'gestion-del-conocimiento',
            'GESTION ORGANIZACIONAL' => 'gestion-organizacional',
            'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO' => 'grupo-de-seguridad-y-salud-en-el-trabajo',
            'GESTION DE LA TECNOLOGIA' => 'gestion-de-la-tecnologia',
        ];

        // Si no está en el mapa, generar slug automáticamente
        $urlCategoria = $slugPersonalizado[$categoria] ?? Str::slug($categoria);
    @endphp

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

            {{-- Flechas de navegación --}}
            @if($noticias->count() > 3)
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ Str::slug($categoria) }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ Str::slug($categoria) }}" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            @endif
        </div>

        {{-- Botón siempre visible --}}
        <div class="text-center mt-3">
            <a href="{{ route('noticias.partes', ['categoria' => $urlCategoria]) }}" class="btn btn-outline-primary">
                Ver todas las publicaciones de esta categoría
            </a>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection
