<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\articulosAutores;
use App\Models\articulos;
use App\Models\usuarios;
use App\Models\eventos;
use App\Models\areas;

class ArticulosController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     

    public function index()
    {

        $Articulos = articulos::with(['evento', 'area', 'autores.usuario'])->OrderBy('id')->get();
        
        //Catalogo de Areas
        $Areas =areas::all();
        //Catalogo de Autores para el combo del Form "registrar Articulo"
        $Autores=articulosAutores::distinct('usuario_id')->get();
        
        return view ('Articulos.index',compact('Articulos','Areas','Autores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $evento= eventos::find($request->session()->get('eventoID'));

        if($evento){
            $datos=$request->all();
            
            //validamos la carga del archivo
            $request->validate([
                'pdf' => 'required|file|mimetypes:application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]);
            // manejo del archivo
            $archivo = $request->file('pdf');
            $nombreArchivo = $archivo->getClientOriginalName();
            $rutaArchivo = storage_path('app/public/Articles/web/'.$evento->acronimo.$evento->edicion.'/'.$nombreArchivo);
    
            // Guardamos el archivo con su nombre original y obtenemos la ruta completa
            $rutaCompletaArchivo = $archivo->storeAs('public/Articles/web/' .$evento->acronimo.$evento->edicion, $nombreArchivo);
    
            //insertamoslos datos del articulo
            $articulo = articulos::create([
                'evento_id'=>$evento->id,
                'titulo' => $datos['titulo'],
                'resumen' => $datos['resumen'],
                'archivo' => $nombreArchivo,
                'registro'=>getdate(),
                'area_id' => $datos['area_id'],
                'estado' => 'Recibido'
            ]);
    
            if ($request->has('selected_authors')) {
                $selectedAuthors = json_decode($request->input('selected_authors'), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Recorrer el array de autores seleccionados
                    foreach ($selectedAuthors as $authorId) {
                        articulosAutores::create([
                            'evento_id'=>$evento->id,
                            'articulo_id'=>$articulo->id,
                            'usuario_id'=> $authorId,
                            'correspondencia'=>false,
                            'institucion'=>'ITTOL',
                            'email'=>(usuarios::find($authorId))->email
                        ]);
                    }
                } else {
                    echo "Error al decodificar Datos: " . json_last_error_msg();
                }
            }
    
            return redirect ('/articulos');
        }else{
            return redirect()->back()->with('error','No es posible insertar: el usuario no es parte de ningun evento');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show($evento_id, $id)
    {
        $articulo = articulos::where('evento_id', $evento_id)->where('id', $id)->first();
        if(!is_null($articulo->archivo)){
            $pdfPath = 'SGEA/storage/app/public/Articles/web/viewer.html?file='.$articulo->evento->acronimo.$articulo->evento->edicion.'/'.$articulo->archivo;
            $pdfUrl =  asset($pdfPath);
        }else{
            $pdfUrl=null;
        }
        $autores= articulosAutores::where('articulo_id',$id)->get();

        return view ('Articulos.read',compact('articulo','pdfUrl','autores'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($evento_id, $id)
    {
        
        $articulo= articulos::where('evento_id', $evento_id)->where('id', $id)->first();
    
        //consultamos los catalogos
        $Areas = Areas::all();
        
        return view('Articulos.edit', compact('articulo', 'Areas'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$evento_id, $id)
    {

        // Validación de los datos de entrada
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:200',
            'resumen' => 'nullable|string',
            'area_id' => 'required|exists:areas,id',
            'estado' => 'required|string|max:15',
        ]);
        //buscamos el articulo
        $articulo = articulos::where('evento_id', $evento_id)->where('id', $id)->firstOrFail();

        // manejo del archivo
        $archivo = $request->file('archivo');
         
        if($archivo){
           $pdfPath = 'public/Articles/web/' . $articulo->evento->acronimo.$articulo->evento->edicion. '/' . $articulo->archivo;
           //verificamos ai existe el archivo en nuestra carpeta destino 
           if (Storage::exists($pdfPath)) {
               Storage::delete($pdfPath);
           }
            $nombreArchivo = $archivo->getClientOriginalName();
            $rutaArchivo = storage_path('app/public/Articles/web/' . $articulo->evento->acronimo.$articulo->evento->edicion. '/' . $nombreArchivo);
            // Guardamos el archivo con su nombre original y obtenemos la ruta completa
            $rutaCompletaArchivo = $archivo->storeAs('public/Articles/web/' . $articulo->evento->acronimo.$articulo->evento->edicion, $nombreArchivo);
        }else{
           $nombreArchivo=$articulo->archivo;
       }
        //insertamos en articulo
        $articulo->update([
            'titulo'=> $validatedData['titulo'],
            'resumen'=> $validatedData['resumen'],
            'area_id'=> $validatedData['area_id'],
            'estado'=> $validatedData['estado'],
            'archivo'=> $nombreArchivo
        ]);
        return redirect('/articulos')->with('success', 'Artículo Modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el artículo a eliminar
        $articulo = articulos::find($id);
        if (!$articulo){
            return redirect()->back()->with('error', 'No se encontro el articulo');
        }elseif($articulo->revisores()->count()>0){
            return redirect()->back()->with('error', 'El articulo aun tiene revisores');
        }

        

        // Eliminar el archivo PDF asociado
        $pdfPath = 'public/Articles/web/' . $articulo->evento->acronimo.$articulo->evento->edicion . '/' . $articulo->archivo;
        if (Storage::exists($pdfPath)) {
            Storage::delete($pdfPath);
        }

        try {
            $articulo->delete();
            return redirect()->back()->with('success', 'el artículo  se elimino correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function checkAuthor(Request $request)
     {
         $authorId = $request->input('author_id');
         $exists = articulosAutores::where('usuario_id', $authorId)->exists();
         $user = null;
         if (!$exists) {
             $user = usuarios::find($authorId);
         }
        return response()->json(['exists' => $exists, 'user' => $user]);
     }
}
