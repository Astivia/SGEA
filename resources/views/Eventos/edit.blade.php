@extends('layouts.master')
    <title>Editar</title>
</head>

@section('Content')
<div class="container">

    <h1>Modificar Evento</h1>
    {!! Form::open(['method' => 'PATCH', 'url' => '/eventos/'.$evento->id, 'files' => true]) !!}
        <div>
            <!-- <img src="<?php echo asset('SGEA/public/assets/uploads/' . $evento->img); ?>" alt="logo" style="width: 400px;"> -->
            <img src="{{ asset('SGEA/public/assets/uploads/' . $evento->img) }}" alt="logo" style="width: 400px;">
            <!-- <img src="{{ asset('SGEA/public/assets/uploads/' . $evento->img) }}" alt="Logo">  por alguna razon esto no funciona-->
            <p>Nombre de la imagen: {{ ( $evento->img) }}</p>
        </div>
        <br>

        <label for="img">Cambiar Logo:</label>
        <input type="file" id="img" name="img" accept="image/png">

        <br><br>

        <label for="nombre">Nombre:</label>
        {!! Form::text ('nombre',$evento->nombre)!!}

        <br><br>

        <label for="acronimo">Acrónimo:</label>
        {!! Form::text ('acronimo',$evento->acronimo)!!}

        <br><br>

        <label for="fecha_inicio">Fecha de inicio:</label>
        {!! Form::date ('fecha_inicio',$evento->fecha_inicio)!!}

        <br><br>

        <label for="fecha_fin">Fecha de fin:</label>
        {!! Form::date ('fecha_fin',$evento->fecha_fin)!!}

        <br><br>

        <label for="edicion">Edición:</label>
        {!! Form::number ('edicion',$evento->edicion)!!}

        <br><br>

        <button type="submit">Guardar evento</button>
        <a href="{{!!asset('/eventos')!!}"><button>Cancelar</button></a>
        {!!Form::close()!!}   
</div>

    
     @endsection