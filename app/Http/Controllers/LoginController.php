<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use App\Models\User;
use App\Models\usuarios;


class LoginController extends Controller
{
    private function generarCodigo(){
        //Generamos CODIGO DE VERIFICACION para correo
        $characters = '0123456789';
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    public function registerView(){
            
        return view ('register');
    }


    public function enviarCodigo(Request $request){

        //creamos el usuario con estado "alta,no registrado"
        $Datos=$request->all();
        //validamos que NO exista un participante ya registrado con la misma CURP

        if (usuarios::where('curp', $Datos['curp'])->exists()) {
            return redirect()->back()->with('error', 'Ya existe un participante con la CURP ingresada');
        }
        
        if($Datos['curp'][10]=='H'){
            $Datos['foto'] = 'DefaultH.jpg';
        }else {
            $Datos['foto'] = 'DefaultM.jpg';
        }
        //Encriptamos la contraseña y Creamos el usuario en la BD
        $Datos['password'] = Hash::make($Datos['password']);
        //establecemos un dato para el estado
        $Datos['estado'] = "alta,no registrado";
        $user = usuarios::create($Datos);
        $userID=$user->id;




        //////////////////////////////////////////ENVIO DE CORREO DE VERIFICACION//////////////////////////////////////////
        $email = $request->get('email');
        
        $codigo = $this->generarCodigo();
        // echo("<script>console.log('codigo:$codigo');</script>");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 'Invalid email address'], 422);
        }
        
        $mail = new PHPMailer();
        try {
            // Set up SMTP configuration (replace with your SMTP settings)
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
            $mail->addAddress("$email");
            
            $person=$request->get('nombre');
            // contenido
            $mail->CharSet = 'UTF-8';
            $subject =  "Código de verificación";
            $message = "Hola $person:\n\nTu codigo de verificacion es:<strong>$codigo</strong>\n\nNo compartas este codigo con nadie.\n\nAtentamente\nSGEA";

            //enviamos  el codigo:
            $mail->Subject = $subject; 
            $mail->Body = nl2br($message);
            $mail->isHTML(true); // Set email format to HTML
            //$mail->send();
            // Send the email
            if (!$mail->send()) {
                return redirect()->back()->with('error', 'Error en el envio: '.$mail->ErrorInfo);
            } else {
                return view('emailVerification',compact('codigo','email','userID'));
                
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function verificarEmail(Request $request){
        $datos=$request->all();
        
        $user=usuarios::where('id',$datos['user-id'])->get();


        if($datos['input-usuario']==$datos['codigo']){
            $user->estado = "alta,registrado";
            $user->save(); 
            return redirect('login')->with('success', 'Se verifico el Email');
        }else{
            return redirect()->back()->with('error', 'Ocurrio un problema ');
        }

    }

    public function register(Request $request){

        //recolectamos los datos en una variable
        $Datos=$request->all();

        //validamos que NO exista un participante ya registrado con la misma CURP

        if (usuarios::where('curp', $Datos['curp'])->exists()) {
            return redirect()->back()->with('error', 'Ya existe un participante con la CURP ingresada');
        }
        
        if($Datos['curp'][10]=='H'){
            $Datos['foto'] = 'DefaultH.jpg';
        }else {
                $Datos['foto'] = 'DefaultM.jpg';
            
                
                //Encriptamos la contraseña y Creamos el usuario en la BD
                $Datos['password'] = Hash::make($Datos['password']);
                //establecemos un dato para el estado
                $Datos['estado'] = "alta,no registrado";
                //Verificamos si el correo ya esta verificado
                $user = usuarios::create($Datos);

                return redirect('login');

                //Iniciamos sesion con el usuario recien creado y lo redirigimos al dashboard
                // Auth::login($user);
                // return redirect(route('home'));
        }
        
    }

    public function login(Request $request){
        //validacion de datos
        $usuario= usuarios::where('email',$request['email'])->first();

       
        if($usuario->estado=="alta,registrado"){
            $credentials =[
                "email"=> $request['email'],
                "password"=> $request['password']
            ];
    
            //mantener iniciada la sesion
            $remember = ($request->has('remember') ? true :false);
    
            if(Auth::attempt($credentials,$remember)){
    
                $request->session()->regenerate();
    
                return redirect()->intended(route('home'));
    
            }else{
                return redirect('login')->with('error', 'Ocurrio un Error, verifica los datos');
            }
        }else{
            return redirect('registro',compact('usuario'))->with('error','El usuario no ha verificado su email');
        }

        
    }

    public function logout (Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
