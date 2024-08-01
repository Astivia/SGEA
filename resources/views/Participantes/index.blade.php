@extends('layouts.master')
<title>Participantes</title>

@section('Content')
<div class="container">
    
    <div class="search-create">
        <h1 id="titulo-h1">Participantes del {!!$evento->acronimo!!} {!!$evento->edicion!!}</h1>
        <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
    </div>

    @if(count($part)===0)
        <strong>No hay datos</strong>
    @else
    <div class="ajuste" >
        <button id="deleteSelected">Eliminar seleccionados</button>
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                        <th>NOMBRE</th>
                        <th>CORREO</th>
                        <th>ROL</th>
                        @role(['Administrador','Organizador'])
                            <th>Controles</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @foreach ($part as $usu)
                        <tr>
                        <td><input type="checkbox" class="selectRow" data-id="{{ $usu->id }}"></td>
                            <td><a href="{{url('usuarios/'.$usu->id)}}" style="color=#000;">{!!$usu->nombre_completo!!}</a></td>
                            <td><a href="mailto:{!!$usu->email!!}">{!!$usu->email!!}</a></td>
                            <td>
                                @if($usu->rol===null)
                                    No asignado
                                @else
                                    {!!$usu->rol!!}
                                @endif
                            </td>
                            @role(['Administrador','Organizador'])
                            <td>
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
        
    @endif
</div>

<div id="create-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Añadir participante</h2>
        <strong>Evento: {!!$evento->acronimo!!} {!!$evento->edicion!!}</strong>

        {!! Form::open(['route' => 'participantes.store', 'id' => 'participante-form']) !!}

            {!! Form::hidden('evento_id', $evento->id) !!}

            <label for="participante-name">Seleccionar Usuario:</label>

            {!! Form::select('usuario_id', $usuarios->pluck('nombre_completo', 'id'), null, ['required' => 'required']) !!}
            <br><br>
            <button type="submit">Guardar</button>
        {!!Form::close()!!}
    </div>
</div>

@endsection

