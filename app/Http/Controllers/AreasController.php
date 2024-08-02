<?php

namespace App\Http\Controllers;

use App\Models\areas;
use App\Models\articulos;

use Illuminate\Http\Request;

class areasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Areas = areas::where('id','>',0)->orderBy('id')->get();

         return view ('Areas.index', compact('Areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos=$request->all();
        areas::create($datos);
        return redirect ('/areas')->with('success', 'Se ha registrado de manera Satisfactoria');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $area=areas::find($id);
        return view ('Areas.edit',compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $NuevosDatos = $request->all();
        $area=areas::find($id);
        $area->update($NuevosDatos);
        return redirect('/areas')->with('info', 'informacion actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
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
