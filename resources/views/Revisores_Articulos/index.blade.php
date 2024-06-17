@extends('layouts.master')
    <title>Revisores de articulos</title>
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')
            <div class="container">
                <h1>Revisores</h1>
                <div class="search-create">
                    <input type="text" id="search-input" placeholder="Buscar eventos...">
                    <button id="create-event-btn">Crear nuevo revisor</button>
                </div>
                <div id="events-list"></div>
                <div id="pagination"></div>
            </div>

            <div class="container">
                <h1>Revisores de Articulos</h1>
                <div class="info">
                    <table border=0>
                        <tr>
                            <th>REVISOR</th>
                            <th>ARTICULO</th>
                            <th>AREA</th>
                        </tr>
                        @foreach ($RevArt as $ra)
                        <tr>
                            <td>{!!$ra->participante->nombre!!} {!!$ra->participante->apellidos!!}</td>
                            <td>{!!$ra->articulo->titulo!!}</td>
                            <td>{!!$ra->articulo->area->nombre!!}</td>
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
                    <form id="create-event-form">
                        <label for="event-name">Nombre del Evento:</label>
                        <input type="text" id="event-name" required>
                        <label for="event-description">Descripción:</label>
                        <textarea id="event-description" required></textarea>
                        <label for="event-photo">Fotografía:</label>
                        <input type="text" id="event-photo" placeholder="URL de la imagen" required>
                        <label for="event-speaker">Ponente:</label>
                        <input type="text" id="event-speaker" required>
                        <label for="event-date">Fecha del Evento:</label>
                        <input type="date" id="event-date" required>
                        <label for="event-time">Hora del Evento:</label>
                        <input type="time" id="event-time" required>
                        <button type="submit">Guardar Evento</button>
                    </form>
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

    @endsection
    <script src="./js/script-eventos.js"></script>
