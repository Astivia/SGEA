@extends('layouts.master')
<title>Informacion</title>

</head>
@section('Content')
<div class="container">
    <h1>{!!$Usu->nombre!!} {!!$Usu->ap_pat!!} {!!$Usu->ap_mat!!}</h1>
    <br><br>
    <img src="{{asset('SGEA/public/assets/img/'.$Usu->foto)}}" alt="foto" style="width:250px">
    <br><br>
    <p><strong>CURP: </strong>{!!$Usu->curp!!}</p>
    <p><strong>CORREO: </strong>{!!$Usu->email!!}</p>
    <p><strong>SEXO: </strong>{!!$sexo!!}</p>
</div>


@endsection