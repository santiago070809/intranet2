<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;

class HomeController extends Controller
{

public function index()
{
    // Obtener las 3 noticias más recientes
    $ultimas = Noticia::latest()->take(3)->get();

    // Obtener las otras noticias (excepto esas 3)
    $otras = Noticia::latest()->skip(3)->take(10)->get();

    // Retornar la vista con ambas variables
    return view('home', compact('ultimas', 'otras'));
}

}
