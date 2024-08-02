@extends('layouts.master')
<title>Informacion</title>

@section('Content')
<div class="container">
    <h1>Autor</h1>
    <div class="contenido">
        <div class="read">
            <div class="profile-card">
                <div class="profile-header">
                    <img src="{{asset('SGEA/public/assets/img/'.$autor->usuario->foto)}}" alt="foto" style="">
                    <h2 class="name">{!!$autor->usuario->nombre_completo!!}</h2>
                    <p class="title"><strong></strong>  {!!$autor->usuario->curp!!}</p>
                </div>
                <div class="profile-body">
                    <p class="descripcion"><strong>CORREO</strong></p>
                    <ul style= "margin-left: 1vw;">
                        <li><p class="descripcion"><strong>Personal: </strong><a href="mailto:{!!$autor->usuario->email!!}">{!!$autor->usuario->email!!}</a></p></li>
                        <li><p class="descripcion"><strong>Correspondencia: </strong><a href="mailto:{!!$autor->email!!}">{!!$autor->email!!}</a></p></li>

                    </ul>
                    
                    <p class="descripcion"><strong>TELEFONO </strong>(<a href="tel:+52 1 {!!$autor->usuario->telefono!!}">{!!$autor->usuario->telefono!!}</a>)</p>
                    @if(count($articulos) > 0)
                        <strong class="descripcion" >ARTICULOS</strong>
                        <ul style="margin-left: 1vw;">
                            @foreach ($articulos as $art)
                                <li>
                                    <i class="las la-arrow-right"></i>
                                    <a href="{!! url('articulos/'.$art->evento->id.'/'.$art->articulo->id) !!}">{!!$art->articulo->titulo_corto!!} </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif     
                </div>
            </div>       
                             
        </div>
        
        <div class="button-read">
        <a href="{{ url()->previous() }}"><button><i class="las la-arrow-left"></i> Regresar</button></a>
        @role(['Administrador','Organizador'])
            <a href="{{url($autor->evento->id.'/autores/'.$autor->usuario->id.'/edit')}}"><button><i class="las la-edit"></i> Modificar autorario</button></a>
        @endrole
        </div>
        
    </div>
    

</div>

@endsection