<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\revisoresArticulos;
use App\Models\articulosAutores;
use App\Models\usuarios;
use App\Models\eventos;
use App\Models\articulos;
use App\Models\areas;
use App\Models\participantes;
use Illuminate\Support\Str;

class RevisoresArticulosController extends Controller
{
    public function index($eventoId)
    {
        $articles = articulos::whereIn('id', function ($query) {
                $query->select('articulo_id')->from('revisores_articulos');
            })->with('revisores.usuario')->get();
        
        //catalogos 
        $articulos = articulos::select('id', 'titulo')->OrderBy('titulo')->get();
        $areas= areas::select('id', 'nombre')->OrderBy('nombre')->get();
        //usuarios que no sean autores
        // $usuarios=usuarios::where('id','!=',1)->whereNotIn('id', function($query) {
        //             $query->select('usuario_id')
        //             ->from('articulos_autores');
        //         })->get();
        //Todos los ususarios
        $usuarios=usuarios::select('nombre','ap_paterno','ap_materno','id')->where('id','!=',1)->get();

        return view ('Revisores_Articulos.index',compact('articulos','areas','usuarios','articles'));
    }

    public function store(Request $request)
    {
        $datos=$request->all();

        if ($request->has('revisores')) {
            $Revisores = json_decode($request->input('revisores'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Recorrer el array de autores seleccionados
                foreach ($Revisores  as $index => $revisor) {
                    if (!is_null($revisor['id'])){
                        $usu=usuarios::where('id',$revisor['id'])->first();
                        revisoresArticulos::create([
                            'evento_id'=>$request->session()->get('eventoID'),
                            'articulo_id'=> $request->input('articulo_id'),
                            'usuario_id'=> $usu->id,
                            'orden'=>  $index + 1 ,
                            // 'notificado'=>$this->NotificarUsuario($usu,$request->input('articulo_id'))
                            'notificado'=>true
                        ]);
                    }
                }
            } else {
                echo "Error al decodificar Datos: ".json_last_error_msg();
            }
            return redirect()->back()->with('success', 'Se ha Registrado correctamente');
        }
    }

    public function show(string $id)
    {
        $articulo=articulos::where('id',$id)->first();
        return view ('Revisores_Articulos.read',compact('articulo'));
    }

    public function edit($eventoId,$id)
    {
        $articulo=articulos::where('id',$id)->first();
        $revisores = revisoresArticulos::where('articulo_id',$id)->OrderBy('orden')->get();

        //catalogos 
        $usuarios=usuarios::where('estado',"alta,registrado")->get();

        return view('Revisores_Articulos.edit',compact('revisores','articulo','usuarios'));
    }

    public function update(Request $request, string $id)
    {
        $evento_id=$request->session()->get('eventoID');
        if ($request->has('revisores')) {
            $Revisors = json_decode($request->input('revisores'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Eliminar autores actuales
                revisoresArticulos::where('articulo_id', $id)->where('evento_id',$evento_id)->delete();
                foreach ($Revisors as $index =>$revisor) {
                    if (!is_null($revisor['id'])){
                            revisoresArticulos::create([
                            'evento_id' => $evento_id,
                            'articulo_id' => $id,
                            'usuario_id' => $revisor['id'],
                            'orden'=>  $index + 1 ,
                            'notificado'=>true
                        ]);
                    }
                }
            } else {
                echo "Error al decodificar Datos: ".json_last_error_msg();
            }
            return redirect ($evento_id.'/revisoresArticulos')->with('info','Informacion Actualizada');
        }else{
            $datos=$request->all();
            // manejo del archivo
            if($request->has('similitud')){
                $Revisor=revisoresArticulos::where('articulo_id',$id)->where('evento_id', $evento_id)->where('usuario_id',$datos['id_usuario'])->first();
                $archivo = $request->file('similitud');
                //generamos nuevo nombre
                $nombreArchivo = 'tntn-'.$Revisor->articulo->id.'.'.$archivo->getClientOriginalExtension();
                try {
                    $path = 'public/Lector/web/ArticulosporEvento/' . $Revisor->evento->acronimo . $Revisor->evento->edicion . '/' .
                            $Revisor->articulo->area->nombre . '/' . $Revisor->articulo->titulo;
                    if (!Storage::exists($path)) {Storage::makeDirectory($path);}
                    //almacenamos el PDF
                    $archivo->storeAs($path,$nombreArchivo);
                } catch (\Exception $e) {
                    return back()->with('error', 'Error al subir el archivo: ' . $e->getMessage());
                }
            }else{
                $nombreArchivo=null;
            }
            //Actualizar el registro
            \DB::table('revisores_articulos')
                ->where('evento_id', $evento_id)
                ->where('articulo_id', $id)
                ->where('usuario_id', $datos['id_usuario'])
                ->update([
                    'puntuacion' =>  $datos['puntuacion'],
                    'similitud' => $nombreArchivo,
                    'comentarios' => isset($datos['comentarios']) ? $datos['comentarios'] : null,
                ]);
            return redirect ($evento_id.'/ArticulosPendientes'.'/'.$datos['id_usuario'])->with('info','se ha calificado el Articulo correctamente');
        }
    }

    public function destroy($eventoId,$usuarioId,$articuloId)
    {
        $RevArt=revisores_articulos::where('evento_id',$eventoId)->where('usuario_id',$usuarioId)->where('articulo_id',$articuloId);
        if ($RevArt) {
            $RevArt->delete();
            return redirect()->back()->with('info', 'El revisor se eliminó del artículo');
        } else {
            return redirect()->back()->with('error', 'No se encontró el revisor para eliminar');
        }
    }

    private function NotificarUsuario(usuarios $user, $articuloId, $RevOaut){

        $articulo=articulos::find($articuloId);
        $evento=$articulo->evento->nombre.' ('.$articulo->evento->acronimo.' '.$articulo->evento->edicion.')';

        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Invalid email address'], 422);
        }
        
        $mail = new PHPMailer();
        try {
            // Configuracion de protocolo SMTP
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'astiviamax@gmail.com';
            $mail->Password = 'diulyvcniykrwacn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // definir Origen y destino
            $mail->setFrom('noreply@sgea.com', 'SGEA');
            $mail->addReplyTo('noreply@sgea.com', 'SGEA');
            $mail->addAddress("$user->email");
            
            //Definimos el contenido
            $mail->CharSet = 'UTF-8';
            $subject =  "Informacion Importante";
           
            $message = "Hola <strong>$user->nombre</strong>:\n
                            El proposito de este mensaje es informar que usted ha sido asignado como revisor para el articulo <strong>$articulo->titulo</strong> en el evento <strong>$evento</strong>\n\n
                            Atentamente:\n<strong>SGEA</strong>\n\n
                            <footer style='font-size:80%;'>Este mensaje es generado de forma automatica por lo que no requiere una respuesta</footer>";
            

            //Estructuramos el correo
            $mail->Subject = $subject; 
            $mail->Body = nl2br($message);
            $mail->isHTML(true);


            // enviamos el email
            if (!$mail->send()) {
                //NO SE ENVIO EL EMAIL
                return redirect()->back()->with('error', 'Error en el envio: '.$mail->ErrorInfo);
            } else{
                echo("<script>console.log('Se envio el correo a $user->email');</script>");
                return true;

            }
        } catch (Exception $e) {
            return false;
        }

    }

    public function pendientes($evento_id,$usuarioId){
        $articulos = articulos::whereHas('revisores', function ($query) use ($usuarioId) {
            $query->where('usuario_id', $usuarioId)
                  ->whereNull('puntuacion');
        })->with('autores.usuario')->get();

        return view ('Revisores_Articulos.pendientes',compact('articulos'));
    }

    public function revision($eventoID,$articuloID){
        $articulo = articulos::where('evento_id',$eventoID)->where('id',$articuloID)->first();
        if(!is_null($articulo->archivo)){
            $pdfPath = 'SGEA/storage/app/public/Lector/web/viewer.html?file=ArticulosporEvento/'.$articulo->evento->acronimo.$articulo->evento->edicion.'/'.
                        $articulo->area->nombre.'/'.$articulo->titulo.'/'.$articulo->archivo;
            $pdfUrl =  asset($pdfPath);
        }else{
            $pdfUrl=null;
        }
        $autores= articulosAutores::where('articulo_id',$articuloID)->OrderBy('orden')->get();

        return view ('Revisores_Articulos.revision',compact('articulo','pdfUrl','autores'));
    }

    public function revisados($eventoID,$usuarioId){
        $articulos= revisoresArticulos::where('evento_id',$eventoID)->where('usuario_id',$usuarioId)->where('puntuacion','!=',null)->get();

        return view('Revisores_Articulos.terminados',compact('articulos'));
    }
}