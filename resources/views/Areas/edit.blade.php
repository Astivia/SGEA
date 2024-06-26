@extends('layouts.master')
    <title>Modificar datos</title>
    <link rel="stylesheet" href="{{asset('SGEA/public/css/mainModificar.css')}}">

</head>
@section('Content')
<div class="container">

    <h1>MODIFICAR AREA</h1>
    {!! Form::open(['method'=>'PATCH','url'=>'/areas/'.$area->id]) !!}
        <label for="area-name">Nombre:</label>
        <br>
        {!! Form::text ('nombre',$area->nombre)!!}
        <br><br>
        <label for="area-description">Descripción:</label>
        <br>
        {!! Form::textarea ('descripcion',$area->descripcion)!!}
        <br><br>
        <button type="submit">Guardar Cambios</button>
        <a href="{{!!asset('/areas')!!}"><button>Cancelar</button></a> 
    {!!Form::close()!!}   
    
</div>
@endsection