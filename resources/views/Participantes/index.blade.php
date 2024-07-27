@extends('layouts.master')
<title>Participantes</title>

</head>
@section('Content')
<div class="container">
    
    <div class="search-create">
        <h1 id="titulo-h1">Participantes del {!!$evento->acronimo!!} {!!$evento->edicion!!}</h1>
        <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
    </div>



    @if($part==null)
    <strong>No hay datos</strong>
    @else
    <div class="ajuste" >
    <table id="example" class="display  responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>NOMBRE</th>
            <th>CORREO</th>
            @role(['Administrador','Organizador'])
            <th>Controles</th>
            @endrole
        </tr>
        </thead>
        <tbody>
        @foreach ($part as $usu)
        <tr>
            <td>{!!$usu->nombre_completo!!}</td>
            <td>{!!$usu->email!!}</td>
            @role(['Administrador','Organizador'])
            <td>
                <!-- <a href="route{!! 'usuarios/'.$usu->id !!}"><i class="las la-info-circle la-2x"></i></a> -->
                <a href="{{ url('usuarios/'.$usu->id) }}"><i class="las la-info-circle la-2x"></i></a>

                {!! Form::open(['route' => ['participantes.destroy', $evento->id, $usu->id], 'method' => 'delete', 'style' => 'display:inline-block;']) !!}
                    <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar este participante?');" style="border:none; background:none;">
                        <i class="las la-trash la-2x" style="color:red;"></i>
                    </button>
                {!! Form::close() !!}

            </td>
            @endrole
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    @endif
</div>

<div id="create-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Añadir participante</h2>
        <strong>Evento: {!!$evento->acronimo!!} {!!$evento->edicion!!}</strong>

        {!! Form::open(['route' => 'participantes.store']) !!}

            {!! Form::hidden('evento_id', $evento->id) !!}

            <label for="participante-name">Seleccionar Usuario:</label>

            {!! Form::select('usuario_id', $usuarios->pluck('nombre_completo', 'id'), null, ['required' => 'required']) !!}
            <br><br>
            <button type="submit">Guardar</button>
        {!!Form::close()!!}
    </div>
</div>

@endsection