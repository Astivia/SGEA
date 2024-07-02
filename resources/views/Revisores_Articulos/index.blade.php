@extends('layouts.master')
    <title>Revisores de articulos</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')
        <div class="container">
            <h1>Revisores de Articulos en {!!$evento->acronimo!!} {!!$evento->edicion!!}</h1>
            <div class="search-create">
                <input type="text" id="search-input" placeholder="Buscar eventos...">
                <button id="create-event-btn"><i class="las la-plus-circle la-2x"></i></button>
            </div>
        </div>
        <br><br>
        <div class="container">
            @if($RevArt->isEmpty())
                <strong>No hay datos</strong>
            @else
                <table>
                    <tr>
                        <th>PARTICIPANTE</th>
                        <th>ARTICULO</th>
                        <th>ESTADO</th>
                        <th>Controles</th>
                    </tr>
                     @foreach ($RevArt as $ra)
                    <tr>
                        <td>{!!$ra->usuario->nombre_completo!!}</td>
                        <td>
                            {!!$ra->articulo->titulo!!}
                            <a href="{!! url('articulos/'.$ra->articulo->id) !!}"><i class="las la-info-circle"></i></a>
                        </td>
                        <td>
                            {!!$ra->articulo->estado!!}
                        </td>
                        <td>
                            <a href="{!!url('revisores_articulos/'.$ra.'/edit')!!}"><i class="las la-user-edit la-2x"></i></a>
                                <a href="{{url('revisores_articulos/'.$ra->id)}}" onclick=" event.preventDefault(); 
                                    if (confirm('¿Estás seguro de que deseas eliminar este Revisor en este articulo?')) 
                                    { document.getElementById('delete-form-{{ $ra->articulo->id }}').submit(); }">
                                <i class="las la-trash la-2x" style="color:red;"></i>
                                </a>
                                <form id="delete-form-{{ $ra->articulo->id }}" 
                                      action="{{ url('revisores_articulos/'.$ra->evento->id.'/'.$ra->usuario->id.'/'.$ra->articulo->id) }}"
                                      method="POST" style="display: none;">
                                    @method('DELETE')
                                    @csrf
                                </form>
                        </td>
                    </tr>
                     @endforeach
                </table>
            @endif
        </div>
        
        
        <div id="create-event-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Asignar Revisor</h2>
                {!! Form::open(['url'=>'/revisores_articulos']) !!}
                    {!! Form::hidden('evento_id', $evento->id) !!}
                    <label for="participante">Seleccionar participante :</label>
                    {!! Form::select('usuario_id', $parts, null, ['required' => 'required']) !!}
                    <label for="articulo">Seleccionar Articulo:</label>
                    {!! Form::select('articulo_id', $articulosOptions, null, ['required' => 'required']) !!}


                    <br><br>
                    <button type="submit">Registrar Revisor</button>
                {!!Form::close()!!} 
            </div>
        </div>
        

    @endsection
    <script src="./js/script-eventos.js"></script>
