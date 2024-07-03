<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\usuarios;
use App\Models\autores;
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
        $Usuarios=usuarios::All();
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
            'ap_pat' => 'required|string',
            'ap_mat' => 'required|string',
            'curp' => 'required|string',
            'email' => 'required|email',
        ]);
        $datos=$request->all();
        
        //verificamos que no exista la curp
        if (usuarios::where('curp', $datos['curp'])->exists()) {
            return redirect()->back()->with('error', 'Ya existe un participante con la CURP ingresada.No se guardaron los datos');
        }

        $datos['password'] = Hash::make($datos['password']);
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
         return redirect('/usuarios')->with('info','Se guardaron los cambios de manera satisfactoria');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario=usuarios::find($id);

        if ($usuario->eventos()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar porque está registrado en uno o más eventos');
        }else if (autores::where('usuario_id', $usuario->id)->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el participante porque esta registrado como Autor');
        }
        $usuario->delete();
        return redirect('usuarios')->with('success', 'Participante eliminado correctamente');
    }
}
