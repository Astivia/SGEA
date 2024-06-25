@extends('layouts.master')
    <title>Roles</title>
</head>
@section('Content')
<div class="container">
    <h1>Roles</h1>
    <div class="search-create">
        <input type="text" id="search-input" placeholder="Buscar eventos...">
        <button id="create-event-btn">Crear nuevo evento</button>
    </div>
</div>
<br><br>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>controles</th>
                </tr>

            </thead>
            <tbody>
                @foreach($roles as $rol)
                <tr>
                    <td>{!!$rol->nombre!!}</td>
                    <td>{!!$rol->descripcion!!}</td>
                    <td>
                    <a href="{{ route('roles.show', $rol->id) }}">
                        <button>detalles</button>
                    </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Controls</th>
            </tfoot>
        </table>

    </div>
@endsection