@extends('layouts.app')

@section('title', 'Publicaciones por Partes')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-5">Publicaciones en {{ $rutaActual ?: 'Raíz' }}</h2>

    <a href="javascript:history.back()" class="btn btn-secondary mt-3">← Volver</a>

    {{-- Mostrar subcarpetas --}}
    @if(count($subcarpetas) > 0)
    <h4>Subcarpetas</h4>
    <ul class="list-group mb-4">
        @foreach($subcarpetas as $subcarpeta)
        @php
        // Obtener solo el nombre de la subcarpeta para mostrar
        $nombreSubcarpeta = basename($subcarpeta);
        @endphp
        <li class="list-group-item">
            <a href="{{ route('noticias.partes', ['ruta' => $subcarpeta]) }}">
                📁 {{ $nombreSubcarpeta }}
            </a>
        </li>
        @endforeach
    </ul>
    @endif

    {{-- Mostrar noticias --}}
    <h4>Noticias</h4>
    @if($noticias->isEmpty())
    <p>No hay noticias en esta carpeta.</p>
    @else
    <div class="row">
        @foreach($noticias as $noticia)
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                @if($noticia->imagen)
                <img src="{{ asset('storage/' . $noticia->imagen) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5>{{ $noticia->titulo }}</h5>
                    <p class="text-muted small">{{ \Carbon\Carbon::parse($noticia->fecha_documento)->format('d M Y') }}</p>
                    <p>{{ \Illuminate\Support\Str::limit($noticia->contenido, 80) }}</p>
                    <a href="{{ route('noticias.ver', $noticia->id) }}" class="btn btn-sm btn-outline-success mt-auto">Leer más</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection