@extends('layouts.master')
<title>Modificar datos</title>
</head>
@section('Content')
<div class="container">
    <h1>Modificar datos del miembro</h1>

    {!! Form::open(['method'=>'PATCH','url'=>'/comite_editorial/'.$part->id]) !!}
    <label for="event-name">Evento :</label>
    <select name="evento_id" require>
        @foreach ($Eventos as $e)
        <option value="{{$e->id }}" {{ $e->id == $part->evento_id ?'selected':''}}>
            {{ $e->acronimo }} {{ $e->edicion }}
        </option>
        @endforeach
    </select>

    <br><br>

    <label for="area">Participante :</label>
    <select name="participante_id" require>
        @foreach ($Participantes as $p)
        <option value="{{ $p->id }}" {{ $p->id == $part->participante_id ?'selected':''}}>
            {{ $p->nombre}} {{ $p->ap_pat}} {{ $p->ap_mat}}
        </option>
        @endforeach
    </select>
    <br><br>
    <button type="submit">Guardar Cambios</button>
    <a href="{{asset('SGEA/public/comite_editorial')}}"><button>Cancelar</button></a>
    {!!Form::close()!!}
</div>
@endsection