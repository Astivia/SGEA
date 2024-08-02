@extends('layouts.master')
<title>Informacion</title>

@section('Content')
<div class="container">
    <h1>Usuario</h1>
    <div class="contenido">
        <div class="read">
            <div class="profile-card">
                <div class="profile-header">
                    <img src="{{asset('SGEA/public/assets/img/'.$Usu->foto)}}" alt="foto" >
                    <h2 class="name">{!!$Usu->nombre!!} {!!$Usu->ap_paterno!!} {!!$Usu->ap_materno!!}</h2>
                    <p class="title"> {!!$Usu->curp!!}</p>
                </div>
                <div class="profile-body">
                <p class="descripcion"><strong>Correo:</strong> <a href="mailto:{!!$Usu->email!!}">{!!$Usu->email!!}</a></p>
                    <p class="descripcion"><strong>Sexo: </strong>
                        @if ($Usu->curp[10] === 'H')
                            Masculino
                        @else
                            Femenino
                        @endif
                    </p>
                    <p class="descripcion"><strong>Telefono: </strong><a href="tel:+52 1 {!!$Usu->telefono!!}">{!!$Usu->telefono!!}</a></p>
                    @if(count($articulos) > 0)
                        <strong>Articulos en los que participa: </strong>
                        <ul style="margin-left: 4%">
                            @foreach ($articulos as $art)
                                <li>
                                <i class="las la-arrow-right"></i>
                                    {!!$art->articulo->titulo_corto!!} <a href="{!! url('articulos/'.$art->evento->id.'/'.$art->articulo->id) !!}">
                                    <i class="las la-info-circle" style="color:#fff;"></i></a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
                
            
        </div>        
        <a href="{{ url()->previous() }}"><button><i class="las la-arrow-left"></i> Regresar</button></a>
        @role(['Administrador','Organizador'])
            <a href="{{url('usuarios/'.$Usu->id.'/edit')}}"><button><i class="las la-edit"></i> Modificar Usuario</button></a>
        @endrole
    </div>

</div>

@endsection