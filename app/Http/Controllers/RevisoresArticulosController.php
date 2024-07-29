<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
use App\Models\revisoresArticulos;
use App\Models\usuarios;
use App\Models\eventos;
use App\Models\articulos;
use App\Models\areas;
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
        $usuarios=usuarios::where('estado',"alta,registrado")->get();
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
                            'orden'=>  $index + 1 
                        ]);
                        $this->NotificarUsuario($usu,$request->input('articulo_id'));
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

        $datos=$request->all();

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
                            'orden'=>  $index + 1 
                        ]);
                    }
                }
            } else {
                echo "Error al decodificar Datos: ".json_last_error_msg();
            }
        }

        return redirect ($evento_id.'/revisoresArticulos')->with('info','Informacion Actualizada');
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

    private function NotificarUsuario(usuarios $user, $articuloId){

        $articulo=articulos::find($articuloId);

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
        
            // Set sender and recipient information
            $mail->setFrom('noreply@sgea.com', 'SGEA');
            $mail->addAddress("$user->email");
            
            
            //Definimos el contenido
            $mail->CharSet = 'UTF-8';
            $subject =  "Informacion Importante";
            $message = "Hola $user->nombre:\n
                        Este Correo se envia con el proposito de informar que usted ha sido asignado como revisor para el articulo <strong>$articulo->titulo</strong>.\n\n
                        Atentamente\n
                        <strong>SGEA</strong>\n\n
                        Este mensaje es generado de forma automatica por lo que solicita No responder.";

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
            // return response()->json(['error' => $e->getMessage()], 500);
            return false;
        }

    }
}
