@extends('layouts.master')
    <title>Modificar Datos</title>
</head>
@section('Content')
<div class="container">

    <h1>MODIFICAR DATOS</h1>
    {!! Form::open(['method'=>'PATCH','url'=>'/revisores_articulos/'.$ra]) !!}
        
        <label for="participante">Seleccionar Revisor:</label>
        
        
        <label for="articulo">Seleccionar Articulo:</label>
                       
        
        <button type="submit">Guardar</button>
        <a href="{{!!asset('/revisores_articulos')!!}"><button>Cancelar</button></a>
    {!!Form::close()!!}   
</div>
    
    
@endsection