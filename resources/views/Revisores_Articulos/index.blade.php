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
                        <th>AREA</th>
                        <th>Controles</th>
                    </tr>
                     @foreach ($RevArt as $ra)
                    <tr>
                        <td>{!!$ra->usuario->nombre_completo!!}</td>
                        <td>{!!$ra->articulo->titulo!!}</td>
                        <td>{!!$ra->articulo->area->nombre!!}</td>
                        
                        <td>
                            <a href="{!!'revisores_articulos/'.$ra->id.'/edit'!!}">
                                    <button>editar</button>
                                </a>
                                <a href="{{url('revisores_articulos/'.$ra->id)}}" 
                                    onclick="
                                    event.preventDefault(); 
                                    if (confirm('¿Estás seguro de que deseas eliminar este Revisor?')) 
                                    { document.getElementById('delete-form-{{ $ra->id }}').submit(); }">
                                <button>Eliminar</button>
                                </a>
                                <form id="delete-form-{{ $ra->id }}" action="{{ url('revisores_articulos/'.$ra->id) }}" method="POST" style="display: none;">
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
                <h2>Registro de Revisor</h2>
                {!! Form::open(['url'=>'/revisores_articulos']) !!}
                    {!! Form::hidden('evento_id', $evento->id) !!}
                    <label for="participante">Seleccionar participante :</label>
                    {!! Form::select('usuario_id', $parts, null, ['required' => 'required']) !!}

                    <label for="articulo">Seleccionar Articulo:</label>
                    {!! Form::select('articulo_id', $articulos, null, ['required' => 'required']) !!}

                    <br><br>
                    <button type="submit">Registrar Revisor</button>
                {!!Form::close()!!} 
            </div>
        </div>
        

    @endsection
    <script src="./js/script-eventos.js"></script>
