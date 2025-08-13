<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $fillable = [
        'titulo',
        'contenido',
        'tipo',
        'ruta',
        'imagen',
        'categoria',
        'fecha_documento',
        'archivos', // 🔹 agregado para los archivos opcionales
    ];

    // 🔹 Importante: indicar que 'tipo' y 'archivos' son arrays
    protected $casts = [
        'tipo' => 'array',
        'archivos' => 'array',       // 🔹 agregado
        'fecha_documento' => 'date',
    ];
}
