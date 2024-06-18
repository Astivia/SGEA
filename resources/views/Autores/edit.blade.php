<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>MODIFICAR Autor</h1>
    {!! Form::open(['method'=>'PATCH','url'=>'/autores/'.$autor->id]) !!}

        <label for="evento">Seleccionar evento :</label>
        <select name="evento_id" require>
            @foreach ($events as $e)
                <option value="{{$e->id }}" {{ $e->id == $autor->evento_id ?'selected':''}}>
                    {{ $e->acronimo }} {{ $e->edicion }}
                </option>
            @endforeach
        </select>

        <br><br>
                       
        <label for="participante">Seleccionar participante :</label>
        <select name="participante_id" require>
            @foreach ($participantes as $p)
                <option value="{{$p->id }}" {{ $p->id == $autor->participante_id ?'selected':''}}>
                    {{ $p->nombre}} {{ $p->apellidos }}
                </option>
            @endforeach
        </select>
        <br><br>
        <label for="afiliacion">Afiliacion:</label>
        {!! Form::text ('afiliacion',$autor->afiliacion,['require'])!!}

        <br><br>
        <button type="submit">Guardar cambios</button>
        <a href="{{!!asset('/autores')!!}"><button>Cancelar</button></a> 
    {!!Form::close()!!}

    

</body>
</html>