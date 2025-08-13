<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Intranet Gobernación del Cauca</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome (para la lupa) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background-color: #004e2a;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #ffc107 !important;
        }

        .search-input {
            border-radius: 20px 0 0 20px;
            border-right: none;
            padding-left: 15px;
        }

        .search-btn {
            border-radius: 0 20px 20px 0;
            border-left: none;
            background-color: #ffc107;
            color: #000;
        }

        .search-btn:hover {
            background-color: #e0a800;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="/">
                <img src="https://www.cauca.gov.co/SiteAssets/images/logo-encabezado.png" height="60" alt="Logo">
            </a>

            <!-- Botón responsive -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <!-- INICIO -->
                    <li class="nav-item">
                        <a class="nav-link" href="/">INICIO</a>
                    </li>

                    <!-- SECRETARÍA GENERAL -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="secretariaDropdown" role="button" data-bs-toggle="dropdown">
                            SECRETARÍA GENERAL
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('noticias.partes', ['categoria' => 'bienestar-y-desarrollo-institucional']) }}">Bienestar y Desarrollo Institucional</a></li>
                            <li><a class="dropdown-item" href="{{ route('noticias.partes', ['categoria' => 'gestion-talento-humano']) }}">Gestión Talento Humano</a></li>
                            <li><a class="dropdown-item" href="{{ route('noticias.partes', ['categoria' => 'gestion-del-conocimiento']) }}">Gestión del Conocimiento</a></li>
                            <li><a class="dropdown-item" href="{{ route('noticias.partes', ['categoria' => 'gestion-organizacional']) }}">Gestión Organizacional</a></li>
                            <li><a class="dropdown-item" href="{{ route('noticias.partes', ['categoria' => 'grupo-de-seguridad-y-salud-en-el-trabajo']) }}">Grupo de Seguridad y Salud en el Trabajo</a></li>
                            <li><a class="dropdown-item" href="{{ route('noticias.partes', ['categoria' => 'gestion-de-la-tecnologia']) }}">Gestión de la Tecnología</a></li>
                        </ul>
                    </li>

                    <!-- PUBLICACIONES -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('noticias.todas') }}">PUBLICACIONES</a>
                    </li>

                    <!-- BÚSQUEDA -->
                    <form class="d-flex" action="{{ route('noticias.todas') }}" method="GET">
                        <input class="form-control form-control-sm me-1 search-input" type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título o categoría">
                        <button class="btn search-btn btn-sm" type="submit"><i class="fas fa-search"></i></button>
                    </form>

                    <!-- MI CUENTA -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            MI CUENTA
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('perfil.index') }}">Perfil</a></li>

                            @auth
                            @if(Auth::user()->rol === 'SUPERADMIN')
                            <li><a class="dropdown-item" href="{{ route('usuarios.create') }}">Crear Cuentas</a></li>
                            @endif
                            @endauth

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <div class="container py-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
