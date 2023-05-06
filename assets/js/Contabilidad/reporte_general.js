var url = "";
var $table_simple = false;
var baseurl;

let total_flujo = 0;
$(document).ready(function () {

    baseurl = $("#baseurl").val();

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        minimumInputLength: 0,
        placeholder: '* Seleccione la categoria',

    });

    $('#rango').daterangepicker({
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        showDropdowns: true,
        maxDate: new Date(),
        locale: {
            applyLabel: "Aceptar",
            format: "DD/MM/YYYY",
            cancelLabel: 'Cancelar',
            customRangeLabel: 'Rango personalizado'
        },
        ranges: {
            'Enero': ['01/01/' + '2021', '31/01/' + '2021'],
            'Febrero': ['01/02/' + '2021', '27/02/' + '2021'],
            'Marzo': ['01/03/' + '2021', '31/03/' + '2021'],
            'Abril': ['01/04/' + '2021', '30/04/' + '2021'],
            'Mayo': ['01/05/' + '2021', '31/05/' + '2021'],
            'Junio': ['01/06/' + '2021', '30/06/' + '2021'],
            'Julio': ['01/07/' + '2021', '31/07/' + '2021'],
            'Agosto': ['01/08/' + '2021', '31/08/' + '2021'],
            'Septiembre': ['01/09/' + '2021', '30/09/' + '2021'],
            'Octubre': ['01/10/' + '2021', '31/10/' + '2021'],
            'Noviembre': ['01/11/' + '2021', '30/11/' + '2021'],
            'Diciembre': ['01/12/' + '2021', '31/12/' + '2021'],

            'Mes actual': [moment().startOf('month'), moment()],
            'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, function (start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });

    const columns_table = [{
            "data": function (data) {
                let tipo = data.id.substr(0, 2);
                let id = data.id.substring(3);
                let res = "";
                switch (tipo) {
                    case "FC":
                        res = `<a href='` + baseurl + `contabilidad/flujoCajaComprobante/` + id + `/A4' title="Comprobante" class=" btn btn-info btn-sm" target="_blank"><i class="fa fa-file-pdf"></i></a>`;
                        break;
                    case "PP":
                        res = `<a href='` + baseurl + `Contabilidad/pagoPersonalComprobanteA4/` + id + `' title="Comprobante" class=" btn btn-info btn-sm" target="_blank"><i class="fa fa-file-pdf"></i></a>`;
                        break;
                    case "AL":
                        res = `<a href='` + baseurl + `AtencionCliente/comprobante/` + id + `/A4' title="Comprobante" class=" btn btn-info btn-sm" target="_blank"><i class="fa fa-file-pdf"></i></a>`;
                        break;

                    default:
                        res = `<a href='` + baseurl + `Contabilidad/comprobanteAdeDesc/` + id + `/A4' title="Comprobante" class=" btn btn-info btn-sm" target="_blank"><i class="fa fa-file-pdf"></i></a>`;
                        break;
                }
                return res;
            }
        },

        {
            "data": function (data) {
                let tipo = data.id.substr(0, 2);
                let res = "";
                switch (tipo) {
                    case "FC":
                        res = `<span class="label label-warning"> GASTO </span>`;
                        break;
                    case "PP":
                        res = `<span class="label label-info"> PAGO A PERSONAL </span>`;
                        break;
                    case "AL":
                        res = `<span class="label label-primary"> PAGO DE ALUMNO </span>`;
                        break;

                    default:
                        res = `<span class="label bg-teal"> ADELANTO </span>`;
                        break;
                }
                return res;
            }
        },
        {
            "data": "descripcion"
        },
        {
            "data": function (data) {
                let tipo = data.id.substr(0, 2);
                let res = "";
                switch (tipo) {
                    case "FC":
                        res = `<span class="label label-danger">EGRESO</span>`;
                        break;
                    case "PP":
                        res = `<span class="label label-danger">EGRESO</span>`;
                        break;
                    case "AL":
                        res = `<span class="label label-success">INGRESO</span>`;
                        break;

                    default:
                        res = `<span class="label label-danger"> EGRESO </span>`;
                        break;
                }
                return res;
            }
        },
        {
            "data": (data) => (data.importe)
        },
        {
            "data": "fecha"
        },

    ];


    $table_simple = $('#table-simple').DataTable({
        dom: "<'row'<'col-sm-6 col-lg-8'B><'col-sm-6col-lg-4 text-right'lf>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
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
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
        "processing": true,
        "serverSide": true,
        "bResetDisplay": true,
        searching: false,
        "ajax": {
            "url": baseurl + "Contabilidad/getDataResumen",
            "type": "POST",
            "data": function (data) {
                return $.extend(data, $('.ocform').serializeJSON());
            },
        },
        "drawCallback": function () {

            var api = this.api();

            let total_fl = api.column(4, {
                page: 'current'
            }).data().sum();
            console.log(total_flujo);
            document.getElementById('cap-total').innerHTML = total_fl;
        },
        "columns": columns_table,
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
    });

    $('.ocform input').DTFilter($table_simple);

    $('.ocform input,.ocform select').change(function () {
        $table_simple.draw();
        return false;
    })



});