<div>
    <table class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Controles</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($Areas as $area)
            <tr>
                <td>{!!$area->nombre!!}</td>
                <td>{!!$area->descripcion!!}</td>
                <td>
                    <a href="{!!'areas/'.$area->id.'/edit'!!}">
                        <button>editar</button>
                    </a>
                    <a href="{{url('areas/'.$area->id)}}"
                        onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $area->id }}').submit(); }">
                        <button>Eliminar</button>
                    </a>
                    <form id="delete-form-{{ $area->id }}" action="{{ url('areas/'.$area->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
        
    </table>
</div>
