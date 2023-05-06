var url = "";
$(document).ready(function () {

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        minimumInputLength: 0,
        placeholder: ' Filtrar por categoria '

    });

    var url = nameurl + '?json=true';
    $('#rango').myDateRangePicker();
    var $table;

    function botones(id, $ar) {
        html = `
  
        <a href='{baseurl}Contabilidad/pagopersonal_crear/{id}/0' title="PAGO A PERSONAL:" class="btn btn-success btn-sm">Pagar  <i class="fa  fa-credit-card"></i></a>
        <a href='{baseurl}Contabilidad/adelantoDescuentoForm/{id}' title="ADELANTOS Y DESCUENTOS:" class="btn btn-danger btn-sm adelanto">  <i class="fa  fa-credit-card"></i></a>
        `;
        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", id);
        $ar.append(html);

        $ar.find('.adelanto').on('click', function () {
            $(this).load_dialog({
                title: $(this).attr("title"),
                loaded: function ($dlg) {
                    $dlg.find('form').submit(function () {
                        $(this).formPost(true, {}, function (data) {
                            if (data.exito) {
                                swal("", data.mensaje, "success");                      
                                let url_comprobante = baseurl + 'Contabilidad/comprobanteAdeDesc/' + data.id_res + '/A4';
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
        })


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
                    } else
                        swal("", data.mensaje, "error");
                });
            });
            return false;
        });
    }

    var $dt = $('#mitabla'),
        conf = {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            data_source: url,
            cactions: ".ocform",
            order: [
                [1, "asc"]
            ],
            language: {
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
            "columnDefs": [{
                "targets": [1],
                "visible": true,
                "searchable": true
            }],
            onrow: function (row, data) {
                botones(data.DT_RowId, $(row).find('td .opts'));
            }
        };

    var buton = "<div class='opts'></div>";
    $table = $dt.load_simpleTable(conf, true, buton);
    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function () {

        $table.draw();
        return false;
    })

    $('.crear').on('click', function () {
        $('.num').keyup(function () {
            var total = 0;
            total = parseInt($('#monto').val()) + parseInt($('#bono').val());
            console.log("Hola mundo");
            console.log(total);
            $("#total").val(total);
        });
        $(this).load_dialog({
            title: $(this).attr("title"),
            loaded: function ($dlg) {
                $dlg.find('form').submit(function () {
                    $('.num').keyup(function () {
                        var total = 0;
                        total = parseInt($('#monto').val()) + parseInt($('#bono').val());
                        console.log("Hola mundo");
                        console.log(total);
                        $("#total").val(total);
                    });

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