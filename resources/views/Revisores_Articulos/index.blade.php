@extends('layouts.master')
    <title>CiDiCi</title>
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')
    <!-- <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-flex">
                <img src="" width="40px" alt="">
                <div class="brand-icons">
                    <span class="las la-bell"></span>
                    <span class="las la-user-circle"></span>
                </div>
            </div>
        </div>
        <div class="sidebar-main">
            <div class="sidebar-user">
                <img src="assets/img/perfil.png" alt="">
                <div>
                     <h3>Luis Eduardo</h3>
                     <span>Administrador</span>
                </div>                
            </div>
            <div class="sidebar-menu">
                <div class="menu-head">
                    <span>Catalogos</span>
                </div>
                <ul>
                    <li>
                        <a href="participantes.html">
                            <span class="las la-user"></span>
                            Participantes
                        </a>
                    </li>
                    <li>
                        <a href="eventos.html">
                            <span class="las la-calendar-alt"></span>
                            Eventos
                        </a>
                    </li>
                    <li>
                        <a href="areas.html">
                            <span class="las la-id-card"></span>
                            areas
                        </a>
                    </li>    
                    <li>
                        <a href="articulos.html">
                            <span class="las la-comment"></span>
                            Articulos
                        </a>
                    </li>
                    <li>
                        <a href="revisores.html">
                            <span class="las la-people-carry"></span>
                            Revisores
                        </a>
                    </li>                           
                </ul>
                <div class="menu-head">
                    <span>Aplicaciones</span>
                </div>
                <ul>
                    <li>
                        <a href="">
                            <span class="las la-calendar"></span>
                            Calendario
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <span class="las la-user"></span>
                            Contactos
                        </a>
                    </li>
                 
                                           
                </ul>
            </div>
        </div>
    </div> -->
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
                            <th>ARTICULO</th>
                            <th>AREA</th>
                            <th>REVISOR</th>
                        </tr>
                        @foreach ($RevArt as $ra)
                        <tr>
                            <td>{!!$ra->articulo->titulo!!}</td>
                            <td>{!!$ra->articulo->area->nombre!!}</td>
                            <td>{!!$ra->participante->nombre!!} {!!$ra->participante->apellidos!!}</td>
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
        </main>
    </div>
<script src="./js/scriptEventos.js"></script>
@endsection
</html>
