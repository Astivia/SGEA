<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>MODIFICAR Evento</h1>
    {!! Form::open(['method'=>'PATCH','url'=>'/eventos/'.$evento->id]) !!}
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