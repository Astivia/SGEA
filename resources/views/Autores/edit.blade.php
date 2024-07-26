@extends('layouts.master')
    <title>Modificar Datos</title>
@section('Content')
    <div class="container">
        <h1>MODIFICAR AUTOR</h1>
        {!! Form::open(['method'=>'PATCH','url'=>'/autores/'.$autor->usuario->id]) !!}
            <label for="curp"><strong>CURP:</strong></label>
            {!! Form::text ('curp',$autor->usuario->curp,['readonly' => true])!!}
            <label for="nombre"><strong>Nombre:</strong></label>
            {!! Form::text ('nombre',$autor->usuario->nombre_completo,['readonly' => true])!!}
            <label for="telefono"><strong>Teléfono:</strong></label>
            {!! Form::tel ('telefono',$autor->usuario->telefono,['readonly' => true])!!}
            <label for="email"><strong>Email:</strong></label>
            {!! Form::email('email',$autor->usuario->email,['readonly' => true])!!}
            <label for="corresp-email"><strong>Email de correspondencia:</strong></label>
            {!! Form::email('corresp-email',$autor->email)!!}
            <label for="institucion"><strong>Institución:</strong></label>
            {!! Form::text ('institucion',$autor->institucion)!!}
            <button type="submit">Guardar Cambios</button>
        {!!Form::close()!!}
        <a href="{{ url()->previous() }}"><button> Cancelar</button></a>  
    </div>
@endsection