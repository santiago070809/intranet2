<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticia;
use Illuminate\Support\Facades\Storage;

class NoticiaController extends Controller
{
    public function inicio()
    {
        $noticias = Noticia::all()->groupBy('categoria');
        $recientes = Noticia::latest()->take(3)->get();
        $otras = Noticia::latest()->skip(3)->take(6)->get();

        return view('home', [
            'noticiasPorCategoria' => $noticias,
            'recientes' => $recientes,
            'otras' => $otras
        ]);
    }

    public function create(Request $request)
    {
        // Solo permitir acceso a SUPERADMIN
        $user = auth()->user();
        if (!$user || $user->rol !== 'SUPERADMIN') {
            abort(403, 'Acceso denegado. Solo el SUPERADMIN puede crear noticias.');
        }

        $ruta = $request->get('ruta', '/');
        $rutas = Storage::disk('public')->directories($ruta);
        $archivos = Storage::disk('public')->files($ruta);

        $secciones = [
            'BIENESTAR Y DESARROLLO INSTITUCIONAL',
            'GESTION TALENTO HUMANO',
            'GESTION DEL CONOCIMIENTO',
            'GESTION ORGANIZACIONAL',
            'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO',
            'GESTION DE LA TECNOLOGIA',
        ];

        return view('noticias.create', compact('ruta', 'rutas', 'archivos', 'secciones'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|string',
            'ruta_destino' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $noticia = new Noticia();
        $noticia->titulo = $request->titulo;
        $noticia->contenido = $request->contenido;
        $noticia->tipo = $request->tipo;
        $noticia->ruta = trim($request->ruta_destino, '/');

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store($noticia->ruta, 'public');
            $noticia->imagen = $path; // Guarda: "categoria/nombre_imagen.jpg"
        }

        $noticia->save();

        return redirect()->route('noticias.todas')->with('success', 'Noticia guardada correctamente.');
    }

    public function todas()
    {
        $categorias = [
            'BIENESTAR Y DESARROLLO INSTITUCIONAL',
            'GESTION TALENTO HUMANO',
            'GESTION DEL CONOCIMIENTO',
            'GESTION ORGANIZACIONAL',
            'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO',
            'GESTION DE LA TECNOLOGIA',
        ];

        $noticiasPorCategoria = [];

        foreach ($categorias as $categoria) {
            $noticiasPorCategoria[$categoria] = Noticia::where('ruta', 'LIKE', "$categoria%")
                ->latest()
                ->take(6)
                ->get();
        }

        return view('noticias.todas', compact('noticiasPorCategoria'));
    }
    public function ver($id)
{
    $noticia = Noticia::findOrFail($id);
    return view('noticias.ver', compact('noticia'));
}

   


    public function crearCarpeta(Request $request)
    {
        $request->validate([
            'ruta_base' => 'required|string',
            'nombre_carpeta' => 'required|string|max:255',
        ]);

        $ruta = trim($request->ruta_base, '/') . '/' . trim($request->nombre_carpeta, '/');
        Storage::disk('public')->makeDirectory($ruta);

        return redirect()->back()->with('success', 'Carpeta creada correctamente.');
    }

    public function subirArchivo(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file',
            'ruta_destino' => 'required|string',
        ]);

        $ruta = trim($request->ruta_destino, '/');
        Storage::disk('public')->putFile($ruta, $request->file('archivo'));

        return redirect()->back()->with('success', 'Archivo subido correctamente.');
    }
}
