<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Intranet Gobernación del Cauca</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .navbar { background-color: #004e2a; }
        .navbar-nav .nav-link { color: white !important; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #004e2a;">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand text-white" href="/">
            <img src="https://www.cauca.gov.co/SiteAssets/images/logo-encabezado.png" height="60" alt="Logo">
        </a>

        <!-- Botón para responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Ítems del menú alineados a la derecha -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/">INICIO</a>
                </li>

                <!-- SECRETARÍA GENERAL (Dropdown) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="secretariaDropdown" role="button" data-bs-toggle="dropdown">
                        SECRETARÍA GENERAL
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="secretariaDropdown">
                        <li><a class="dropdown-item" href="#">Talento Humano</a></li>
                        <li><a class="dropdown-item" href="#">Contratación</a></li>
                        <li><a class="dropdown-item" href="#">Archivo General</a></li>
                    </ul>
                </li>

                <li class="nav-item">
<a class="nav-link" href="{{ route('noticias.todas') }}">PUBLICACIONES</a>
                </li>

                <!-- BÚSQUEDA -->
                <li class="nav-item">
                    <form class="d-flex" action="#" method="GET">
                        <input class="form-control form-control-sm me-2" type="search" name="q" placeholder="Buscar..." aria-label="Buscar">
                        <button class="btn btn-outline-light btn-sm" type="submit">Buscar</button>
                    </form>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">TRÁMITES Y SERVICIOS</a>
                </li>

                <!-- MI CUENTA (Dropdown) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="cuentaDropdown" role="button" data-bs-toggle="dropdown">
                        MI CUENTA
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="cuentaDropdown">
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
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
