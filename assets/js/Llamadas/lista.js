var url = "";
var $table;
var baseurl;
$(document).ready(function () {
    baseurl = $("#baseurl").val();
    var url = nameurl + '?json=true';

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        onSelect: () => { console.log('Categoria seleccionada.'); },
        minimumInputLength: 0
    });

    $('#rango').myDateRangePicker();

    function botones(id, $ar) {
        html = `
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <li><a href='{baseurl}Llamadas/crear/{id}' title="editar llamada" class="editar"><i class="fa fa-edit"></i> Editar</a></li>
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
        buttons: [
            {
                extend: 'excel',
                text: '<span><i class="fa fa-file-excel-o"></i> EXCEL</span>'
            },
            {
                extend: 'pdf',
                text: '<span><i class="fa fa-file-pdf-o"></i> PDF</span>',
                orientation: 'landscape',
            },
            {
                extend: 'print',
                text: '<span><i class="fa fa-print"></i> IMPRIMIR</span>'
            },
        ],
        "processing": true,
        "serverSide": true,
        "bResetDisplay": true,
        "order": [
            [1, "desc"]
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
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
        //totales()
        return false;
    })
    //totales();

});

// function totales() {
//     form = $(".ocform");
//     $.ajax({
//         dataType: "json",
//         method: "POST",
//         url: baseurl + "llamadas/getcomisiones",
//         data: form.serialize(),
//         success: function (resp) {
//             console.log(resp);
//             $('.comision_directa').html(resp.comision_directa);
//             $('.comision_asesor').html(resp.comision_asesor);
//             $('.comision_total').html(resp.comision_total);
//         }
//     });
// }