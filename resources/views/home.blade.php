<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Intranet Gobernación del Cauca</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
          #logo-intranet {
        height: 70% !important;   /* Aumenta el tamaño del logo */
        width: auto !important;
        object-fit: contain;
    }

    .navbar-brand {
        padding: 0 !important;
    }
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background-color: #004e2a;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .navbar-nav .nav-link {
            color: #fff !important;
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

        .section-buttons {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .section-buttons .btn {
            width: 100%;
            margin-bottom: 15px;
            font-size: 1rem;
            font-weight: 500;
        }

        .card-noticia {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }
      .circulo-icono {
    width: 70px;
    height: 70px;
    background-color: #004e2a;
    color: #fff;
    font-size: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none; /* Asegura que no haya borde */
    box-shadow: none; /* Asegura que no haya sombra */
}

/* Si el ícono tenía un borde inferior o línea accidental */
.circulo-icono i {
    border: none;
    box-shadow: none;
    display: inline-block;
}

.icon-link {
    text-decoration: none !important;
    border: none !important;
}


    </style>
</head>
<body>
@if(session()->has('user'))
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
    <img src="https://intranet.cauca.gov.co/images/logo-gobernacion-cauca.png" alt="Logo Gobernación del Cauca" id="logo-intranet">
</a>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">

                <li class="nav-item">
                    <a class="nav-link" href="/">INICIO</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        SECRETARÍA GENERAL
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Talento Humano</a></li>
                        <li><a class="dropdown-item" href="#">Contratación</a></li>
                        <li><a class="dropdown-item" href="#">Archivo General</a></li>
                    </ul>
                </li>

                <li class="nav-item">
<a class="nav-link" href="{{ route('noticias.todas') }}">PUBLICACIONES</a>
                </li>

                <li class="nav-item">
                    <form class="d-flex" action="#" method="GET">
                        <input class="form-control form-control-sm me-1 search-input" type="search" name="q" placeholder="Buscar...">
                        <button class="btn search-btn btn-sm" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">TRÁMITES Y SERVICIOS</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        MI CUENTA
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                    
                        <li><hr class="dropdown-divider"></li>
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
<div class="container mt-4">

    <!-- CARRUSEL DE NOTICIAS RECIENTES -->
    @if($recientes->count())
    <div id="carruselNoticias" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($recientes as $index => $noticia)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    @if($noticia->imagen)
                        <img src="{{ asset('storage/'.$noticia->imagen) }}" class="d-block w-100" style="height: 400px; object-fit: cover;">
                    @endif
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $noticia->titulo }}</h5>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carruselNoticias" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carruselNoticias" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    @endif

    <!-- BOTONES GRANDES --><!-- SERVICIO CIUDADANO -->
<!-- SERVICIO CIUDADANO -->
<div class="text-center mt-5 mb-4">
    <h4 class="fw-bold">SECRETARIA GENERAL</h4>
</div>

