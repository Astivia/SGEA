@extends('layouts.master')
    <title>permisos</title>
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
    <h1>Detalles de {!!$rol->nombre!!}</h1>
    <div class="info">
       <p> <span>Nombre del Rol: </span>{!!$rol->nombre!!}</p>
       <p> <span>Descripcion: </span>{!!$rol->descripcion!!}</p>
       <p> 
            <span>Permisos:</span><br>
                @foreach ($rol->permisos as $permiso)
                    {{ $permiso->nombre }}
                    @if (!$loop->last)
                            , 
                    @endif
                @endforeach
        </p><br><br>
        <span>Usuarios con este rol:</span><br>
        @foreach ($users as $u)
            {{$u->nombre}}
        @endforeach
    </div>

</div>
@endsection