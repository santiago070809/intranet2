<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerfilController;


// RUTAS DE AUTENTICACIÓN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// RUTAS PROTEGIDAS POR SESIÓN
Route::middleware(['checkSession'])->group(function () {


    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::post('/perfil/cambiar-password', [PerfilController::class, 'cambiarPassword'])->name('perfil.cambiarPassword');

    Route::get('/', [NoticiaController::class, 'inicio'])->name('home');

    // CRUD de noticias
    Route::get('/noticias', [NoticiaController::class, 'todas'])->name('noticias.todas');
    Route::get('/noticias/create', [NoticiaController::class, 'create'])->name('noticias.create');
    Route::post('/noticias/store', [NoticiaController::class, 'store'])->name('noticias.store');
    Route::get('/noticias/partes', action: [NoticiaController::class, 'partes'])->name('noticias.partes');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/noticias/admin', [NoticiaController::class, 'admin'])->name('noticias.admin');
    Route::get('/noticias/{id}/edit', [NoticiaController::class, 'edit'])->name('noticias.edit');
    Route::put('/noticias/{id}', [NoticiaController::class, 'update'])->name('noticias.update');
    Route::delete('/noticias/{id}', [NoticiaController::class, 'destroy'])->name('noticias.destroy');

    // Carpeta y archivo
    Route::post('/noticias/crear-carpeta', [NoticiaController::class, 'crearCarpeta'])->name('noticias.crearCarpeta');
    Route::post('/noticias/subir-archivo', [NoticiaController::class, 'subirArchivo'])->name('noticias.subirArchivo');
    Route::get('/noticias/{id}', [NoticiaController::class, 'ver'])->name('noticias.ver');

    // Ruta para filtrar por categoría
    Route::get('/noticias/categoria/{categoria}', [NoticiaController::class, 'filtrarPorCategoria'])->name('noticias.filtrarPorCategoria');
    // routes/web.php


    // Ruta para ver noticias por categoría
    Route::get('/noticias/categoria/{categoria}', [NoticiaController::class, 'porCategoria'])
        ->name('noticias.categoria');
    // Ruta para filtrar noticias por categoría
    Route::get('/noticias/categoria/{categoria}', [NoticiaController::class, 'filtrarPorCategoria'])
        ->name('noticias.filtrarPorCategoria');
});
