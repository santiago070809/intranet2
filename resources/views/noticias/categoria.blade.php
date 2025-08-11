@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fw-bold text-center mb-4">{{ $categoria }}</h2>

    @forelse ($noticias as $noticia)
        <div class="mb-4 border p-3 rounded">
            <h4>{{ $noticia->titulo }}</h4>
            <p>{{ $noticia->contenido }}</p>
        </div>
    @empty
        <p class="text-center text-muted">No hay noticias en esta categoría.</p>
    @endforelse
</div>
@endsection
