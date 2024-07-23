@extends('layouts.master')
    <title>Informacion</title>
</head>
@section('Content')
    <div class="container">
        <h1>Informacion del evento {!!$evento->acronimo!!} {!!$evento->edicion!!}</h1>
        <div class="info">
            <div class="image">
                <img src="{{asset('SGEA/public/assets/uploads/'.$evento->logo)}}" alt="" style="width:400px;">
            </div>
            <strong>Nombre:</strong>
                <p>{!!$evento->nombre!!}</p>
            <strong>Acronimo</strong>
            <p>{!!$evento->acronimo!!}</p>
            <strong>Edicion</strong>
            <p>{!!$evento->edicion!!}</p>
            
            <p><strong>Fecha de Inicio: </strong>{!!$evento->fecha_inicio!!}</p>
            
            <p><strong>Fecha de Fin: </strong>{!!$evento->fecha_fin!!}</p>
            <strong>Status del evento:</strong>
            <p>{!!$message!!}</p>
            <br><br>
            <a href="{{ url('eventos') }}"><button><i class="las la-arrow-left la-2x"></i></button></a>
            <a href="{{ route('participantes.evento.index', ['eventoId' => $evento->id]) }}"><button>Ver participantes</button></a>
            <a href="{{ route('revisores_articulos.evento.index', ['eventoId' => $evento->id]) }}"><button>Ver Revisores de Articulos</button></a>
            <a href=""><button id="create-event-btn">Ver Articulos</button></a>
            

        </div>
    </div>
@endsection