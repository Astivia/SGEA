@extends('layouts.master')
    <title>Modificar Datos</title>
</head>
@section('Content')
<div class="container">
    <h1>MODIFICAR ARTICULO</h1>
    {!! Form::open(['method'=>'PATCH','url'=>'/articulos/'.$articulo->id]) !!}
        <label for="titulo"><strong>Titulo:</strong></label>
        {!! Form::text ('titulo',$articulo->titulo)!!}

        <label for="event-name"><strong>Evento: </strong>
            <select name="evento_id" require>
            @foreach ($Eventos as $e)
                <option value="{{$e->id }}" {{ $e->id == $articulo->evento_id ?'selected':''}}>
                    {{ $e->acronimo }} {{ $e->edicion }}
                </option>
            @endforeach
            </select>
        </label>
        <label for="area"><strong>Area : </strong>
            <select name="area_id" require>
                @foreach ($Areas as $area)
                    <option value="{{ $area->id }}"{{ $area->id == $articulo->area_id ?'selected':''}}>
                        {{ $area->nombre}}
                    </option>
                @endforeach
            </select>
        </label>
        <label for="autor"><strong>Autores Actuales:</strong>
        @if($articulo->autores->count() > 0)
                @foreach ($articulo->autores as $autor)
                    <i class="las la-pen-nib"></i>{!!$autor->usuario->nombre_completo!!} <a href="{!!url( 'usuarios/'.$autor->usuario->id) !!}"><i class="las la-info-circle la-1x"></i></a>
                @endforeach
            @elseif($articulo->autoresExternos->count() > 0)
                @foreach ($articulo->autoresExternos as $autor)
                    <i class="las la-external-link-alt"></i>{!!$autor->nombre_completo!!} <a href="{!! 'autores_externos/'.$autor->id !!}"><i class="las la-info-circle la-1x"></i></a>
                @endforeach
            @endif
        </label>
        <p>para cambiar al autor, seleccione un nuevo autor:</p>
        {!! Form::select('autor_id_autor', $autoresSistema, null, ['id' => 'autor_id_autor', 'placeholder' => 'Seleccionar del sistema...']) !!}
                        
        {!! Form::select('autor_id_ext', $autoresExternos, null, ['id' => 'autor_id_ext', 'placeholder' => 'Seleccionar Externo ...']) !!}
        <br><br>

        <strong>Estado:</strong>
        {!! Form::text ('estado',$articulo->estado)!!}

        <br><br>
        <button type="submit">Guardar Cambios</button>
    {!!Form::close()!!}   
    <a href="{{ url('articulos') }}"><button>Cancelar</button></a> 
</div>
    
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var autorSistemaSelect = document.getElementById('autor_id_autor');
        var autorExternoSelect = document.getElementById('autor_id_ext');

        autorSistemaSelect.addEventListener('change', function () {
            if (autorSistemaSelect.value) {
                autorExternoSelect.disabled = true;
            } else {
                autorExternoSelect.disabled = false;
            }
        });

        autorExternoSelect.addEventListener('change', function () {
            if (autorExternoSelect.value) {
                autorSistemaSelect.disabled = true;
            } else {
                autorSistemaSelect.disabled = false;
            }
        });

    });
</script>