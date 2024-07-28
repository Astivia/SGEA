@extends('layouts.master')
<title>Revisores de articulos</title>

@section('Content')
<div class="container">

    <div class="info">
        <p><Strong>{!! $articulo->titulo !!}</Strong></p>
        <p>
            @foreach ($articulo->autores->sortBy('orden') as $autor)                  
                {{ $autor->orden }}. {{ $autor->usuario->nombre_autor }} <a href="{{ url('usuarios/'.$autor->usuario->id) }}"><i class="las la-info-circle la-1x"></i></a>      
            @endforeach
        </p>
        <p>{!! $articulo->area->nombre !!}</p>
    </div>

    <div class="revisores">
        @foreach($articulo->revisores as $revisor)
            <p>
                <strong>Revisor {!!$revisor->orden!!}: {!!$revisor->usuario->nombre_completo!!}</strong>
            </p>
            <p>
                <strong>Puntuacion:</strong>
                {!!$revisor->puntuacion!!}
            </p>
            <p>
                <strong>
                    Similitud:
                </strong>
                {!!$revisor->similitud!!}
            </p>
            
        @endforeach
    </div>
</div>
@endsection