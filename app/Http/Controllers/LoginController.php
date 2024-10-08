<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use App\Models\User;
use App\Models\usuarios;
use App\Models\eventos;
use App\Models\participantes;


class LoginController extends Controller
{
    private function generarCodigo(){
        //Generamos el codigo de verificacion
        $characters = '0123456789';
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    private function enviarCodigo(usuarios $user,$codigo){
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Invalid email address'], 422);
        }
        
        $mail = new PHPMailer();
        try {
            // Configuracion de protocolo SMTP
            $mail->SMTPDebug = 0;$mail->isSMTP();$mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;$mail->Username = 'astiviamax@gmail.com';
            $mail->Password = 'diulyvcniykrwacn';$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; $mail->Port = 587;
        
            $mail->setFrom('noreply@sgea.com', 'SGEA'); $mail->addAddress("$user->email");
            
            //Definimos el contenido
            $mail->CharSet = 'UTF-8';
            $subject =  "Código de verificación";
            $message = "Hola $user->nombre,\n\n
                        Tu codigo de verificacion es: <strong>$codigo</strong>\n\n
                        No compartas este codigo con nadie.\n\n
                        Atentamente\n
                        <strong>SGEA</strong>";

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
   

    public function verificarEmail(Request $request){
        $datos=$request->all();
        $user=usuarios::where('id',$datos['user-id'])->first();
        //validamos si el codigo es correcto
        if($datos['input-usuario']==$datos['codigo']){
            //el codigo es correcto
            $user->update([
                'estado' => 'alta,registrado'
            ]);
            $request->session()->forget('verification_code');
        }else{
            //el codigo es incorrecto
            $request->session()->forget('verification_code');
            return redirect('login')->with('error', 'El codigo es Incorrecto');
        }
        if(is_null($user->password) || $user->password == ''||$request->session()->get('RessetPass')==1){
            //Interfaz para definir password
            return view('Password',compact('user'));
        }
        $request->session()->forget('RessetPass');
        return redirect('login')->with('success', 'Se verifico el Email');
    }

    public function reenviarCodigo(Request $request){
        try{
            $userId = $request->input('user-id');
            $user = usuarios::find($userId);
    
            // obtenemos el codigo desde la variabla de sesion
            $verificationCode = $request->session()->get('verification_code');
    
             // validamos que el usuario y el codigo existan.
             if (!$user || !$verificationCode) {
                return response('Usuario o código no encontrado', 404);
            }
    
            // enviamos el codigo
            if($this->enviarCodigo(usuarios::find($userId), $verificationCode)){
                return response('Código reenviado exitosamente', 200);
            }else{
                return response('Error al enviar el código', 500);
            }
            // return response()->json(['success' => true]);
        }catch (\Exception $e) {
            // return response()->json(['success' => false, 'message' => 'Ocurrió un error al reenviar el código. Inténtalo de nuevo más tarde.'], 500);
            return response('Ocurrió un error al reenviar el código', 500);
        }
    }

    public function setPassword(Request $request){
        $datos=$request->all();
        $user = usuarios::find($datos['user-id']);
        $datos['password'] = Hash::make($datos['password']);
        $user->update([
            'password' => $datos['password'] 
        ]);
        return redirect('login')->with('success', 'Contraseña Definida');

    }
    
    public function register(Request $request){
        //recolectamos los datos en una variable
        $Datos=$request->all();
        //Verificamos si ya existe el usuario por medio de su curp
        $user = usuarios::where('curp', $Datos['curp'])->first();
        if ($user !== null) {
            //EL USUARIO EXISTE
            //verificamos su estado
            if ( $user->estado == "alta,registrado"){
                return redirect('login')->with('error', 'Ya existe un usuario con la CURP ingresada');
            }else if ($user->estado =="alta,no registrado"){
                //Iniciamos el proceso para verificar el usuario
                if (!$request->session()->has('verification_code')) {
                    $codigo = $this->generarCodigo();
                    if($this->enviarCodigo($user, $codigo)){
                        $request->session()->put('verification_code', $codigo);
                    }
                } else {
                    $codigo = $request->session()->get('verification_code');
                }
                return view('emailVerification',compact('user','codigo'));
            }
        }else{
            //EL USUARIO NO EXISTE -> iniciamos proceso para registrar
            if($Datos['curp'][10]=='H'){$Datos['foto'] = 'DefaultH.jpg';} else {$Datos['foto'] = 'DefaultM.jpg';}   
            $Datos['password'] = Hash::make($Datos['password']);
            //Establecemos el estado
            $Datos['estado'] = "alta,no registrado";
            //Creamos el usuario
            $user = usuarios::create($Datos);
            //iniciamos el proceso para verificar el usuario
            if (!$request->session()->has('verification_code')) {
                $codigo = $this->generarCodigo();
                if($this->enviarCodigo($user, $codigo)){
                    $request->session()->put('verification_code', $codigo);
                }
            } else {
                $codigo = $request->session()->get('verification_code');
            }
            return view('emailVerification',compact('user','codigo'));
        }
    }

    public function index($acronimo, $edicion)
    {
        // Buscar el evento con el acrónimo y la edición
        $evento = eventos::where('acronimo', $acronimo)->where('edicion', $edicion)->first();
        $evento->logo = 'SGEA/storage/app/public/EventImgs/'.$acronimo.$edicion.'/logo'.'/'.$evento->logo;
        
        if ($evento) {
            return view ('Eventos.read',compact('evento'));
        } else {
            return redirect()->route('dashboard')->with('error', 'Evento no encontrado');
        }
    }

    public function login(Request $request){
        //Buscamos el usuario por medio de su email
        $user= usuarios::where('email',$request['email'])->first();

        if ($user !== null){
            //EL USUARIO EXISTE -> entonces procedemos a verificar su estado
            if($user->estado=="alta,registrado"){
                //PROCESO PARA INICIAR SESION
                $credentials =[
                    "email"=> $request['email'],"password"=> $request['password']
                ];
                $remember = ($request->has('remember') ? true:false);
                if(Auth::attempt($credentials,$remember)){
                    //EL INICIO DE SESION FUE EXITOSO
                    $request->session()->regenerate();
                    $part=participantes::where('usuario_id',$user->id)->first();
                    if($part!==null){
                        //EL USUARIO ESTA REGISTRADO EN ALGUN EVENTO
                        $request->session()->put('eventoID', $part->evento->id);$request->session()->put('rol', $part->rol);
                        return redirect()->route('evento.index', ['acronimo' => $part->evento->acronimo, 'edicion' => $part->evento->edicion]);
                    }else{
                        $request->session()->put('eventoID', null);$request->session()->put('rol', null);
                        return redirect()->route('dashboard');
                    }
                }else{
                    //EL INICIO DE SESION NO FUE EXITOSO
                    return redirect('login')->with('error', 'Ocurrio un Error, verifica los datos');
                }
            }else if($user->estado=="alta,no registrado"){
                //El usuario registrado no ha verificado su email
                //generamos nuevo codigo
                if (!$request->session()->has('verification_code')) {
                    $codigo = $this->generarCodigo();
                    $this->enviarCodigo($user, $codigo);
                    $request->session()->put('verification_code', $codigo);
                } else {
                    $codigo = $request->session()->get('verification_code');
                }
                //redirige al usuario a la vista de verificación
                return view('emailVerification',compact('user','codigo'));
            }
        }else{
            return redirect()->back()->with('error', 'El usuario no Existe');
        }
    }

    public function logout (Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();
        return redirect('/login')->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache');
    }

    public function resetPassword(Request $request){
        $request->validate(['email' => 'required|email',]);
        $user = usuarios::where('email',$request['email'])->first();

        if (!$user) { return redirect('login')->with('error','No existe un usuario con esa dirección de correo');}

        if (!$request->session()->has('verification_code')) {
            $codigo = $this->generarCodigo();
            $request->session()->put('verification_code', $codigo);
        } else {
            $codigo = $request->session()->get('verification_code');
        }
        $this->enviarCodigo($user, $codigo);
        $rp=1;
        $request->session()->put('RessetPass', $rp);

        return view('emailVerification',compact('user','codigo'));
        
    }

    public function getSidebar()
    {
        return view('layouts.sidebar');
    }

}
