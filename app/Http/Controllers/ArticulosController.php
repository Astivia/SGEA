<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\articulosAutores;
use App\Models\revisoresArticulos;
use App\Models\articulos;
use App\Models\usuarios;
use App\Models\eventos;
use App\Models\areas;

class ArticulosController extends Controller
{

    public function __construct(){
        $this->middleware('can:articulos.index')->only('index');
        $this->middleware('can:articulos.edit')->only('edit','update');
        $this->middleware('can:articulos.create')->only('create','store'); 
        $this->middleware('can:articulos.destroy')->only('destroy'); 
    }

    public function index($eventoId)
    {
        $evento = eventos::find($eventoId); 
        $Articulos = articulos::with(['evento', 'area', 'autores.usuario','revisores.usuario'])->where('evento_id', $evento->id)->OrderBy('id')->get();
        //Catalogo de Areas
        $Areas =areas::all();
        //Catalogo de Autores para el combo del Form "registrar Articulo"
        $Autores = articulosAutores::select('usuarios.id', 'usuarios.ap_paterno', 'usuarios.nombre', 'usuarios.ap_materno')
                    ->join('usuarios', 'articulos_autores.usuario_id', '=', 'usuarios.id')
                    ->groupBy('usuarios.id', 'usuarios.ap_paterno', 'usuarios.ap_materno','usuarios.nombre')->where('id','!=',1)
                    ->orderBy('usuarios.ap_paterno')
                    ->get();

        return view ('Articulos.index',compact('Articulos','Areas','Autores'));
    }

    public function store(Request $request){
        $evento= eventos::find($request->session()->get('eventoID'));
        if($evento){
            $datos=$request->all();
            if($request->has('pdf')){
                $area=areas::find($datos['area_id']);
                
                // manejo del archivo
                $archivo = $request->file('pdf');
                $nombreArchivo = $archivo->getClientOriginalName();
                try {
                    $archivo->storeAs('public/Lector/web/ArticulosporEvento/'.$evento->acronimo.$evento->edicion.
                                        '/'.$area->nombre.'/'.$datos['titulo'],$nombreArchivo);
                } catch (\Exception $e) {
                    return back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
                }
            }else{
                $nombreArchivo=null;
            }
            //insertamos datos del articulo
            $articulo = articulos::create([
                'evento_id'=>$evento->id,
                'titulo' => $datos['titulo'],
                'resumen' => $datos['resumen'],
                'archivo' => $nombreArchivo,
                'registro'=>getdate(),
                'area_id' => $datos['area_id'],
                'estado' => 'Recibido'
            ]);
            //Verificamos los autores del articulo
            if ($request->has('selected_authors')) {
                $selectedAuthors = json_decode($request->input('selected_authors'), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Recorrer el array de autores seleccionados
                    foreach ($selectedAuthors as $author) {
                        articulosAutores::create([
                            'evento_id'=>$evento->id,
                            'articulo_id'=>$articulo->id,
                            'usuario_id'=> $author['id'],
                            'correspondencia'=>$author['corresponding'],
                            'institucion'=>$author['institucion'],
                            'email'=>(usuarios::find($author['id']))->email
                        ]);
                    }
                } else {
                    echo "Error al decodificar Datos: ".json_last_error_msg();
                }
            }
            if($request->session()->get('rol') === "Autor"){
                return redirect ($evento->id.'_'.Auth::user()->id.'/MisArticulos')->with('success','Articulo Registrado');
            }else{
                return redirect ($evento->id.'/articulos')->with('success','Articulo Registrado');
            }
        }else{
            return redirect()->back()->with('error','No es posible agregar: el usuario no es parte del evento');
        }
    }

    
    public function show($evento_id, $id)
    {
        $articulo = articulos::where('evento_id', $evento_id)->where('id', $id)->first();
        if(!is_null($articulo->archivo)){
            $pdfPath = 'SGEA/storage/app/public/Lector/web/viewer.html?file=ArticulosporEvento/'.
                        $articulo->evento->acronimo.$articulo->evento->edicion.'/'.$articulo->area->nombre.'/'.$articulo->titulo.'/'.$articulo->archivo;
            $pdfUrl =  asset($pdfPath);
        }else{
            $pdfUrl=null;
        }
        $autores= articulosAutores::where('articulo_id',$id)->OrderBy('orden')->get();
        $revisores= revisoresArticulos::where('articulo_id',$articulo->id)->get();
        
        return view ('Articulos.read',compact('articulo','pdfUrl','autores','revisores'));
    }

    
    public function edit($evento_id, $id)
    {
        $articulo= articulos::where('evento_id', $evento_id)->where('id', $id)->first();
        if($articulo->estado === "Recibido"){
            
            $autores= articulosAutores::where('articulo_id',$articulo->id)->get();
        
            //catalogos
            $Areas = Areas::select('nombre','id')->get();
            $Autores= articulosAutores::distinct('usuario_id')->where('usuario_id','!=',1)->get();
            return view('Articulos.edit', compact('articulo', 'Areas','autores','Autores'));
        }else{
            return redirect()->back()->with('error','El articulo ya ha sido calificado o esta en revision');
        }

    }

