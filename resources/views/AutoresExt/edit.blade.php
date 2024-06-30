@extends('layouts.master')
    <title>Modificar datos</title>

@section('Content')

    <div class="container">
        <h2>Modificar datos</h2>
        {!! Form::open(['method'=>'PATCH','url'=>'/autores_externos/'.$ae->id]) !!}

        <label for="autorExt-name">Nombre:</label>
        {!! Form::text ('nombre',$ae->nombre,['require'])!!}
        
        <label for="participante-lastName">Apellido Paterno:</label>
        {!! Form::text ('ap_pat',$ae->ap_pat,['require'])!!}

        <label for="participante-2lastName">Apellido Materno:</label>
        {!! Form::text ('ap_mat',$ae->ap_mat,['require'])!!}

        <label for="afiliacion">Afiliacion:</label>
        {!! Form::text ('afiliacion',$ae->afiliacion,['require'])!!}

        <button type="submit">Guardar Cambios</button>
        {!!Form::close()!!}
        <a href="{{ url('autores_externos') }}"><button>Cancelar</button></a> 
    </div>

@endsection