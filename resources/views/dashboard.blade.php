@extends('layouts.master')
    <title>Inicio</title>
    <link rel="stylesheet" href="./css/style-home.css">
</head>
@section('Content')
            
            
            <div class="container">
                <h1>Panel Principal</h1>
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
