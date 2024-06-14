<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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

    

</body>
</html>