<div class="row text-center justify-content-center mb-5">

    <!-- BOTÓN 1 -->
    <div class="col-6 col-md-2 mb-3">
        <a href="{{ route('noticias.create', ['categoria' => 'BIENESTAR Y DESARROLLO INSTITUCIONAL']) }}" class="icon-link">
            <div class="d-flex justify-content-center">
                <i class="bi bi-people-fill circulo-icono"></i>
            </div>
        </a>
        <div class="mt-2 text-dark">
            BIENESTAR Y DESARROLLO INSTITUCIONAL<br>
            <small><a href="#" class="text-decoration-none">Más Información</a></small>
        </div>
    </div>

    <!-- BOTÓN 2 -->
    <div class="col-6 col-md-2 mb-3">
        <a href="{{ route('noticias.create', ['categoria' => 'GESTION TALENTO HUMANO']) }}" class="icon-link">
            <div class="d-flex justify-content-center">
                <i class="bi bi-briefcase-fill circulo-icono"></i>
            </div>
        </a>
        <div class="mt-2 text-dark">
            GESTION TALENTO HUMANO<br>
            <small><a href="#" class="text-decoration-none">Más Información</a></small>
        </div>
    </div>

    <!-- BOTÓN 3 -->
    <div class="col-6 col-md-2 mb-3">
        <a href="{{ route('noticias.create', ['categoria' => 'GESTION DEL CONOCIMIENTO']) }}" class="icon-link">
            <div class="d-flex justify-content-center">
                <i class="bi bi-file-earmark-text-fill circulo-icono"></i>
            </div>
        </a>
        <div class="mt-2 text-dark">
            GESTION DEL CONOCIMIENTO<br>
            <small><a href="#" class="text-decoration-none">Más Información</a></small>
        </div>
    </div>

    <!-- BOTÓN 4 -->
    <div class="col-6 col-md-2 mb-3">
        <a href="{{ route('noticias.create', ['categoria' => 'GESTION ORGANIZACIONAL']) }}" class="icon-link">
            <div class="d-flex justify-content-center">
                <i class="bi bi-folder-fill circulo-icono"></i>
            </div>
        </a>
        <div class="mt-2 text-dark">
            GESTION ORGANIZACIONAL<br>
            <small><a href="#" class="text-decoration-none">Más Información</a></small>
        </div>
    </div>

    <!-- BOTÓN 5 -->
    <div class="col-6 col-md-2 mb-3">
        <a href="{{ route('noticias.create', ['categoria' => 'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO']) }}" class="icon-link">
            <div class="d-flex justify-content-center">
                <i class="bi bi-database-fill circulo-icono"></i>
            </div>
        </a>
        <div class="mt-2 text-dark">
            GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO<br>
            <small><a href="#" class="text-decoration-none">Más Información</a></small>
        </div>
    </div>

    <!-- BOTÓN 6 -->
    <div class="col-6 col-md-2 mb-3">
        <a href="{{ route('noticias.create', ['categoria' => 'GESTION DE LA TECNOLOGIA']) }}" class="icon-link">
            <div class="d-flex justify-content-center">
                <i class="bi bi-database-fill circulo-icono"></i>
            </div>
        </a>
        <div class="mt-2 text-dark">
            GESTION DE LA TECNOLOGIA<br>
            <small><a href="#" class="text-decoration-none">Más Información</a></small>
        </div>
    </div>

</div>



    <!-- NOTICIAS VARIAS -->
    <div class="text-center mt-5 mb-4">
        <h4>Noticias Varias</h4>
        <div class="row">
            @foreach($otras as $noticia)
            <div class="col-md-4 mb-4">
                <div class="card card-noticia h-100">
                    @if($noticia->imagen)
                        <img src="{{ asset('storage/'.$noticia->imagen) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $noticia->titulo }}</h5>
                        <p class="card-text">{{ Str::limit($noticia->contenido, 100) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
<!-- PIE DE PÁGINA -->
<footer class="text-white mt-5 pt-4 pb-3" style="background-color: #004e2a;">
    <div class="container">
        <div class="row">

            <!-- Logo y descripción -->
            <div class="col-md-4 mb-3">
                <img src="https://www.cauca.gov.co/SiteAssets/images/logo-encabezado.png" alt="Logo Cauca" height="60">
                <p class="mt-2 small">
                    Gobernación del Cauca<br>
                    Comprometidos con la transparencia y el servicio ciudadano.
                </p>
            </div>

            <!-- Enlaces útiles -->
            <div class="col-md-4 mb-3">
                <h6 class="fw-bold">Enlaces útiles</h6>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-white text-decoration-none">Transparencia</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Trámites y Servicios</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Atención al Ciudadano</a></li>
                    <li><a href="#" class="text-white text-decoration-none">PQRSDF</a></li>
                </ul>
            </div>

            <!-- Redes sociales -->
            <div class="col-md-4 mb-3">
                <h6 class="fw-bold">Síguenos</h6>
                <a href="#" class="text-white me-3 fs-5"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="text-white me-3 fs-5"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-white fs-5"><i class="bi bi-youtube"></i></a>
            </div>
        </div>

        <div class="text-center mt-3 small">
            © {{ date('Y') }} Gobernación del Cauca. Todos los derechos reservados.
        </div>
    </div>
</footer>


<!-- Scripts Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
@endif
</html>
