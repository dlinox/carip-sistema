var url = "";
$(document).ready(function () {
    // $('#curso_id').mySelect2({url: baseurl + 'producto/s2', onSelect: selectCurso});

    var _user_tipo_id = $('#_user_tipo_id').val(),
        ver_notas_class = _user_tipo_id == 4 ? 'hidden' : '',
        ver_asistencias_class = _user_tipo_id == 4 ? 'hidden' : '',
        agregar_pago_class = _user_tipo_id == 3 ? 'hidden' : '';

    $('#box-curso-periodo').cursoPeriodo();

    $('#persona').mySelect2({
        url: baseurl + 'persona/s2',
        onSelect: selectCurso
    });

    function selectCurso() {

    }

    var url = nameurl + '?json=true';
    var $table;

    function botones(data, $ar) {


        let _deuda = data.DEUDA.split(" ", 2);
        var fue_pagado_class = parseInt(_deuda[1]) <= 0 ? 'disabled' : '';

        html = `
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li class="">
                    <a type="button" href='` + baseurl + `AtencionCliente/agregarPagoForm/` + data.ID + `/' title="Agregar pago" class="agregar-pago ` + fue_pagado_class + `">
                        <i class="glyphicon glyphicon-shopping-cart"></i> Agregar pago
                    </a>
                </li>
                <li>
                    <a type="button" href='` + baseurl + `AtencionCliente/verNotas/` + data.ID + `/` + data.DT_grup_id + `' title="Ver notas" class="ver-notas ` + ver_notas_class + `">
                        <i class="glyphicon glyphicon-list-alt"></i> Ver notas
                    </a>
                </li>
            </ul>
        </div>`;

        $ar.append(html);


        $ar.find('.agregar-pago').on('click', function (e) {
            $(this).load_dialog({
                title: $(this).attr("title"),
                loaded: function ($dlg) {
                    $dlg.find('form').submit(function () {

       
                        $(this).formPost(true, {}, function (data) {
                            if (data.exito) {
                                let url_comprobante = baseurl + 'AtencionCliente/comprobante/' + data.pago_id + '/' + data.tipo_comp;
                                setTimeout(function () {
                                   // window.open(url_comprobante, '_blank');
                                }, 1000);
                                swal("", data.mensaje, "success");
                                $dlg.find('.close').click();
                                $table.draw('page');
                            } else {
                                $dlg.find('.error-message').html(data.mensaje);
                                // $dlg.find('.error-message').show();
                                $dlg.find('.error-message').removeClass('hidden');
                            }
                        });
                        return false;
                    })
                }
            });
            return false;
        });

        $ar.find('.ver-notas').on('click', function (e) {
            $(this).load_dialog({
                title: $(this).attr("title")
            });
            return false;
        });

        $ar.find('.ver-asistencias').on('click', function (e) {
            $(this).load_dialog({
                title: $(this).attr("title"),
                loaded: function ($dlg) {
                    var x = $dlg.find('form');
                    console.log(x);
                    $dlg.find('form').submit(function () {
                        $(this).formPost(true, {}, function (data) {
                            if (data.exito) {
                                swal("", data.mensaje, "success");
                                $dlg.find('.close').click();
                                // $table.draw('page');
                            } else {
                                $dlg.find('.error-message').html(data.mensaje);
                                // $dlg.find('.error-message').show();
                                $dlg.find('.error-message').removeClass('hidden');
                            }
                        });
                        return false;
                    })
                }
            });
            return false;
        });
    }

    function updateBarra(row, data) {
        let _pagado = data.PAGADO.split(" ", 2)[1];
        let _deuda = data.DEUDA.split(" ", 2)[1];
        //console.log(data.DT_CUOTAS);
        //console.log(data.PAGADO);
        //console.log(data.DEUDA);
        //console.log(data.DT_COSTO);

        var costocuota = data.DT_COSTO / data.DT_CUOTAS;
        var cuotaspagadas = _pagado / costocuota;
        //console.log("Pag:  ",cuotaspagadas);

        if (data.DT_es_becado == '1') {
            cuotaspagadas = data.DT_CUOTAS;
        }

        var iconos = "";

        for (let i = 0; i < data.DT_CUOTAS; i++) {

            if (cuotaspagadas > 0) {
                iconos = iconos + "<span class='pull-right badge bg-green'>-</span>";

                cuotaspagadas--;
            } else {
                iconos = iconos + "<span class='pull-right badge bg-red'>-</span>";
            }
        }

        //console.log("Iconos",iconos);

        var $icon = $(row).find('div.iconos'),
            $td = $icon.parent(),
            value = $icon.text();
        $td.empty();

        $td.html(iconos);



        var $barra = $(row).find('div.barra'),
            $td = $barra.parent(),
            value = $barra.text();
        $td.empty();
        if (data.DT_es_becado == '1') {
            $td.html('<span class="label label-info">BECADO</span>');
        } else {
            if (_deuda <= 0) {
                $td.html('<span class="label label-success">PAGO COMPLETO</span>');
            } else {
                $('#tmpl-barra-progreso').tmpl({
                    value: value
                }).appendTo($td);
            }
        }
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
                updateBarra(row, data);
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

    $table = $dt.load_simpleTable(conf, _user_tipo_id != 5, buton);

    $('.ocform select,.ocform input').change(function () {
        $table.draw();
        return false;
    });

    $('.id_alumno').select2({
        placeholder: 'Alumnos no asignados',
        allowClear: true,
        width: '100%',
        language: "es",
        minimumInputLength: Infinity,
        ajax: {
            url: baseurl + "grupos/buscar_alumnos",
            dataType: 'json',
            data: function (params) {
                return {
                    curso_id: $('select[name="curso_id"]').val(),
                    grupo_id: $('select[name="grupo_id"]').val()
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 0,
    });
    $('form').submit(function () {
        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();
            if (data.exito) {
                console.log('AQIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII');
                swal("", data.mensaje, "success");
                $('.id_alumno').val(null).trigger('change');
                $table.draw();
                $('.error-message-paquete').addClass('hidden');
            } else {
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    });

    $("#buscar").keydown(function (e) {
        // Capturamos qué telca ha sido
        var keyCode = e.which;
        // Si la tecla es el Intro/Enter
        if (keyCode == 13) {
            // Evitamos que se ejecute eventos
            event.preventDefault();
            // Devolvemos falso
            alert("alex");
            return false;
        }
    });



});