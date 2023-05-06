var editar = false;
$(document).ready(function () {

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
                <li >
                    <a href='` + baseurl + `ventas/completar/` + data.ID + `/' title="Completar Alumno" class="completar">
                        <i class="fa fa-edit"></i> Completar</a></li>
                <li>
                    <a href='` + baseurl + `AreaAcademica/eliminarAlumno/` + data.ID + `' title="Completar Alumno" class="text-red eliminar">
                        <i class="fa fa-trash"></i> Eliminar</a></li>
            </ul>
        </div>`;

        $ar.append(html);

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

    function updateBarra(row, data) {
        let _pagado = data.PAGADO.split(" ", 2)[1];
        let _deuda = data.DEUDA.split(" ", 2)[1];
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

    $table = $dt.load_simpleTable(conf, true, buton);

    $('#btn-editar-persona').on('click', () => {
        desbloquearFormularioPersona();
    })

    $('form').submit(function () {
        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();

            if (data.exito) {
                swal("", data.mensaje, "success");
                $('.error-message-paquete').addClass('hidden');
                desbloquearFormularioPersona();

            } else {
                // swal("", data.mensaje, "error");
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    })

    function desbloquearFormularioPersona() {
        editar = !editar;
        editar ? $('#btn-editar-persona').addClass('btn-danger').removeClass('btn-info').html('Cancelar') :
            $('#btn-editar-persona').addClass('btn-info').removeClass('btn-danger').html('<i class="fas fa-user-edit"></i>');
        editar ? $("#btn-guardar-persona").removeClass('no-edit').addClass('edit') :
            $("#btn-guardar-persona").removeClass('edit').addClass('no-edit');

        $("#info-persona :input").attr("readonly", !editar);
    }

});