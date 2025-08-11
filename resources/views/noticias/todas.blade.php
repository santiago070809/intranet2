@extends('layouts.app')


@section('title', 'Todas las Publicaciones')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-5">Publicaciones por Categoría</h2>

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
<p class="card-text text-muted small">{{ \Carbon\Carbon::parse($noticia->fecha_documento)->format('d M Y') }}</p>
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

                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ Str::slug($categoria) }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ Str::slug($categoria) }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('noticias.filtrarPorCategoria', ['categoria' => $categoria]) }}" class="btn btn-outline-primary">
                        Ver todas las noticias de esta categoría
                    </a>
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
