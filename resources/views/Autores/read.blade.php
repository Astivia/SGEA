@extends('layouts.master')
<title>Informacion</title>

@section('Content')
<div class="container">
    <div class="contenido">
        <div class="read">
            <div class="read-img">
                <img src="{{asset('SGEA/public/assets/img/'.$autor->usuario->foto)}}" alt="foto" style="width:250px">
            </div>
            <div class="read-info">
                <h1>{!!$autor->usuario->nombre_completo!!}</h1> <br>       
                <p><strong>SEXO: </strong>
                    @if ($autor->usuario->curp[10] === 'H')
                        Masculino
                    @else
                        Femenino
                    @endif
                </p>         
                <p><strong>CURP:</strong>  {!!$autor->usuario->curp!!}</p>
                <p><strong>CORREO PERSONAL: </strong>{!!$autor->usuario->email!!}</p>
                <p><strong>CORREO DE CORRESPONDENCIA: </strong>{!!$autor->email!!}</p>
                
                <p><strong>TELEFONO: </strong>{!!$autor->usuario->telefono!!}</p>
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
        <br><br>
        <a href="{{ url()->previous() }}"><button><i class="las la-arrow-left"></i> Regresar</button></a>
        @role(['Administrador','Organizador'])
            <a href="{{url($autor->evento->id.'/autores/'.$autor->usuario->id.'/edit')}}"><button><i class="las la-edit"></i> Modificar autorario</button></a>
        @endrole
    </div>

</div>

@endsection