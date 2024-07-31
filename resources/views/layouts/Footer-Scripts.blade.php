</html>

<script src="{{asset('SGEA/public/js/script-master.js')}}"></script>
<!-- Se agrega script para tablas -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
    let temp = $("#btn1").clone();
    $("#btn1").click(function(){
        $("#btn1").after(temp);
    });

    $(document).ready(function(){
        var table = $('#example').DataTable({
            orderCellsTop: true,        
            fixedHeader: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: -1 }  // Deshabilitamos la ordenación en todas las columnas
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
            }
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

@if(session('reload'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchSidebar();
        });

        function fetchSidebar() {
            fetch('{{ route('get.sidebar') }}')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('sidebar').innerHTML = html;
                });
        }
    </script>
@endif