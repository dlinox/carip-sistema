var url = "";
$(document).ready(function () {
    // $('#curso_id').mySelect2({url: baseurl + 'producto/s2', onSelect: selectCurso});

    var _user_tipo_id = $('#_user_tipo_id').val(),
        ver_notas_class = _user_tipo_id == 4 ? 'hidden' : '';


    var url = nameurl + '?json=true';
    var $table;

    function botones(data, $ar) {

        html = `<a type="button" href='` + baseurl + `AreaAcademica/detalleAlumno/` + data.ID + `' title="Detalle Alumno" class="btn btn-success btn-sm">
            <i class="glyphicon glyphicon-eye-open"></i> 
        </a>
               `;
        $ar.append(html);

    }


    var $dt = $('#mitabla'),
        conf = {
            data_source: url,
            cactions: ".ocform",
            order: [
                [1, "asc"]
            ],
            pageLength: 50,
            lengthMenu: [20],
            onrow: function (row, data) {
                botones(data, $(row).find('td .opts'));
            },
            "language": {
                "processing": "Procesando...",
                "lengthMenu": "Mostrar _MENU_ por página",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron datos",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "infoFiltered": "(filtrado de _MAX_ registros totales)"
            },
        };

    var buton = "<div class='opts'></div>";

    $table = $dt.load_simpleTable(conf, true, buton);

    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function () {

        $table.draw();
        //totales()
        return false;
    })

});