    public function update(Request $request,$evento_id, $id)
    {
        $evento= eventos::find($request->session()->get('eventoID'));
        if($evento){
            $datos=$request->all();
            //buscamos el articulo a editar
            $articulo = articulos::where('evento_id', $evento_id)->where('id', $id)->first();
            // manejo del archivo
            if($request->has('archivo')){
                $ArchivoAntiguo = 'public/Lector/web/ArticulosporEvento/'.$articulo->evento->acronimo.$articulo->evento->edicion.'/'.
                                    $articulo->area->nombre.'/'.$articulo->titulo.'/'.$articulo->archivo;
                //verificamos si existe el archivo en nuestra carpeta destino 
                if (Storage::exists($ArchivoAntiguo)) {
                    Storage::delete($ArchivoAntiguo);
                }
                //Guardamos el Nuevo Archivo
                $archivo = $request->file('archivo');
                $nombreArchivo = $archivo->getClientOriginalName();
                try {
                    $archivo->storeAs('public/Lector/web/ArticulosporEvento/'.$articulo->evento->acronimo.$articulo->evento->edicion.'/'.
                                $articulo->area->nombre.'/'.$articulo->titulo , $nombreArchivo);
                } catch (\Exception $e) {
                    return back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
                }
            }else{
               $nombreArchivo=$articulo->archivo;
           }
            //insertamos en articulo
            $articulo->update([
                'titulo'=> $datos['titulo'],
                'resumen'=> $datos['resumen'],
                'area_id'=> $datos['area_id'],
                'estado' => $articulo['estado'],
                'archivo'=> $nombreArchivo
            ]);
    
            if ($request->has('selected_authors')) {
                $selectedAuthors = json_decode($request->input('selected_authors'), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Eliminar autores actuales
                    articulosAutores::where('articulo_id', $articulo->id)->where('evento_id',$articulo->evento->id)->delete();
                    foreach ($selectedAuthors as $author) {
                        articulosAutores::create([
                            'evento_id' => $evento_id,
                            'articulo_id' => $articulo->id,
                            'usuario_id' => $author['id'],
                            'correspondencia' => $author['corresponding'],
                            'institucion' => $author['institucion'],
                            'email' =>(usuarios::find($author['id']))->email
                        ]);
                    }
                } else {
                    echo "Error al decodificar Datos: ".json_last_error_msg();
                }
            }
            return redirect ($evento_id.'/articulos')->with('info','Informacion Actualizada');
        }else{
            return redirect()->back()->with('error','No es posible agregar: el usuario no es parte del evento');
        }
    }

