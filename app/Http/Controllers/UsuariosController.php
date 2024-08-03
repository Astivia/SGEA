<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\usuarios;
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
        return view ('Usuarios.index',compact('Usuarios'));
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
        usuarios::create($datos);
        return redirect()->back()->with('success', 'Se ha Registrado correctamente');
    }


    public function show(string $id)
    {
        $Usu = usuarios::find($id);
        $articulos=articulosAutores::where('usuario_id',$Usu->id)->get();
        return view ('Usuarios.read',compact('Usu','articulos'));
    }

    public function edit(string $id)
    {
        $usu = usuarios::find($id);
        $roles =Role::All();
        return view ('Usuarios.edit',compact('usu','roles'));
    }


    public function update(Request $request, string $id)
    {
        $usuario=usuarios::find($id);
        $NuevosDatos = $request->all();
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

    public function insertUser(Request $request){
        //validamos datos
        try {
            $validatedData = $request->validate([
                'authorData.curp' => 'required|string|max:18',
                'authorData.nombre' => 'required|string|max:255',
                'authorData.ap_paterno' => 'required|string|max:255',
                'authorData.ap_materno' => 'required|string|max:255',
                'authorData.telefono' => 'required|string|max:15',
                'authorData.email' => 'required|string|email|max:255',
                'authorData.institucion' => 'required|string|max:255',
            ]);

            $authorData = $validatedData['authorData'];
            

            if($authorData['curp'][10]=='H'){
                $authorData['foto'] = 'DefaultH.jpg';
            }else {
                $authorData['foto'] = 'DefaultM.jpg';
            }
            
            //verificamos que no exista la curp
            if (usuarios::where('curp', $authorData['curp'])->exists()) {
                $error='Ya existe un participante con la CURP ingresada. No se guardaron los datos';
                return response()->json(['error' => $error]);
            }
            $user = new usuarios();
            $user->foto=$authorData['foto'];
            $user->curp = $authorData['curp'];
            $user->nombre = $authorData['nombre'];
            $user->ap_paterno = $authorData['ap_paterno'];
            $user->ap_materno = $authorData['ap_materno'];
            $user->telefono = $authorData['telefono'];
            $user->email = $authorData['email'];
            $user->estado = "alta,no registrado";
            $user->save();

            $institution =$authorData['institucion'];
            return response()->json(['success' => 'Usuario insertado correctamente.','id'=>$user->id , $institution]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500)->with('error',$e->getMessage() );
        }
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
    // //eliminacion multiple
    // public function deleteMultiple(Request $request)
    // {
    //     $ids = $request->ids;
    //     if (!empty($ids)) {
    //         usuarios::whereIn('id', $ids)->delete();
    //         return response()->json(['success' => "Registros eliminados correctamente."]);
    //     }
    //     return response()->json(['error' => "No se seleccionaron registros."]);
    // }
    
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
