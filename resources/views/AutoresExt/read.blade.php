@extends('layouts.master')
    <title>Detalles</title>

@section('Content')
    <div class="container">
        <h1>{!!$ae->nombre!!} {!!$ae->ap_pat!!} {!!$ae->ap_mat!!}</h1>
        <br><br>
        <img src="" alt="foto" style="width:250px">
        <br><br>
        <p><strong>Afiliacion: </strong>{!!$ae->afiliacion!!}</p>

    </div>



@endsection