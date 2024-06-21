<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>
</head>
<body>
    <h1>Modificar Evento</h1>
    {!! Form::open(['method'=>'PATCH','url'=>'/eventos/'.$evento->id]) !!}
        <label for="img">Imagen:</label>
        <img src="<?php echo asset('SGEA/public/assets/uploads/' . $evento->img); ?>" alt="Logo-de-Evento"> 
        <img src="<?php echo public_path('assets/uploads/'. $evento->img); ?>" alt="Logo-de-Evento">
        <br><br>
        <img src="{{ asset('assets/uploads/'.$evento->img) }}" alt="aaa">
        
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

    

</body>
</html>