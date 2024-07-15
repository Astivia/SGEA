@extends('layouts.master')
<title>{!!$evento->acronimo!!}</title>

</head>
@section('Content')
    <div class="container">
        <h1>{!!$evento->nombre!!} ({!!$evento->acronimo!!} {!!$evento->edicion!!} )</h1>
        <img src="{{  asset('SGEA/public/assets/uploads/'.$evento->logo) }}" alt="Imagen" class="img-thumbnail img-selectable" style="width: 200px;">
        <div class="info">
            <div class="card">
                <a href=""><i class="las la-book"></i>Talleres</a>
            </div>
            <div class="card">
                <a href=""><i class="las la-book"></i>Conferencias</a>
            </div>
            <div class="card">
                <a href=""><i class="las la-book"></i>ponencias</a>
            </div>
          @role(['Administrador','Organizador'])
            <div class="card">
                <a href=""><i class="las la-book"></i>Articulos</a>
            </div>
            <div class="card">
                <a href=""><i class="las la-user"></i>Participantes</a>
            </div>
            <div class="card">
                <a href=""><i class="las la-book"></i>revisores</a>
            </div>
            <div class="card">
                <a href=""><i class="las la-book"></i>autores</a>
            </div>
            <div class="card">
                <a href=""><i class="las la-book"></i>areas</a>
            </div>
          @endrole

        </div>
    </div>
    

@endsection