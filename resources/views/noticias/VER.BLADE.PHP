@extends('layouts.app')

@section('title', $noticia->titulo)

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        {{-- Imagen destacada --}}
        @if($noticia->imagen)
        <img src="{{ asset('storage/' . $noticia->imagen) }}" class="card-img-top" style="max-height: 400px; object-fit: cover;">
        @endif

        <div class="card-body">
            <h3 class="card-title">{{ $noticia->titulo }}</h3>



            {{-- Contenido --}}
            <p class="card-text">{!! nl2br(e($noticia->contenido)) !!}</p>

           {{-- Archivos opcionales --}}
@if($noticia->archivos && count($noticia->archivos))
<div class="mt-4 text-center">
    <h5>📎 Archivos adjuntos</h5>
    <div class="d-flex flex-wrap justify-content-center gap-3">
        @foreach($noticia->archivos as $archivo)
        @php
            $ext = pathinfo($archivo, PATHINFO_EXTENSION);
        @endphp

        <div class="card" style="flex: 1 1 45%; max-width: 600px;">
            {{-- PDF --}}
            @if(Str::endsWith($archivo, '.pdf'))
                <embed src="{{ asset('storage/' . $archivo) }}#page=1&view=FitH" type="application/pdf" width="100%" height="400" style="display:block; margin:auto;">

            {{-- Imagen --}}
            @elseif(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                <img src="{{ asset('storage/' . $archivo) }}" class="card-img-top" style="max-height: 400px; object-fit: contain; display:block; margin:auto;">

            {{-- Video --}}
            @elseif(in_array(strtolower($ext), ['mp4','webm','ogg','avi']))
                <video class="w-100" controls style="max-height: 400px; display:block; margin:auto;">
                    <source src="{{ asset('storage/' . $archivo) }}" type="video/{{ $ext }}">
                    Tu navegador no soporta este video.
                </video>

            {{-- Audio --}}
            @elseif(in_array(strtolower($ext), ['mp3','wav','ogg']))
                <audio class="w-100" controls style="display:block; margin:auto;">
                    <source src="{{ asset('storage/' . $archivo) }}" type="audio/{{ $ext }}">
                    Tu navegador no soporta este audio.
                </audio>

            {{-- Otros (Word, Excel, etc) --}}
            @else
                <div class="text-center p-4">
                    <i class="fas fa-file fa-3x text-secondary"></i>
                    <p class="mt-2">{{ basename($archivo) }}</p>
                </div>
            @endif

            {{-- Botón ver completo --}}
            <div class="card-body text-center">
                <a href="{{ asset('storage/' . $archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">Ver completo</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif




            <a href="javascript:history.back()" class="btn btn-secondary mt-3">← Volver</a>
        </div>
    </div>
</div>
@endsection