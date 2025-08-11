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
        try {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'contenido' => 'required|string',
                'tipo' => 'required|string',
                'categoria' => 'required|string',
                'ruta_destino' => 'required|string',
                'fecha_documento' => 'nullable|date',
                'imagen' => 'nullable|image|max:4096',
            ]);

            $noticia = new Noticia();
            $noticia->titulo = $request->titulo;
            $noticia->contenido = $request->contenido;
            $noticia->tipo = $request->tipo;
            $noticia->categoria = $request->categoria;
            $noticia->ruta = $request->ruta_destino;
            $noticia->fecha_documento = $request->fecha_documento; // ← aquí guardamos la fecha

            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store($request->ruta_destino, 'public');
                $noticia->imagen = $path;
            }

            $noticia->save();

            return redirect()
                ->route('noticias.create', ['ruta' => $request->ruta_destino])
                ->with('success', '✅ Noticia creada correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->route('noticias.create', ['ruta' => $request->ruta_destino])
                ->with('error', '❌ Ocurrió un error al crear la noticia: ' . $e->getMessage());
        }
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
                ->orderBy('fecha_documento', 'desc')  // Ordena por fecha_documento DESC
                ->take(6)
                ->get();
        }

        return view('noticias.todas', compact('noticiasPorCategoria'));
    }

    
public function partes(Request $request)
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión primero');
    }

    $mapaCategorias = [
        'bienestar-y-desarrollo-institucional' => 'BIENESTAR Y DESARROLLO INSTITUCIONAL',
        'gestion-talento-humano' => 'GESTION TALENTO HUMANO',
        'gestion-del-conocimiento' => 'GESTION DEL CONOCIMIENTO',
        'gestion-organizacional' => 'GESTION ORGANIZACIONAL',
        'grupo-de-seguridad-y-salud-en-el-trabajo' => 'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO',
        'gestion-de-la-tecnologia' => 'GESTION DE LA TECNOLOGIA',
    ];

    // Primero vemos si viene parámetro ruta con la ruta completa (ejemplo: BIENESTAR Y DESARROLLO INSTITUCIONAL/sys)
    $rutaActual = $request->query('ruta');

    // Si no viene ruta, tomamos el slug categoría y lo convertimos
    if (!$rutaActual) {
        $categoriaSlug = $request->query('categoria', '');
        $rutaActual = $mapaCategorias[$categoriaSlug] ?? null;
        if (!$rutaActual) {
            abort(404, 'Categoría no válida');
        }
    }

    // Validar que la ruta comience con alguna de las categorías (por seguridad)
    $valido = false;
    foreach ($mapaCategorias as $slug => $categoriaNombre) {
        if (str_starts_with($rutaActual, $categoriaNombre)) {
            $valido = true;
            break;
        }
    }
    if (!$valido) {
        abort(404, 'Ruta no permitida');
    }

    // Ahora sí: obtener subcarpetas dentro de la ruta actual
    $subcarpetas = Storage::disk('public')->directories($rutaActual);

    // Obtener noticias dentro de esta ruta (puedes decidir si quieres incluir subcarpetas o solo exacta)
    $noticias = Noticia::where('ruta', $rutaActual)
        ->orderBy('fecha_documento', 'desc')
        ->get();

    return view('noticias.partes', compact('rutaActual', 'subcarpetas', 'noticias'));
}


    public function ver($id)
    {
        $noticia = Noticia::findOrFail($id);
        return view('noticias.ver', compact('noticia'));
    }

    public function verPorCategoria($categoria)
    {
        // Filtramos por categoría exacta
        $noticias = Noticia::where('categoria', $categoria)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('noticias.categoria', compact('categoria', 'noticias'));
    }

    public function filtrarPorCategoria($categoria)
    {
        // Filtra noticias que pertenecen a la categoría dada
        $noticias = Noticia::where('categoria', $categoria)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('noticias.categoria', compact('categoria', 'noticias'));
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
