@extends('layouts.app')

@section('title', 'Noticias de ' . $categoria)

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold mb-4 text-center">Noticias de {{ $categoria }}</h2>

    @if($noticias->isEmpty())
    <p class="text-muted text-center">No hay noticias disponibles en esta categoría.</p>
    @else
    <div class="row">
        @foreach($noticias as $noticia)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($noticia->imagen)
                <img src="{{ asset('storage/' . $noticia->imagen) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $noticia->titulo }}</h5>
                    <p class="card-text">{{ Str::limit($noticia->contenido, 100) }}</p>
                    <a href="{{ route('noticias.ver', $noticia->id) }}" class="btn btn-sm btn-outline-success mt-auto">Leer más</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection