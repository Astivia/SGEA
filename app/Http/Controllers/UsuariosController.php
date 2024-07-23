<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\usuarios;
use App\Models\participantes;
use App\Models\articulosAutores;
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Usuarios=usuarios::orderBy('id')->get();
        return view ('Usuarios.index',compact('Usuarios'));
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

        
        // return redirect('/usuarios')->with('success', 'Se ha Registrado correctamente');
        return redirect()->back()->with('success', 'Se ha Registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Usu = usuarios::find($id);
        $curp=$Usu->curp;
        if($curp[10]=='H'){
            $sexo='Masculino';
        }else{
            $sexo = 'Femenino';
        }
        
        return view ('Usuarios.read',compact('Usu','sexo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usu = usuarios::find($id);
        $roles =Role::All();
        return view ('Usuarios.edit',compact('usu','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
            $evento = $part->evento;
            $acronimo = $evento->acronimo;
            $edicion = $evento->id; // Asumiendo que el ID representa la edición del evento

            return redirect()->route('evento.index', ['acronimo' => $acronimo, 'edicion' => $edicion]);
        } else {
            // El usuario no está registrado en ningún evento
            return redirect()->route('dashboard');
        }
    }
}
