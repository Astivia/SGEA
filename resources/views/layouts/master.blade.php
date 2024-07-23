@include ('layouts.Header')

@include ('layouts.sidebar')
    <body>
        
        <div class="main-content">
            @include ('layouts.head')
            <main>
                <div class="options-bar">
                    <div class="menu-toggle">
                        <label for="">
                                <span class="las la-bars"></span>
                        </label>
                    </div>
                    <div class="header-icons">
                        <a href="{{ route('user.redirect') }}"><span class="las la-home"></span></a>
                        <a href="{{route('logout')}}"><span class="las la-door-closed"></span></a>
                    </div>
                </div>
                @if(session('error'))
                    <div class="alert alert-error" id="error-alert">
                        <i class="las la-exclamation-circle la-2x"></i>
                        <strong>{!! session('error') !!}</strong>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success" id="success-alert">
                        <i class="las la-check-circle la-2x"></i>
                        <strong> {!! session('success') !!}</strong>
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-info" id="info-alert">
                        <i class="las la-info-circle la-2x"></i>
                        <strong> {!! session('info') !!}</strong>
                    </div>
                @endif
                
                @yield('Content')
            </main>
        </div>
    </body>
@include ('layouts.Footer')
<script src="{{asset('SGEA/public/js/script-master.js')}}"></script>
<!-- Se agrega script para tablas -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script> 





<script>
    let temp = $("#btn1").clone();
    $("#btn1").click(function(){
        $("#btn1").after(temp);
    });

    $(document).ready(function(){
        var table = $('#example').DataTable({
        orderCellsTop: true,
        fixedHeader: true ,
            // se agrega 
            scrollY: '400px',
            scrollX: true,
            scrollCollapse: true,
            paging: true,
        columnDefs: [
            { orderable: false, targets: -1 }  // Deshabilitamos la ordenación en todas las columnas
        ]
        });

        $('#example thead tr').clone(true).appendTo('#example thead');

    $('#example thead tr:eq(1) th').each(function (i) {
        var title = $(this).text(); // es el nombre de la columna

        // Verificamos si no es la última columna antes de agregar el input
        if (i < $('#example thead tr:eq(1) th').length - 1) {
            $(this).html('<input type="text" placeholder="' + title + '" />');

            $('input', this).on('keyup change', function () {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        }
    }); 
    });
        

    function openModal(id) {
        var modal = document.getElementById('confirmModal');
        modal.style.display = 'block';
        // Guardamos el ID del registro que se desea eliminar en un atributo data del modal
        modal.setAttribute('data-record-id', id);
    }

    // Función para cerrar el modal
    function closeModal() {
        var modal = document.getElementById('confirmModal');
        modal.style.display = 'none';
    }

    // Función para eliminar el registro
    function deleteRecord(id) {
        var modal = document.getElementById('confirmModal');
        modal.style.display = 'none';
        // Enviamos el formulario de eliminación correspondiente
        document.getElementById('delete-form-' + id).submit();
    }
</script>
             
