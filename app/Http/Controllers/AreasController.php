<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\areas;

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
        return redirect ('/areas');
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
        return redirect('/areas');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $area=areas::find($id);
        $area->delete();

        return redirect('areas');
    }
}