    public function destroy(string $id)
    {
        // Buscar el artículo a eliminar
        $articulo = articulos::find($id);
        if (!$articulo){
            return redirect()->back()->with('error', 'No se encontro el articulo');
        }
        if($articulo->estado === "Recibido" ){
            try {
                articulosAutores::where('articulo_id', $articulo->id)->where('evento_id',$articulo->evento->id)->delete();
                if($articulo->archivo !== null){
                    // Eliminar la Ruta asociada
                    $pdfPath = 'public/Lector/web/ArticulosporEvento/'.$articulo->evento->acronimo.$articulo->evento->edicion.'/'.
                                $articulo->area->nombre.'/'.$articulo->titulo.'/'.$articulo->archivo;
                    $folderPath = 'public/Lector/web/ArticulosporEvento/' . $articulo->evento->acronimo . $articulo->evento->edicion . '/' .
                                    $articulo->area->nombre.'/'.$articulo->titulo;
    
                    if (Storage::exists($pdfPath)) {
                        Storage::delete($pdfPath);
                    }
                    // Verificar si la carpeta está vacía y eliminarla si es así
                    if (Storage::exists($folderPath) && count(Storage::files($folderPath)) === 0) {
                        Storage::deleteDirectory($folderPath);
                    }
                }
                $articulo->delete();
                return redirect()->back()->with('info', 'el artículo  se elimino correctamente');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }else{
            return redirect()->back()->with('error', 'El Articulo ya ha sido evaluado o se encuentra en revision');
        }
    }

    public function checkAuthor(Request $request)
     {
         $authorId = $request->input('author_id');
         $exists = articulosAutores::where('usuario_id', $authorId)->exists();
         $user = null;
         if (!$exists) {
             $user = usuarios::find($authorId);
         }else{
            $user =  articulosAutores::where('usuario_id', $authorId)->first();
         }
        return response()->json(['exists' => $exists, 'user' => $user]);
     }

     public function getArticles($area_id)
    {
        $articles = articulos::where('area_id', $area_id)->OrderBy('titulo')->get();
        return response()->json($articles);
    }
    
    public function deleteMultiple(Request $request){
        $ids = $request->ids;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                // Buscar el artículo a eliminar
                $articulo = articulos::find($id);
                if (!$articulo) {
                    return response()->json(['error' => "No se encontró el artículo con id: $id"], 404);
                }

                if ($articulo->estado !== "En revision") {
                    try {
                        articulosAutores::where('articulo_id', $articulo->id)->where('evento_id', $articulo->evento->id)->delete();

                        if ($articulo->archivo !== null) {
                            // Eliminar la Ruta asociada
                            $pdfPath = 'public/Lector/web/ArticulosporEvento/' . $articulo->evento->acronimo . $articulo->evento->edicion . '/' .
                                    $articulo->area->nombre . '/' . $articulo->titulo . '/' . $articulo->archivo;
                            $folderPath = 'public/Lector/web/ArticulosporEvento/' . $articulo->evento->acronimo . $articulo->evento->edicion . '/' .
                                        $articulo->area->nombre . '/' . $articulo->titulo;

                            if (Storage::exists($pdfPath)) {
                                Storage::delete($pdfPath);
                            }
                            // Verificar si la carpeta está vacía y eliminarla si es así
                            if (Storage::exists($folderPath) && count(Storage::files($folderPath)) === 0) {
                                Storage::deleteDirectory($folderPath);
                            }
                        }

                        $articulo->delete();
                    } catch (\Exception $e) {
                        return response()->json(['error' => "Error al eliminar el artículo con id: $id. Detalle: " . $e->getMessage()], 500);
                    }
                } else {
                    return response()->json(['error' => "El artículo con id: $id se encuentra en revisión"], 403);
                }
            }

            return response()->json(['success' => "Registros eliminados correctamente pppp."]);
        }

        return response()->json(['error' => "No se seleccionaron registros."], 400);
    }

    public function AuthorArticles($evento_id, $id){
        $Articulos = articulos::where('evento_id', $evento_id)
                    ->whereHas('autores', function($query) use ($id) {
                        $query->where('usuario_id', $id);
                    })
                    ->get();

        //Catalogos necesarios
        $Areas =areas::select('nombre','id')->get();
        $Autores = articulosAutores::select('usuarios.id', 'usuarios.ap_paterno', 'usuarios.nombre', 'usuarios.ap_materno')
                    ->join('usuarios', 'articulos_autores.usuario_id', '=', 'usuarios.id')
                    ->groupBy('usuarios.id', 'usuarios.ap_paterno', 'usuarios.ap_materno','usuarios.nombre')
                    ->orderBy('usuarios.ap_paterno')
                    ->get();

        return view ('Articulos.index',compact ('Articulos','Areas','Autores'));
    }

    public function Evaluations ($evento_id,$id){
        $articulos = revisoresArticulos::distinct('articulo_id')->where('evento_id', $evento_id)
                    ->whereNotNull('puntuacion')
                    ->whereHas('articulo.autores', function ($query) use ($id) {
                        $query->where('usuario_id', $id)
                            ->where('correspondencia', true);
                    })->get();
        
        return view('Revisores_Articulos.terminados',compact('articulos'));
        
    }

}
