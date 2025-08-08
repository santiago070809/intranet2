<?php

// app/Models/Noticia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $fillable = [
        'titulo',
        'contenido',
        'tipo',
        'ruta',
        'imagen'
    ];
}
