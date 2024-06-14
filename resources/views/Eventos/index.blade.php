@extends('layouts.master')
    <title>CiDiCi</title>
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')

    <div class="main-content">
        <header>
            <div class="menu-toggle">
                <label for="">
                    <span class="las la-bars"></span>
                </label>
            </div>
            <div class="header-icons">
                <span class="las la-search"></span>
                <span class="las la-bookmarks"></span>
                <span class="las la-sms"></span>
            </div>
        </header>
        <main>
            <div class="container">
                <h1>Eventos</h1>
                <div class="search-create">
                    <input type="text" id="search-input" placeholder="Buscar eventos...">
                    <button id="create-event-btn">Crear nuevo evento</button>
                </div>
                <div id="events-list"></div>
                <div id="pagination"></div>
            </div>

            <div class="container">
                <h1>Lista de Eventos</h1>
                <div class="info">
                    <table border=0>
                        <tr>
                            <th>NOMBRE</th>
                            <th>ACRONIMO</th>
                            <th>EDICION</th>
                            <th>FECHA INICIO</th>
                            <th>FECHA FIN</th>
                            <th>CONTROLES</th>
                        </tr>
                        @foreach ($Eventos as $e)
                        <tr>
                            <td>{!!$e->nombre!!}</td>
                            <td>{!!$e->acronimo!!}</td>
                            <td>{!!$e->edicion!!}</td>
                            <td>{!!$e->fecha_inicio!!}</td>
                            <td>{!!$e->fecha_fin!!}</td>
                            <td>
                                <a href="{!!'eventos/'.$e->id.'/edit'!!}">
                                    <button>editar</button>
                                </a>
                                <a href="{{url('eventos/'.$e->id)}}" onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $e->id }}').submit(); }">
                                <button>Eliminar</button>
                                </a>
                                <form id="delete-form-{{ $e->id }}" action="{{ url('eventos/'.$e->id) }}" method="POST" style="display: none;">
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
        
            <div id="register-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Se ha registrado correctamente.</p>
                </div>
            </div>
        
            <div id="create-event-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registro de Evento</h2>
                    {!! Form::open(['url'=>'/eventos']) !!}
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>

                        <label for="acronimo">Acrónimo:</label>
                        <input type="text" id="acronimo" name="acronimo" required>

                        <label for="fecha_inicio">Fecha de inicio:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" required>

                        <label for="fecha_fin">Fecha de fin:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" required>

                        <label for="edicion">Edición:</label>
                        <input type="number" id="edicion" name="edicion" required>

                        <button type="submit">Guardar evento</button>
                    {!!Form::close()!!} 
                </div>
            </div>
        
            <div id="edit-event-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Editar Evento</h2>
                    <form id="edit-event-form">
                        <label for="edit-event-name">Nombre del Evento:</label>
                        <input type="text" id="edit-event-name" required>
                        <label for="edit-event-description">Descripción:</label>
                        <textarea id="edit-event-description" required></textarea>
                        <label for="edit-event-photo">Fotografía:</label>
                        <input type="text" id="edit-event-photo" placeholder="URL de la imagen" required>
                        <label for="edit-event-speaker">Ponente:</label>
                        <input type="text" id="edit-event-speaker" required>
                        <label for="edit-event-date">Fecha del Evento:</label>
                        <input type="date" id="edit-event-date" required>
                        <label for="edit-event-time">Hora del Evento:</label>
                        <input type="time" id="edit-event-time" required>
                        <button type="submit">Actualizar Evento</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
@endsection
<script src="./js/scriptEventos.js"></script>

</html>
