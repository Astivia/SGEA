<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICAR DATOS</title>
</head>
<body>
    <h1>MODIFICAR ARTICULO</h1>
    {!! Form::open(['method'=>'PATCH','url'=>'/articulos/'.$art->id]) !!}
        <label for="titulo">Titulo:</label>
        {!! Form::text ('nombre',$art->articulo->titulo)!!}
        <br><br>
        <label for="event-name">Evento :</label>
        <select name="evento_id" require>
        @foreach ($Eventos as $e)
            <option value="{{$e->id }}" {{ $e->id == $art->evento_id ?'selected':''}}>
                {{ $e->acronimo }} {{ $e->edicion }}
            </option>
        @endforeach
        </select>

        <br><br>
        <label for="area">Area :</label>
        <select name="area_id" require>
            @foreach ($Areas as $area)
                <option value="{{ $area->id }}"{{ $area->id == $art->area_id ?'selected':''}}>
                    {{ $area->nombre}}
                </option>
            @endforeach
        </select>
        <br><br>
        <label for="autor">Autor :</label>
        <select name="autor_id" required>
            @foreach ($Autores as $autor)
                <option value="{{ $autor->id }}"
                    {{ $art->autor ? ($art->autor->id == $autor->id ? 'selected' : '') : '' }}>
                    {{ $autor->participante->nombre }} {{ $autor->participante->apellidos }}
                </option>
            @endforeach
        </select>
        <br><br>
        <button type="submit">Guardar Cambios</button>
        <a href="{{!!asset('/articulos')!!}"><button>Cancelar</button></a> 
    {!!Form::close()!!}   

    

</body>
</html>