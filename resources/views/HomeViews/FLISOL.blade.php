@extends('layouts.master')
<title>{!!$evento->acronimo!!}</title>

</head>
@section('Content')
    <div class="container">
        <h1>{!!$evento->nombre!!} ({!!$evento->acronimo!!} {!!$evento->edicion!!} )</h1>
    </div>

@endsection