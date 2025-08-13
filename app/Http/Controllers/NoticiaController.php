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

        // 🔹 Solo las últimas 3 noticias con tipo "banner"
        $recientes = Noticia::whereJsonContains('tipo', 'banner')
            ->latest()
            ->take(3)
            ->get();

        // Otras noticias (puedes decidir si excluir las que son banner)
        $otras = Noticia::latest()->skip(3)->take(6)->get();

        return view('home', [
            'noticiasPorCategoria' => $noticias,
            'recientes' => $recientes,
            'otras' => $otras
        ]);
    }


    public function create(Request $request)
    {
        // ❌ Se elimina la restricción de rol para permitir acceso a todos los usuarios

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
        'categoria' => 'required|string',
        'fecha_documento' => 'nullable|date',
        'tipo' => 'nullable|array',
        'imagen' => 'nullable|image|max:4096',
        'archivos.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,mp3,mp4,wav,avi|max:10240',
    ]);

    $noticia = new Noticia();
    $noticia->titulo = $request->titulo;
    $noticia->contenido = $request->contenido;
    $noticia->tipo = $request->tipo ?? [];
    $noticia->categoria = $request->categoria;
    $noticia->ruta = $request->ruta_destino;
    $noticia->fecha_documento = $request->fecha_documento;

    if ($request->hasFile('imagen')) {
        $noticia->imagen = $request->file('imagen')->store($request->ruta_destino, 'public');
    }

    $noticia->save();

    // Archivos opcionales
    if ($request->hasFile('archivos')) {
        $archivosArray = [];
        foreach ($request->file('archivos') as $archivo) {
            $path = $archivo->store($request->ruta_destino, 'public');
            $archivosArray[] = $path;
        }
        $noticia->archivos = $archivosArray;
        $noticia->save();
    }

    return redirect()
        ->route('noticias.create', ['ruta' => $request->ruta_destino])
        ->with('success', '✅ Noticia creada correctamente.');
}



    public function todas(Request $request)
{
    $search = $request->input('search'); // Texto del buscador

    $noticiasPorCategoria = [];

    // Obtenemos todas las noticias que coincidan con la búsqueda (o todas si no hay búsqueda)
    $noticias = Noticia::query();

    if ($search) {
        $noticias->where(function ($q) use ($search) {
            $q->where('titulo', 'LIKE', "%{$search}%")
              ->orWhere('categoria', 'LIKE', "%{$search}%");
        });
    }

    $noticias = $noticias->orderBy('fecha_documento', 'desc')->get();

    // Agrupamos por categoría, solo las que tengan noticias
    $noticiasPorCategoria = $noticias->groupBy('categoria');

    return view('noticias.todas', compact('noticiasPorCategoria', 'search'));
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

    public function edit($id)
    {
        $user = auth()->user();
        $noticia = Noticia::findOrFail($id);

        // Mapear roles a carpetas
        $rolCarpeta = [
            'BIENESTAR Y DESARROLLO INSTITUCIONAL' => 'BIENESTAR Y DESARROLLO INSTITUCIONAL',
            'TALENTO_HUMANO' => 'GESTION TALENTO HUMANO',
            'CONOCIMIENTO' => 'GESTION DEL CONOCIMIENTO',
            'ORGANIZACIONAL' => 'GESTION ORGANIZACIONAL',
            'SEGURIDAD_SALUD' => 'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO',
            'TECNOLOGIA' => 'GESTION DE LA TECNOLOGIA',
            'SUPERADMIN' => null
        ];

        $categoriaPermitida = $rolCarpeta[$user->rol] ?? null;

        // Permitir si es SUPERADMIN o si la noticia pertenece a su carpeta o subcarpeta
        if (
            $user->rol !== 'SUPERADMIN' &&
            !($categoriaPermitida && str_starts_with($noticia->categoria, $categoriaPermitida))
        ) {
            abort(403, 'No tienes permisos para editar esta noticia.');
        }

        $secciones = array_values($rolCarpeta);
        unset($secciones[array_search(null, $secciones)]); // quitar null de SUPERADMIN

        return view('noticias.edit', compact('noticia', 'secciones'));
    }

   public function update(Request $request, $id)
{
    $noticia = Noticia::findOrFail($id);

    $request->validate([
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'categoria' => 'required|string',
        'fecha_documento' => 'nullable|date',
        'tipo' => 'nullable|array',
        'imagen' => 'nullable|image|max:4096',
        'archivos.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,mp3,mp4,wav,avi|max:10240',
    ]);

    $categoriaAnterior = $noticia->categoria;
    $noticia->titulo = $request->titulo;
    $noticia->contenido = $request->contenido;
    $noticia->tipo = $request->tipo ?? [];
    $noticia->categoria = $request->categoria;
    $noticia->ruta = $request->categoria;
    $noticia->fecha_documento = $request->fecha_documento;

    // Imagen
    if ($request->hasFile('imagen')) {
        if ($noticia->imagen && Storage::disk('public')->exists($noticia->imagen)) {
            Storage::disk('public')->delete($noticia->imagen);
        }
        $noticia->imagen = $request->file('imagen')->store($request->categoria, 'public');
    } elseif ($noticia->imagen && $categoriaAnterior !== $request->categoria) {
        $nuevoPath = $request->categoria . '/' . basename($noticia->imagen);
        Storage::disk('public')->move($noticia->imagen, $nuevoPath);
        $noticia->imagen = $nuevoPath;
    }

    // Archivos opcionales
    $archivosExistentes = $noticia->archivos ?? [];
    if ($request->hasFile('archivos')) {
        foreach ($request->file('archivos') as $archivo) {
            $path = $archivo->store($request->categoria, 'public');
            $archivosExistentes[] = $path;
        }
    }

    // Mover archivos si cambió categoría
    if ($categoriaAnterior !== $request->categoria && !empty($archivosExistentes)) {
        $archivosMovidos = [];
        foreach ($archivosExistentes as $archivo) {
            $nuevoPath = $request->categoria . '/' . basename($archivo);
            if (Storage::disk('public')->exists($archivo)) {
                Storage::disk('public')->move($archivo, $nuevoPath);
            }
            $archivosMovidos[] = $nuevoPath;
        }
        $noticia->archivos = $archivosMovidos;
    } else {
        $noticia->archivos = $archivosExistentes;
    }

    $noticia->save();

    return redirect()->route('noticias.admin')->with('success', '✅ Noticia actualizada correctamente.');
}



    public function destroy($id)
    {
        $user = auth()->user();
        $noticia = Noticia::findOrFail($id);

        $rolCarpeta = [
            'BIENESTAR Y DESARROLLO INSTITUCIONAL' => 'BIENESTAR Y DESARROLLO INSTITUCIONAL',
            'TALENTO_HUMANO' => 'GESTION TALENTO HUMANO',
            'CONOCIMIENTO' => 'GESTION DEL CONOCIMIENTO',
            'ORGANIZACIONAL' => 'GESTION ORGANIZACIONAL',
            'SEGURIDAD_SALUD' => 'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO',
            'TECNOLOGIA' => 'GESTION DE LA TECNOLOGIA',
            'SUPERADMIN' => null
        ];
        $categoriaPermitida = $rolCarpeta[$user->rol] ?? null;

        if (
            $user->rol !== 'SUPERADMIN' &&
            !($categoriaPermitida && str_starts_with($noticia->categoria, $categoriaPermitida))
        ) {
            abort(403, 'No tienes permisos para eliminar esta noticia.');
        }

        $noticia->delete();

        return redirect()->route('noticias.admin')->with('success', 'Noticia eliminada correctamente.');
    }

    public function admin(Request $request)
    {
        if (!auth()->check()) {
            abort(403, 'Debes iniciar sesión.');
        }

        $user = auth()->user();
        $search = $request->get('search');

        // Mapear roles a carpetas/categorías
        $rolCarpeta = [
            'BIENESTAR Y DESARROLLO INSTITUCIONAL' => 'BIENESTAR Y DESARROLLO INSTITUCIONAL',
            'TALENTO_HUMANO' => 'GESTION TALENTO HUMANO',
            'CONOCIMIENTO' => 'GESTION DEL CONOCIMIENTO',
            'ORGANIZACIONAL' => 'GESTION ORGANIZACIONAL',
            'SEGURIDAD_SALUD' => 'GRUPO DE SEGURIDAD Y SALUD EN EL TRABAJO',
            'TECNOLOGIA' => 'GESTION DE LA TECNOLOGIA',
            'SUPERADMIN' => null // Sin restricción
        ];

        $categoriaPermitida = $rolCarpeta[$user->rol] ?? null;

        $noticias = Noticia::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%$search%")
                    ->orWhere('categoria', 'like', "%$search%");
            });
        })
            ->when($user->rol !== 'SUPERADMIN', function ($query) use ($categoriaPermitida) {
                // Filtrar también subcarpetas
                $query->where('categoria', 'LIKE', $categoriaPermitida . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('noticias.index', compact('noticias', 'search'));
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
