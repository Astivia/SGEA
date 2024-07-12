@extends('layouts.master')
<title>Autores</title>

</head>
@section('Content')
    <div class="container">
        <h1>AUTORES</h1>
        <div class="search-create">
            <input type="text" id="search-input" placeholder="Buscar autores...">
            <button id="create-event-btn"><i class="las la-plus-circle la-2x"></i></button>
        
        </div>
    </div>
    <br><br>
    <div class="container">
        @if($autores->isEmpty())
            <strong>No hay autores registrados en este momento</strong>
        @else
            <table>
                <tr>
                    <th>PARTICIPANTE</th>
                    <th>AFILIACION</th>
                    <th>Controles</th>
                </tr>
                @foreach ($Autores as $autor)
                <tr>
                    <td>{!!$autor->usuario->nombre_completo!!} </td>
                    <td>{!!$autor->afiliacion!!}</td>
                    <td>
                        <a href="{!! 'usuarios/'.$autor->usuario->id !!}"><i class="las la-info-circle la-2x"></i></a>
                        <a href="{!!'autores/'.$autor->id.'/edit'!!}">
                            <i class="las la-user-edit la-2x"></i>
                        </a>
                        <a href="{{url('autores/'.$autor->id)}}"
                            onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $autor->id }}').submit(); }">
                            <i class="las la-user-minus la-2x"></i>
                        </a>
                        <form id="delete-form-{{ $autor->id }}" action="{{ url('autores/'.$autor->id) }}" method="POST"
                            style="display: none;">
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
        <h2>Registrar Autor</h2>
        {!! Form::open(['url'=>'/autores']) !!}

        <label for="participante-name">Seleccione un Usuario del sistema:</label>
        {!! Form::select('usuario_id', $usuarios->pluck('nombre_completo', 'id'), null, ['required' => 'required']) !!}
        
       <br>

        <label for="afiliacion">Afiliacion:</label>
        <input type="text" id="afiliacion" name="afiliacion" required>


        <button type="submit">Agregar Autor</button>
        {!!Form::close()!!}
    </div>
</div>
    
@endsection