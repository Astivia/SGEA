@extends('layouts.master')
<title>Informacion</title>

</head>
@section('Content')
<div class="container">
    <div class="contenido">
        <div class="read">
            <div class="read-img">
                <img src="{{asset('SGEA/public/assets/img/'.$Usu->foto)}}" alt="foto" style="width:250px">
            </div>
            <div class="read-info">
                <h1>{!!$Usu->nombre!!} {!!$Usu->ap_paterno!!} {!!$Usu->ap_materno!!}</h1>        
                <br><br>         
                <p><strong>CURP:</strong>  {!!$Usu->curp!!}</p>
                <p><strong>CORREO: </strong>{!!$Usu->email!!}</p>
                <p><strong>SEXO: </strong>
                    @if ($Usu->curp[10] === 'H')
                        Masculino
                    @else
                        Femenino
                    @endif
                </p>
                <p><strong>TELEFONO: </strong>{!!$Usu->telefono!!}</p>
                @if(count($articulos) > 0)
                    <strong>Articulos en los que participa: </strong>
                    <ul style="margin-left: 4%">
                        @foreach ($articulos as $art)
                            <li>- {!!$art->articulo->titulo_corto!!} <a href="{!! url('articulos/'.$art->evento->id.'/'.$art->articulo->id) !!}">
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
            <a href="{{url('usuarios/'.$Usu->id.'/edit')}}"><button><i class="las la-edit"></i> Modificar Usuario</button></a>
        @endrole
    </div>

</div>

@endsection