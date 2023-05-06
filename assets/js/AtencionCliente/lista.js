var url = "";
var $table;
var baseurl;
$(document).ready(function () {
    baseurl = $("#baseurl").val();
    var url = nameurl + '?json=true';
    function botones(id, $ar) {
        html = `
        <div class="btn-group">
        <a type="button" href='{baseurl}AtencionCliente/vista/{id}' class="btn btn-success btn-sm ver"><i class="fa fa-eye"></i></a>
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <li><a href='{baseurl}AtencionCliente/crear/{id}' title="editar venta" class="editar"><i class="fa fa-edit"></i> Completar</a></li>
            </ul>
        </div>`;
        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        $ar.append(html);
    }
    var buton = "<div class='opts'></div>";
    var selected = [];
    var cols = Array();
    cols.push({
        "data": null,
        "orderable": false,
        "width": "30",
        'render': function (data, type, full, meta) {
            if (typeof (buton) === "undefined") {
                return '<input type="checkbox">';
            } else {
                return buton;
            }
        }
    })
    $('#mitabla').find('tr .ths').each(function (i, item) {
        cols.push({ "data": $(item).text(), className: "edit" });
    })
    $table = $('#mitabla').DataTable({
        dom: "<'row'<'col-sm-6 col-lg-8'B><'col-sm-6col-lg-4 text-right'lf>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "processing": true,
        "serverSide": true,
        "bResetDisplay": true,
        "order": [
            [2, "asc"]
        ],
        "ajax": {
            "url": url,
            "type": "POST",
            "data": function (data) {
                return $.extend(data, $('.ocform').serializeJSON());
            }
        },
        "rowCallback": function (row, data) {
            selected.push(data.ID);

            botones(data.DT_RowId, $(row).find('td .opts'));
        },
        "drawCallback": function (settings) {
            var api = this.api();
            selected = [];
            $.each(api.rows().data(), function () {
                selected.push(this.ID);
            })
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
        "searching": false,
        "pageLength": 10,
        "columns": cols,
        "columnDefs": [
            {
                "targets": [1],
                "visible": true,
                "searchable": true
            }
        ]
    });

    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function () {

        $table.draw();
        totales()
        return false;
    })
    totales();

});
function totales() {
    form = $(".ocform");
    $.ajax({
        dataType: "json",
        method: "POST",
        url: baseurl + "Ventas/getcomisiones",
        data: form.serialize(),
        success: function (resp) {
            console.log(resp);
            $('.comision_directa').html(parseFloat(resp.comision_directa).toFixed(2));
            $('.comision_asesor').html(parseFloat(resp.comision_asesor).toFixed(2));
            $('.comision_total').html(parseFloat(resp.comision_total).toFixed(2));
        }
    });
}