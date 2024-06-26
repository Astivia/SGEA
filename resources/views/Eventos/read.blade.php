@extends('layouts.master')
    <title>Informacion</title>
</head>
@section('Content')
    <div class="container">
        <h1>Informacion del evento {!!$evento->acronimo!!} {!!$evento->edicion!!}</h1>
        <div class="info">
            <div class="image">
                <img src="{{asset('SGEA/public/assets/uploads/'.$evento->img)}}" alt="" style="width:400px;">
            </div>
            <strong>Nombre:</strong>
                <p>{!!$evento->nombre!!}</p>
            <strong>Acronimo</strong>
            <p>{!!$evento->acronimo!!}</p>
            <strong>Edicion</strong>
            <p>{!!$evento->edicion!!}</p>
            <strong>Fecha de Inicio</strong>
            <p>{!!$evento->fecha_inicio!!}</p>
            <strong>Fecha de Fin</strong>
            <p>{!!$evento->fecha_fin!!}</p>

        </div>
    </div>
@endsection