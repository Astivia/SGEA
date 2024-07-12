@extends('layouts.master')
    <title>Modificar Datos</title>
</head>
@section('Content')
<div class="container">
    <h1>MODIFICAR ARTICULO</h1>

    {!! Form::open(['method'=>'PUT','url'=>'/articulos/'.$articulo->evento_id.'/'.$articulo->id, 'files' => true]) !!}
        <label for="titulo"><strong>Titulo:</strong></label>
        {!! Form::text ('titulo',$articulo->titulo)!!}

        <label for="desc">Resumen:</label>
        <textarea rows="4" cols="50" id="description" name="resumen" >{!!$articulo->resumen!!}</textarea>
        
        <label for="area"><strong>Area : </strong>
            <select name="area_id" require>
                @foreach ($Areas as $area)
                    <option value="{{ $area->id }}"{{ $area->id == $articulo->area_id ?'selected':''}}>
                        {{ $area->nombre}}
                    </option>
                @endforeach
            </select>
        </label>
        <strong>Estado:</strong>
        {!! Form::text ('estado', $articulo->estado) !!}
        <br><br>
        <p><Strong>Archivo actual: </Strong>{!!$articulo->archivo!!}</p>
        <br>
        <div class="form-group">
            <label for="nuevo_archivo_pdf">Seleccionar nuevo archivo PDF:</label>
            <input type="file" class="form-control" id="archivo" name="archivo">
        </div>
        <br><br>
        <button type="submit" id="create-event-btn">Guardar Cambios</button>
    {!!Form::close()!!}   
    <a href="{{ url('articulos') }}"><button>Cancelar</button></a> 
</div>
    
@endsection