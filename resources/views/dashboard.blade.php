@extends('layouts.master')
    <title>CiDiCi</title>
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/style-areas.css">
</head>
@section('Content')


            <div class="page-header">
                <div>
                    <h1>Analytics Dashboard</h1>
                    <small>Estadisticas del evento actual</small>              
                </div>
                <div class="header-actions">
                <button id="create-event-btn" class="las la-file-export">Export</button>
                    <button>
                        <span class="las la-tools"></span>
                        Settings
                    </button>
                </div>
            </div>
            <div class="cards">
                <div class="card-single">
                    <div class="card-flex">
                        <div class="card-info">
                            <div class="card-head">
                                <span>Inscritos</span>
                                <small>Numero de personas interesadas</small>
                            </div>
                            <h2>17,663</h2>
                            <small>2% mas</small>
                        </div>
                        <div class="card-chart sucess">
                            <span class="las la-chart-line"></span>
                        </div>
                    </div>
                </div>
                <div class="card-single">
                    <div class="card-flex">
                        <div class="card-info">
                            <div class="card-head">
                                <span>Visto</span>
                                <small>Numero de personas que vieron el evento </small>
                            </div>
                            <h2>17,663</h2>
                            <small>2% mas</small>
                        </div>
                        <div class="card-chart danger">
                            <span class="las la-chart-line"></span>
                        </div>
                    </div>
                </div>
                <div class="card-single">
                    <div class="card-flex">
                        <div class="card-info">
                            <div class="card-head">
                                <span>Sin interes</span>
                                <small>Numero de personas que no estan interesadas</small>
                            </div>
                            <h2>17,663</h2>
                            <small>2% mas</small>
                        </div>  
                        <div class="card-chart yellow">
                            <span class="las la-chart-line"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <h1>Administrador de Usuarios</h1>
                <div class="search-add">
                    <input type="text" id="searchInput" placeholder="Buscar por nombre...">
                    <button id="addUserBtn">Agregar Usuario</button>
                </div>
                <table id="userTable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be dynamically added here -->
                    </tbody>
                </table>
                <div id="pagination">
                    <!-- Pagination will be dynamically added here -->
                </div>
            </div>
        
            <div id="userModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Agregar Usuario</h2>
                    <form id="userForm">
                        <label for="userName">Nombre:</label>
                        <input type="text" id="userName" required>
                        <label for="userEmail">Correo Electrónico:</label>
                        <input type="email" id="userEmail" required>
                        <button type="submit">Agregar</button>
                    </form>
                </div>
            </div>
@endsection
