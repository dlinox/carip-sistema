var url = "";
var $table;
var baseurl;
$(document).ready(function () {


    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        minimumInputLength: 0
    });


    baseurl = $("#baseurl").val();
    console.log(baseurl);
    var url = nameurl + '?json=true';

    $('#rango').myDateRangePicker();

    function botones(id, $ar) {
        html = `

        <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href='{baseurl}AtencionCliente/comprobante/{id}/ticket' title="Editar venta" class="" target="_blank"><i class="fa fa-file"></i> Ticket</a></li>
                <li><a href='{baseurl}AtencionCliente/comprobante/{id}/A4' title="Editar Datos" class="" target="_blank"><i class="fa fa-file-pdf"></i> A4</a></li>
            </ul>
        </div>

        <a href='{baseurl}AtencionCliente/editarpago/{id}' title="Editar Pago" class="btn btn-info btn-sm editar"><i class="fa fa-edit"></i></a>
        <a href='{baseurl}AtencionCliente/eliminarpago/{id}'title="Eliminar Pago" class="btn btn-danger btn-sm eliminar"><i class="fa fa-trash"></i></a>`;

        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        $ar.append(html);

        $ar.find('.comprobante').click(() => {
            $(this).load_dialog({
                title: $(this).attr("title"),
                loaded: function ($dlg) {
                    $dlg.find('form').submit(function () {
                        $(this).formPost(true, {}, function (data) {
                            if (data.exito) {
                                swal("", data.mensaje, "success");
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
        });

        $ar.find('.editar').click(function () {
            $(this).load_dialog({
                title: $(this).attr("title"),
                loaded: function ($dlg) {
                    $dlg.find('form').submit(function () {
                        $(this).formPost(true, {}, function (data) {
                            if (data.exito) {
                                swal("", data.mensaje, "success");
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

    $('#mitabla tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
    });

    $('#btn-select').click(function () {

        $.gs_loader.show();
        let array = [];
        $.map($table.rows('.selected').data(), function (item) {
            array.push(item.DT_RowId);
        });

        console.log(array.length);

        if (array.length > 0) {
            const ids = array.join('-');
            let url_comprobante = baseurl + 'AtencionCliente/comprobanteVarios/' + ids;

            setTimeout(function () {
                window.open(url_comprobante, '_blank');
            }, 1000);
        }
        else{
            alert('No se selecciono ninguna fila');
        }

        setTimeout(function () {
            $.gs_loader.hide();
        }, 1500);
        

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

            var ingreso = 0;

            ingreso = pagosalumnos;

            console.log(ingreso);

            $('.ingreso').html(ingreso.toFixed(2));
        }
    });

};