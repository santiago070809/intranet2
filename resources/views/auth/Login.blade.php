<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Intranet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f8;
        }

        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        }

        .login-title {
            text-align: center;
            margin-bottom: 20px;
            color: #004e2a;
            font-weight: bold;
        }

        .btn-green {
            background-color: #004e2a;
            color: white;
        }

        .btn-green:hover {
            background-color: #00753a;
        }

        .logo-login {
            display: block;
            margin: 0 auto 20px;
            width: 100px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <img src="https://intranet.cauca.gov.co/images/logo-gobernacion-cauca.png" class="logo-login" alt="Logo">
    <h3 class="login-title">Iniciar Sesión</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo institucional</label>
            <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-green">Ingresar</button>
        </div>
    </form>
</div>
</body>
</html>
