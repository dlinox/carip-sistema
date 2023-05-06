var url = "";
var $table, $table_asesor;
var baseurl;
$(document).ready(function () {


    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        onSelect: () => {
            console.log('Categoria Seleccionada');
        },
        minimumInputLength: 0,
        placeholder: 'Seleccione una categoria'
    });

    let select_vendedor = $('#selectVendedor');
    select_vendedor.mySelect2({
        url: baseurl + '/ventas/getVendedores',
        onSelect: () => {
            console.log('Vendedor Seleccionado');
        },
        minimumInputLength: 0,
        placeholder: 'Seleccione un Vendedor'
    });


    baseurl = $("#baseurl").val();
    var url = nameurl + '?json=true';

    $('#rango').myDateRangePicker();

    function botones(id, $ar, completado) {

        if ($('#_tipo-usuario').val() == '1' || completado == 1) {
            class_completar = 'hidden';
        } else {
            class_completar = '';
        }

        html = `
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <li><a href='{baseurl}ventas/crear/{id}' title="Editar venta" class="editar"><i class="fa fa-edit"></i> Editar Venta</a></li>
            <li><a href='{baseurl}ventas/editar/{id}' title="Editar Datos" class="editar"><i class="fa fa-edit"></i> Editar Datos</a></li>
            <li ` + class_completar + `><a href='{baseurl}ventas/completar/{id}' title="Completar venta" class="completar"><i class="fa fa-edit"></i> Completar</a></li>
            <li><a href='{baseurl}ventas/eliminar/{id}' title="eliminar venta" class="eliminar"><i class="fa fa-edit"></i> Eliminar</a></li>
            </ul>
        </div>
        <a href='{baseurl}ventas/imprimir/{id}' title="Imprimir Ficha" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-file-text"></i></a>
        `;
        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        $ar.append(html);

        $ar.find('.eliminar').click(function (e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Seguro que desea eliminar el alumno?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            }, function () {
                //swal("Deleted!", "Your imaginary file has been deleted.", "success");
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

        $ar.find('.editared').click(function () {
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

    $table = $('#mitabla').DataTable({
        //dom: 'Blfrtip',
        dom: "<'row'<'col-sm-6 col-lg-8'B><'col-sm-6col-lg-4 text-right'lf>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
                extend: 'excel',
                text: '<span><i class="fa fa-file-excel-o"></i> EXCEL</span>'
            },
            {
                extend: 'pdf',
                text: '<span><i class="fa fa-file-pdf-o"></i> PDF-H</span>',
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
        "ajax": {
            "url": url,
            "type": "POST",
            "data": function (data) {
                return $.extend(data, $('.ocform').serializeJSON());
            }
        },
        "rowCallback": function (row, data) {
            selected.push(data.ID);

            botones(data.DT_RowId, $(row).find('td .opts'), data.DT_COMPLETADO);
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

    /* Tabla de asesorados */

    var cols_asesorados = Array();
    // cols_asesorados.push({
    //     "data": null,
    //     "orderable": false,
    //     "width": "30",
    //     'render': function (data, type, full, meta) {
    //         if (typeof (buton) === "undefined") {
    //             return '<input type="checkbox">';
    //         } else {
    //             return buton;
    //         }
    //     }
    // })
    $('#asesorados_tabla').find('tr .ths').each(function (i, item) {
        cols_asesorados.push({
            "data": $(item).text(),
            className: "edit"
        });
    });

    console.log(cols_asesorados);

    $table_asesor = $('#asesorados_tabla').DataTable({
        "processing": true,
        "serverSide": true,
        "bResetDisplay": true,
        "order": [
            [1, "desc"]
        ],
        "ajax": {
            "url": $('#baseurl').val() + 'Ventas/ventas_asesorados',
            "type": "POST",
            "data": function (data) {
                return $.extend(data, $('.ocform').serializeJSON());
            }
        },
        "rowCallback": function (row, data) {
            selected.push(data.ID);

            //botones(data.DT_RowId, $(row).find('td .opts'));
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
        "columns": cols_asesorados,
        "columnDefs": [{
            "targets": [1],
            "visible": true,
            "searchable": true
        }]
    });
    /* End Tabla asesorados */

    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function () {

        $table.draw();
        $table_asesor.draw();
        totales();
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
            var directo_total = parseFloat(resp.directo.total),
                asesorado_total = parseFloat(resp.asesorado.total),
                total = directo_total + asesorado_total;

            console.log(directo_total);
            console.log(asesorado_total);
            console.log(directo_total);
            $('.comision_directa').html(directo_total.toFixed(2));
            $('.comision_asesor').html(asesorado_total.toFixed(2));
            $('.comision_total').html(total.toFixed(2));
        }
    });
}