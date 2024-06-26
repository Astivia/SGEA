@extends('layouts.master')
    <title>Modificar Datos</title>
    <link rel="stylesheet" href="{{asset('SGEA/public/css/mainModificar.css')}}">

</head>
@section('Content')
<div class="container">

    <h1>MODIFICAR DATOS</h1>
    {!! Form::open(['method'=>'PATCH','url'=>'/revisores_articulos/'.$ra->id]) !!}
        
        <label for="participante">Seleccionar Revisor:</label>
        <select name="participante_id" require>
        @foreach ($Participantes as $part)
            <option value="{{$part->id }}" {{ $part->id == $ra->participante_id ?'selected':''}}>
                {{ $part->nombre }} {{ $part->apellidos }}
            </option>
        @endforeach
        </select>
        <br><br>
        <label for="articulo">Seleccionar Revisor:</label>
        <select name="articulo_titulo" require>
        @foreach ($Articulos as $art)
            <option value="{{$art->id }}" {{ $art->id == $ra->articulo_id ?'selected':''}}>
                {{ $art->titulo }} 
            </option>
        @endforeach
        </select>                   
        <br><br>
        <button type="submit">Guardar</button>
        <a href="{{!!asset('/revisores_articulos')!!}"><button>Cancelar</button></a>
    {!!Form::close()!!}   
</div>
    
    
@endsection