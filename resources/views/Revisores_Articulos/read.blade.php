@extends('layouts.master')
<title>Revisores de articulos</title>
@section('Content')
<div class="container">
    <h1>{!! $articulo->titulo !!}</h1>
    <div class="info">
        <p>
            @foreach ($articulo->autores->sortBy('orden') as $autor)           
                <a href="{{ url(session('eventoID').'/autor/'.$autor->usuario->id) }}" style="color:#000;">{{ $autor->usuario->nombre_autor }} </a> ,   
            @endforeach
        </p>
        <p>{!! $articulo->area->nombre !!}</p>
        @if(session('rol')!== "Autor")
            @if($articulo->estado !== "En Revision" && $articulo->estado !== "Recibido")
                <div class="final-info" style="displey:flex; align-items:center;text-align:center">
                    <p style="font-size:3vh;"><strong>PUNTUACION FINAL:</strong> <span id="Resultado"style="font-size:3vh;" ></span></p>
                </div>
            @endif
        @endif
        <strong style="font-size:2.2vh;">{!!$articulo->estado!!}</strong>
    </div>
    <div class="revisores">
        <table>
            <thead>
                <tr>
                    <th>Revisor</th>
                    @if(session('rol')!== "Autor")
                    <th>Puntuacion</th>
                    @endif
                    <th>similitud</th>
                    <th style="width:50vw;">comentarios</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articulo->revisores->sortBy('orden') as $revisor)
                    <tr>
                        <td><strong >Revisor {!!$revisor->orden!!}</strong><br>
                            @if(session('rol')!== "Autor")
                            <a href="{{ url('usuarios/'.$revisor->usuario->id) }}">{!!$revisor->usuario->nombre_completo!!}</a> 
                            @endif
                       </td>
                       @if(session('rol')!== "Autor")
                        <td><strong style="font-size:20px;" class = "puntuacion"> {{ $revisor->puntuacion ?? 'No definido' }}</strong>
                        @endif
                        </td>
                        <td>{{$revisor->similitud ?? 'No definido'}}</td>
                        <td style="width:50vw;text-align:justify;">{{$revisor->comentarios ?? 'No hay comentarios'}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>

</div>
@endsection

<script>
  // Función para calcular el promedio de las puntuaciones
  function calcularPromedio() {
    const puntuaciones = document.querySelectorAll(".puntuacion");
    let suma = 0;
    let count = 0;
    puntuaciones.forEach(puntuacion => {
      const valor = parseFloat(puntuacion.textContent);
      if (!isNaN(valor)) {
        suma += valor;
        count++;
      }
    });
    const promedio = count > 0 ? (suma / count).toFixed(0) : "N/A";
    const result= document.getElementById("Resultado");

    if(result){
        if(promedio>=21){
            result.style.color='348aa7';
        }else if(promedio<=21){
            result.style.color='#eb7434';
        }
        result.textContent=promedio+'/30';
      }
    }
  window.onload = calcularPromedio;
</script>