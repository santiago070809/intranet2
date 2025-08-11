@extends('layouts.app')

@section('title', $noticia->titulo)

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        @if($noticia->imagen)
            <img src="{{ asset('storage/' . $noticia->imagen) }}" class="card-img-top" style="max-height: 400px; object-fit: cover;">
        @endif

        <div class="card-body">
            <h3 class="card-title">{{ $noticia->titulo }}</h3>
            <p class="text-muted">{{ $noticia->created_at->format('d M Y') }} - Tipo: {{ $noticia->tipo }}</p>
            <p class="card-text">{!! nl2br(e($noticia->contenido)) !!}</p>
<a href="javascript:history.back()" class="btn btn-secondary mt-3">← Volver</a>
        </div>
    </div>
</div>
@endsection
