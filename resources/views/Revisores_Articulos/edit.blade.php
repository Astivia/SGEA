@extends('layouts.master')
    <title>Modificar Datos</title>

@section('Content')
<div class="container">
    <h1>MODIFICAR DATOS</h1>

    {!! Form::open(['method'=>'PATCH','url'=>'/revisores/'.$articulo->id]) !!}
        
        <label for="participante">Seleccionar Revisor:</label>
        
        
        <label for="articulo">Seleccionar Articulo:</label>
                       
        
        <button type="submit">Guardar</button>
    {!!Form::close()!!}   
        <a href="{{ url()->previous() }}"><button>Cancelar</button></a> 
</div>
    
    
@endsection