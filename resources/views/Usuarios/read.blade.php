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
            <p><strong>CURP: </strong>{!!$Usu->curp!!}</p>
            <p><strong>CORREO: </strong>{!!$Usu->email!!}</p>
            <p><strong>SEXO: </strong>{!!$sexo!!}</p>
            </div>
            
        </div>
    </div>
</div>


@endsection