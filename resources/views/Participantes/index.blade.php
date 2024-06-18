@extends('layouts.master')
    <title>Participantes</title>
    
    <link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')
            <div class="container">
                <h1>Participantes</h1>
                <div class="search-create">
                    <input type="text" id="search-input" placeholder="Buscar Participante...">
                    <button id="create-event-btn">Registrar Participante</button>
                </div>
                <div id="events-list"></div>
                <div id="pagination"></div>
            </div>
            <br><br>
            
            <div class="container">
                <h1>Lista de Participantes</h1>
                <div class="info">
                    <table border=0>
                        <tr>
                            <th>EVENTO</th>
                            <th>NOMBRE</th>
                            <th>APELLIDOS</th>
                            <th>EMAIL</th>
                            <th>CURP</th>
                            <th>Controles</th>
                        </tr>
                        @foreach ($Participantes as $part)
                        <tr>
                            <td>{!!$part->evento->acronimo!!} {!!$part->evento->edicion!!}</td>
                            <td>{!!$part->nombre!!}</td>
                            <td>{!!$part->apellidos!!}</td>
                            <td>{!!$part->email!!}</td>
                            <td>{!!$part->curp!!}</td>
                            <td>
                                <a href="{!!'participantes/'.$part->id.'/edit'!!}">
                                        <button>editar</button>
                                    </a>
                                    <a href="{{url('participantes/'.$part->id)}}" 
                                        onclick="
                                        event.preventDefault(); 
                                        if (confirm('¿Estás seguro de que deseas eliminar este registro?')) 
                                        { document.getElementById('delete-form-{{ $part->id }}').submit(); }">
                                    <button>Eliminar</button>
                                    </a>
                                    <form id="delete-form-{{ $part->id }}" action="{{ url('participantes/'.$part->id) }}" method="POST" style="display: none;">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div id="events-list"></div>
                <div id="pagination"></div>
            </div>
        
        
            <div id="create-event-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registrar Participante</h2>
                    {!! Form::open(['url'=>'/participantes']) !!}
                        <label for="event-name">Seleccionar evento :</label>
                        <select name="evento_id" require>
                        @foreach ($Eventos as $e)
                            <option value="{{ $e->id }}">{{ $e->acronimo }} {{ $e->edicion }}</option>
                        @endforeach
                        </select>
                        
                        <label for="participante-name">Nombre:</label>
                        <input type="text" id="participante-name" name="nombre" required>
                        
                        <label for="participante-lastName">Apellidos:</label>
                        <input type="text" id="participante-lastName" name="apellidos" required>
                        
                        <label for="participante-curp">CURP:</label>
                        <input type="text" id="participante-curp" name="curp" required>

                        <label for="participante-email">Email:</label>
                        <input type="text" id="participante-email" name="email" required>
                        
                        <label for="participante-pass">Contraseña:</label>
                        <input type="password" id="participante-password" name="password" required>

                        <button type="submit">Guardar</button>
                    {!!Form::close()!!}  
                </div>
            </div>
        

 @endsection
     <script src="./js/script-participantes.js"></script>
