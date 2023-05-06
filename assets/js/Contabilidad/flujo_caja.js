var url = "";
var $table;
var baseurl;

let total_flujo = 0;
$(document).ready(function () {
    baseurl = $("#baseurl").val();
    var url = nameurl + '?json=true';

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        minimumInputLength: 0,
        placeholder: 'Seleccione una categoria',
        onSelect: () => { total_flujo = 0 }
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
                <li><a href='{baseurl}contabilidad/flujoCajaComprobante/{id}/ticket' title="Imprimir Ticket" target="_blank"><i class="fa fa-file"></i> Ticket</a></li>
                <li><a href='{baseurl}contabilidad/flujoCajaComprobante/{id}/A4' title="Imprimir A4" target="_blank"><i class="fa fa-file-pdf"></i> A4</a></li>
            </ul>
        </div>
        <a href='{baseurl}Contabilidad/flujo_caja_crear/{id}' title="Editar rubro" class="btn btn-primary btn-sm editar"><i class="fa fa-edit"></i></a>
        <a href='{baseurl}Contabilidad/flujo_caja_eliminar/{id}'title="Eliminar Gastos" class="btn btn-danger btn-sm eliminar"><i class="fa fa-trash"></i></a>`;

        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        $ar.append(html);

        $ar.find('.editar').click(function () {
            $(this).load_dialog({
                title: $(this).attr("title"),
                loaded: function ($dlg) {
                    $dlg.find('form').submit(function () {
                        $(this).formPost(true, {}, function (data) {
                            if (data.exito) {
                                swal("", data.mensaje, "success");

                                let url_comprobante = baseurl + 'contabilidad/flujoCajaComprobante/' + data.id_res + '/' + data.comprobante;

                                setTimeout(function () {
                                    window.open(url_comprobante, '_blank');
                                }, 1000);

                                $dlg.find('.close').click();
                                $table.draw('page');
                            } else {
                                $dlg.find('.error-message').html(data.mensaje);
                                $dlg.find('.error-message').show();
                            }
                        });
                        return false;
                    })
                }
            });
            return false;
        });
        $ar.find('.eliminar').click(function (e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Seguro que desea eliminar el cliente?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                cancelButtonClass: "btn-secondary",
                closeOnConfirm: false
            }, function () {
                $.gs_loader.show();
                $.getJSON($this.attr('href'), function (data) {
                    $.gs_loader.hide();
                    if (data.exito) {
                        swal("", data.mensaje, "success");
                        $table.draw('page');
                        totales();
                    } else
                        swal("", data.mensaje, "error");
                });
            });
            return false;
        });
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
        cols.push({
            "data": $(item).text(),
            className: "edit"
        });
    });
    console.log(cols);

    $table = $('#mitabla').DataTable({
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
        "processing": true,
        "serverSide": true,
        "bResetDisplay": true,
        "order": [
            [1, "desc"]
        ],
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "Todo"]
        ],
        "ajax": {
            "url": url,
            "type": "POST",
            "data": function (data) {
                return $.extend(data, $('.ocform').serializeJSON());
            }
        },
        "rowCallback": function (row, data) {
            console.log(data);

            total_flujo +=  parseInt(data.DT_IMPORTE);
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
        "columnDefs": [{
            "targets": [1],
            "visible": true,
            "searchable": true
        }]
    });

    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function () {

        $table.draw();
        totales();
        return false;
    })

    totales();


    $('.crear').on('click', function () {
        $(this).load_dialog({
            title: $(this).attr("title"),
            loaded: function ($dlg) {
                $dlg.find('form').submit(function () {
                    $(this).formPost(true, {}, function (data) {
                        if (data.exito) {
                            swal("", data.mensaje, "success");

                            let url_comprobante = baseurl + 'contabilidad/flujoCajaComprobante/' + data.id_res + '/' + data.comprobante;

                            setTimeout(function () {
                                window.open(url_comprobante, '_blank');
                            }, 1000);

                            $dlg.find('.close').click();
                            $table.draw('page');
                            totales();
                        } else {
                            $dlg.find('.error-message').html(data.mensaje);
                            $dlg.find('.error-message').show();
                        }
                    });
                    return false;
                })
            }
        });
        return false;
    })
});

function totales() {
    form = $(".ocform");
    $.ajax({
        dataType: "json",
        method: "POST",
        url: baseurl + "Contabilidad/getresumenmensual",
        data: form.serialize(),
        success: function (resp) {
            var pagopersonal = parseFloat(resp.pagopersonal.egresopagopersonal);
            var flujocaja = parseFloat(resp.flujocaja.egresoflujocaja);
            var pagosalumnos = parseFloat(resp.pagos.ingresopagos);
            if (isNaN(pagopersonal)) {
                pagopersonal = 0;
            }
            if (isNaN(flujocaja)) {
                flujocaja = 0;
            }
            if (isNaN(pagosalumnos)) {
                pagosalumnos = 0;
            }
            console.log("Pagoperosnal: ", pagopersonal);
            console.log("flujocaja: ", flujocaja);
            console.log("pagos: ", pagosalumnos);

            var egresoflujocaja = 0;

            egresoflujocaja = flujocaja;

            console.log(egresoflujocaja);

            $('.egresoflujocaja').html(total_flujo);
        }
    });

};