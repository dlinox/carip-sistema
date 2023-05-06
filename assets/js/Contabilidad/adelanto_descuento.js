var url = "";
var $table;
var baseurl;

let total_flujo = 0;
$(document).ready(function () {
    baseurl = $("#baseurl").val();
    var url = nameurl + '?json=true';

    function botones(id, id_usua,  $ar) {//Contabilidad/adelantoDescuentoForm/{id}
        html = `
        <a href='{baseurl}Contabilidad/comprobanteAdeDesc/{id}' target="_black" title="Comprobante" class="btn btn-primary btn-sm"><i class="fa fa-file"></i></a>
        <a href='{baseurl}Contabilidad/adelantoDescuentoForm/{id_usua}' title="Nuevo" class="btn btn-success btn-sm editar"><i class="fa fa-plus"></i></a>
        <a href='{baseurl}Contabilidad/adelantoDescuentoForm/{id_usua}/{id}' title="Editar" class="btn btn-info btn-sm editar"><i class="fa fa-edit"></i></a>
        <a href='{baseurl}Contabilidad/adelantoDescuentoEliminar/{id}'title="Eliminar " class="btn btn-danger btn-sm eliminar"><i class="fa fa-trash"></i></a>`;

        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        html = replaceAll(html, "{id_usua}", id_usua);

        $ar.append(html);

        $ar.find('.editar').click(function () {
            $(this).load_dialog({
                title: $(this).attr("title"),
                loaded: function ($dlg) {
                    $dlg.find('form').submit(function () {
                        $(this).formPost(true, {}, function (data) {
                            if (data.exito) {
                                swal("", data.mensaje, "success");
                                //let url = baseurl + 'contabilidad/adelantoDescuento';
                                let url_comprobante = baseurl + 'Contabilidad/comprobanteAdeDesc/' + data.id_res + '/A4';
                                setTimeout(function () {
                                    window.open(url_comprobante, '_blank');
                                }, 1000);
                                
                                $table.draw();

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

            total_flujo += parseInt(data.DT_IMPORTE);
            selected.push(data.ID);
            botones(data.DT_RowId, data.DT_USUA_ID , $(row).find('td .opts'));
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
        return false;
    })
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
