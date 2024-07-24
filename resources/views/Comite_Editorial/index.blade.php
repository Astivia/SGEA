@extends('layouts.master')
<title>Comite Editorial</title>

@section('Content')
<div class="container">
    <h1>Comite Editorial</h1>
    <div class="search-create">
        <input type="text" id="search-input" placeholder="Buscar participante...">
        <button id="create-btn">Agregar Participante</button>
    </div>
</div>


<div id="create-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Añadir al Comite</h2>
        
    </div>
</div>


@endsection