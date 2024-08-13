<?php

namespace App\Http\Controllers;

use App\Models\areas;
use App\Models\articulos;

use Illuminate\Http\Request;

class areasController extends Controller
{
    
    public function __construct(){
        $this->middleware('can:areas.index')->only('index');
        $this->middleware('can:areas.edit')->only('edit','update');
        $this->middleware('can:areas.create')->only('create','store'); 
        $this->middleware('can:areas.destroy')->only('destroy'); 
    }

    public function index()
    {
        $Areas = areas::where('id','>',0)->orderBy('id')->get();
         return view ('Areas.index', compact('Areas'));
    }

    public function store(Request $request)
    {
        $datos=$request->all();
        areas::create($datos);
        return redirect ('/areas')->with('success', 'Se ha registrado de manera Satisfactoria');
    
    }

    public function edit(string $id)
    {
        $area=areas::find($id);
        return view ('Areas.edit',compact('area'));
    }

    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        $area=areas::find($id);
        $area->update($NuevosDatos);
        return redirect('/areas')->with('info', 'informacion actualizada');
    }

    public function destroy(string $id)
    {
        $area=areas::find($id);

        if (articulos::where('area_id', $area->id)->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar: hay articulos asociados con esta Area');
        }

        $area->delete();

        return redirect('areas')->with('info', 'Se ha eliminado correctamente');
    }
   
    public function deleteMultiple(Request $request)
    {
        $areaIds = $request->areaIds;

        if (!empty($areaIds)) {
            foreach ($areaIds as $id) {
                $area = areas::find($id);

                if (!$area) {
                    return response()->json(['error' => "Área no encontrada. ID: $id"], 404);
                }

                if (articulos::where('area_id', $area->id)->count() > 0) {
                    return response()->json(['error' => "No se puede eliminar: hay artículos asociados con el área ID: $id"], 400);
                }

                $area->delete();
            }
            return response()->json(['success' => "Áreas eliminadas correctamente."]);
        }

        return response()->json(['error' => "No se seleccionaron áreas."], 400);
    }
}
