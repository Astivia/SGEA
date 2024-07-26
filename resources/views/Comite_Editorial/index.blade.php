@extends('layouts.master')
<title>Comite Editorial</title>

@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Miembros del Comite Editorial</h1>
            <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
        </div>   
    </div>


    <div id="create-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Añadir al Comite</h2>
            
        </div>
    </div>


@endsection