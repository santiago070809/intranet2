<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoticiaController;

// RUTAS DE AUTENTICACIÓN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// RUTAS PROTEGIDAS POR SESIÓN
Route::middleware(['checkSession'])->group(function () {


Route::get('/', [NoticiaController::class, 'inicio'])->name('home');

    // CRUD de noticias
    Route::get('/noticias', [NoticiaController::class, 'todas'])->name('noticias.todas');
    Route::get('/noticias/create', [NoticiaController::class, 'create'])->name('noticias.create');
    Route::post('/noticias/store', [NoticiaController::class, 'store'])->name('noticias.store');

    // Carpeta y archivo
    Route::post('/noticias/crear-carpeta', [NoticiaController::class, 'crearCarpeta'])->name('noticias.crearCarpeta');
    Route::post('/noticias/subir-archivo', [NoticiaController::class, 'subirArchivo'])->name('noticias.subirArchivo');
    Route::get('/noticias/{id}', [NoticiaController::class, 'ver'])->name('noticias.ver');

    // Ruta para filtrar por categoría
    Route::get('/noticias/categoria/{categoria}', [NoticiaController::class, 'filtrarPorCategoria'])->name('noticias.filtrarPorCategoria');

});
