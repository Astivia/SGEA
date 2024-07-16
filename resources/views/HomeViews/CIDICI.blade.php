@extends('layouts.master')
<title>{!!$evento->acronimo!!}</title>

</head>
@section('Content')
    <div class="container">
        <h1>{!!$evento->nombre!!} ({!!$evento->acronimo!!} {!!$evento->edicion!!} )</h1>
        <img src="{{  asset('SGEA/public/assets/uploads/'.$evento->logo) }}" alt="Imagen" class="img-thumbnail img-selectable" style="width: 400px;">
        <div class="info">

            <strong>Edicion</strong>
            <p>{!!$evento->edicion!!}</p>
            
            <p><strong>Inicia: </strong>{!!$evento->fecha_inicio!!}</p>
            
            <p><strong>Finaliza en: </strong>{!!$evento->fecha_fin!!}</p>
        </div>

        <div class="links">
            <ul>
                <li><a href=""><i class="las la-book"></i>Talleres</a></li>
                <li><a href=""><i class="las la-book"></i>Conferencias</a></li>
                <li><a href=""><i class="las la-book"></i>ponencias</a></li>
                <li><a href="{{ url('articulos') }}"><i class="las la-book"></i>Articulos</a></li>
                @role(['Administrador','Organizador'])
                <li><a href="{{url('participantes/evento/'.$evento->id)}}"><i class="las la-user"></i>Participantes</a></li>
                <li><a href=""><i class="las la-book"></i>autores</a></li>
                <li><a href=""><i class="las la-book"></i>revisores</a></li>
                <li><a href="{{ url('areas') }}"><i class="las la-book"></i>areas</a></li>
                @endrole
                
            </ul>

        </div>
    </div>
    

@endsection