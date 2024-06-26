@extends('layouts.master')
<title>Areas</title>
<!-- <link rel="stylesheet" href="./css/style-areas.css"> -->
<link rel="stylesheet" href="{{asset('SGEA/public/css/mainModificar.css')}}">


</head>

@section('Content')

<div class="container">
<h1>Areas</h1>
    <div class="search-create">
        <input type="text" id="search-input" placeholder="Buscar areas...">
        <button id="create-event-btn">Registrar nueva area</button>
    </div>
    
    @livewire ('areas-index')
    @livewireScripts
    
</div>

<div id="create-event-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Registrar Area</h2>
        {!! Form::open(['url'=>'/areas']) !!}
        <label for="area-name">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="area-description">Descripción:</label>
        <textarea id="description" name="descripcion" required></textarea>

        <button type="submit">Guardar</button>

        {!!Form::close()!!}
    </div>
</div>


@endsection
<script src="./js/script-areas.js"></script>