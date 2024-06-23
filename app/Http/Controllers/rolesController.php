<?php

namespace App\Http\Controllers;
use App\Models\roles;
use App\Models\participantes;

use Illuminate\Http\Request;

class rolesController extends Controller
{
    public function index()
    {
        $roles = roles::all();


         return view ('Roles.index', compact('roles'));
    }
    public function show(string $id)
    {
        $rol=roles::find($id);
        $users=participantes::hasRole($id);

        return view ('Roles.read',compact('rol','users'));
    }
}
