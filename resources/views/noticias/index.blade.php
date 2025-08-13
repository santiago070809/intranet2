@extends('layouts.app')

@section('title', 'Administrar Noticias')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Administrar Noticias</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Buscador --}}
    <form method="GET" action="{{ route('noticias.admin') }}" class="mb-3 d-flex">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Buscar por título o categoría">
        <button class="btn btn-primary">Buscar</button>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($noticias as $noticia)
            <tr>
                <td>{{ $noticia->id }}</td>
                <td>{{ $noticia->titulo }}</td>
                <td>{{ $noticia->categoria }}</td>
                <td>{{ $noticia->fecha_documento }}</td>
                <td>{{ implode(', ', (array) $noticia->tipo) }}</td>
                <td>
                    @php
                    // Mapeo de roles a carpeta raíz
                    $rolCarpeta = [
                    'BIENESTAR Y DESARROLLO INSTITUCIONAL' => 'BIENESTAR Y DESARROLLO INSTITUCIONAL',
                    'TALENTO_HUMANO' => 'GESTION TALENTO HUMANO',
                    'CONOCIMIENTO' => 'GESTION DEL CONOCIMIENTO',
                    'ORGANIZACIONAL' => 'GESTION ORGANIZACIONAL',
                    'SEGURIDAD_SALUD' => 'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO',
                    'TECNOLOGIA' => 'GESTION DE LA TECNOLOGIA',
                    'SUPERADMIN' => null
                    ];

                    $categoriaPermitida = $rolCarpeta[Auth::user()->rol] ?? null;

                    // Puede editar si es SUPERADMIN o si la noticia está en su carpeta o subcarpeta
                    $puedeEditar = Auth::user()->rol === 'SUPERADMIN'
                    || ($categoriaPermitida && str_starts_with($noticia->categoria, $categoriaPermitida));
                    @endphp

                    @if($puedeEditar)
                    <a href="{{ route('noticias.edit', $noticia->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('noticias.destroy', $noticia->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta noticia?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                    @else
                    <small class="text-muted">Sin permisos</small>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No se encontraron noticias.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $noticias->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection