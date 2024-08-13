<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\usuarios;
use App\Models\eventos;
use App\Models\articulosAutores;
use App\Models\participantes;
use App\Models\comite_editorial;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class usuariosController extends Controller
{

    public function __construct(){
        $this->middleware('can:usuarios.index')->only('index');
        $this->middleware('can:usuarios.edit')->only('edit','update');
        $this->middleware('can:usuarios.create')->only('create','store'); 
        $this->middleware('can:usuarios.destroy')->only('destroy'); 
    }

    public function index()
    {
        $Usuarios = usuarios::where('id','!=',1)->get();
        $roles = Role::All();
        return view ('Usuarios.index',compact('Usuarios','roles'));
    }

    public function store(Request $request)
    {
        //validacion de datos
        $this->validate($request, [
            'nombre' => 'required|string',
            'ap_paterno' => 'required|string',
            'ap_materno' => 'required|string',
            'curp' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required'
        ]);
        $datos=$request->all();

        if($datos['curp'][10]=='H'){
            $datos['foto'] = 'DefaultH.jpg';
        }else {
            $datos['foto'] = 'DefaultM.jpg';
        }
        
        //verificamos que no exista la curp
        if (usuarios::where('curp', $datos['curp'])->exists()) {
            return redirect()->back()->with('error', 'Ya existe un participante con la CURP ingresada.No se guardaron los datos');
        }
        $datos['password'] = Hash::make($datos['password']);
        $datos['estado'] = "alta,no registrado";
        $usuario=usuarios::create($datos);
        $usuario->roles()->sync($request->roles);
        return redirect()->back()->with('success', 'Se ha Registrado correctamente');
    }


    public function show(string $id)
    {
        $Usu = usuarios::find($id);
        if($Usu->foto === "DefaultH.jpg" || $Usu->foto === "DefaultM.jpg"){
            $Usu->foto= 'SGEA/storage/app/public/users/profile/'.$Usu->foto;
        }else{
            $Usu->foto = 'SGEA/storage/app/public/users/profile/'.$Usu->curp.'/'.$Usu->foto;
        }
        $articulos=articulosAutores::where('usuario_id',$Usu->id)->get();
        return view ('Usuarios.read',compact('Usu','articulos'));
    }

    public function edit(string $id)
    {
        $usu = usuarios::find($id);
        if(!$usu){
            return redirect()->back()->with('error', 'El usuario no existe.');
        }

        // Si el usuario logueado no es el mismo que el usuario a editar y no es administrador
        if (auth()->user()->id !== $usu->id && !auth()->user()->hasRole('Administrador')) {
            return redirect()->back()->with('error', 'No tienes Autorizacion para modificar este perfil.');
        }
        if($usu->id !==1){
            $roles = Role::All();
            if($usu->foto === "DefaultH.jpg" || $usu->foto === "DefaultM.jpg"){
                $url= 'SGEA/storage/app/public/Users/profile/'.$usu->foto;
            }else{
                $url= 'SGEA/storage/app/public/Users/profile/'.$usu->curp.'/'.$usu->foto;
            }
            $usu->foto = $url;
            return view ('Usuarios.edit',compact('usu','roles'));
        }else{
            return redirect()->back()->with ('error','No es posible modificar al Administrador del sistema');
        }
    }


    public function update(Request $request, string $id)
    {
        $usuario=usuarios::find($id);
        $NuevosDatos = $request->all();
        if($request->has('newPhoto')){
            // Definir la ruta donde se guardará el archivo
            $destinationPath = storage_path('app/public/Users/profile/'.$usuario->curp);
            // Crear la carpeta si no existe
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            // Generar un nombre único para el archivo
            $fileName = time().'.'.$NuevosDatos['newPhoto']->getClientOriginalExtension();
            // Mover el archivo a la ruta especificada
            $NuevosDatos['newPhoto']->move($destinationPath, $fileName);
            //guardamos solo el nombre en la BD
            $NuevosDatos['foto'] = $fileName;
        }else{
            $NuevosDatos['foto'] = $usuario->foto;
        }
        //asignamos Rol elejido
        $usuario->roles()->sync($request->roles);

        if(is_null($NuevosDatos['password'])){
            $NuevosDatos['password']=$usuario->password;
        }else{
            //encriptamos la nueva contraseña
            $NuevosDatos['password'] = Hash::make($NuevosDatos['password']);
        }
        //actualizamos
        $usuario->update($NuevosDatos);
         return redirect('/usuarios')->with('info','Informacion Actualizada');
    }

    public function destroy(string $id)
    {
        $usuario=usuarios::find($id);

        if ($usuario->eventos()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar porque está registrado en uno o más eventos');
        }else if (articulosAutores::where('usuario_id', $usuario->id)->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el usuario porque esta registrado como Autor');
        }
        $usuario->delete();
        return redirect('usuarios')->with('info', 'Usuario eliminado correctamente');
    }

    public function redirectToAppropriateView()
    {
        $user = Auth::user();
        $part = participantes::where('usuario_id', $user->id)->first();
        
        if ($part) {
            // El usuario está registrado en algún evento
            return redirect()->route('evento.index', [$part->evento->acronimo, $part->evento->edicion]);
        } else {
            // El usuario no está registrado en ningún evento
            return redirect()->route('dashboard');
        }
    }

    public function deleteMultiple(Request $request){
        $ids = $request->ids;
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $usuario = usuarios::find($id);

                if (!$usuario) {
                    return response()->json(['error' => "No se encontró el usuario con id: $id"], 404);
                }

                if ($usuario->eventos()->count() > 0) {
                    return response()->json(['error' => "No se puede eliminar el usuario con id: $id porque está registrado en uno o más eventos"], 400);
                } else if (articulosAutores::where('usuario_id', $usuario->id)->count() > 0) {
                    return response()->json(['error' => "No se puede eliminar el usuario con id: $id porque está registrado como Autor"], 400);
                }

                $usuario->delete();
            }
            return response()->json(['success' => "Usuarios eliminados correctamente."]);
        }
        return response()->json(['error' => "No se seleccionaron usuarios."], 400);
    }

    public function insertUser(Request $request){
        try {
            //validamos datos
            $validatedData = $request->validate(['authorData.curp' => 'required|string|max:18','authorData.nombre' => 'required|string|max:255',
                'authorData.ap_paterno' => 'required|string|max:255','authorData.ap_materno' => 'required|string|max:255',
                'authorData.telefono' => 'required|string|max:15','authorData.email' => 'required|string|email|max:255',
                'authorData.institucion' => 'required|string|max:255',
            ]);
            $authorData = $validatedData['authorData'];

            //verificamos que no exista la curp
            if (usuarios::where('curp', $authorData['curp'])->exists()) {
                $error='Ya existe un participante con la CURP ingresada. No se guardaron los datos';
                return response()->json(['error' => $error]);
            }
            //definimos la foto que tendra el usuario
            if($authorData['curp'][10]=='H'){$authorData['foto'] = 'DefaultH.jpg';}else {$authorData['foto'] = 'DefaultM.jpg';}
            //creamos el usuario
            $user = new usuarios();
            $user->foto=$authorData['foto'];$user->curp = $authorData['curp'];$user->nombre = $authorData['nombre'];
            $user->ap_paterno = $authorData['ap_paterno'];$user->ap_materno = $authorData['ap_materno'];
            $user->telefono = $authorData['telefono'];$user->email = $authorData['email'];$user->estado = "alta,no registrado";
            $user->save();

            $user->assignRole('Autor');

            //definimos la institucion
            $institution =$authorData['institucion'];
            return response()->json(['success' => 'Usuario insertado correctamente.','id'=>$user->id , $institution]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500)->with('error',$e->getMessage() );
        }
    }

    public function verifyCurp(Request $request) {
        $curp = $request->input('curp');
        $user = usuarios::where('curp', $curp)->first();
    
        if ($user) {
            return response()->json([
                'status' => 'exists',
                'user' => $user
            ]);
        } else {
            return response()->json(['status' => 'not_exists']);
        }
    }

    public function verifyEmail(Request $request) {
        if (usuarios::where('email', $request->input('email'))->exists()) {
            return response()->json(['status' => 'exists']);
        } else {
            return response()->json(['status' => 'not_exists']);
        }
    }

    
}
