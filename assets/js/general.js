var baseurl;
var nameurl;
var confDataTable;
$(document).ready(function () {
    baseurl = $('#baseurl').val();
    nameurl = $('#nameurl').val();

    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        opens: 'right',
        //startDate: moment(),
        locale: {
            "format": "DD/MM/YYYY",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        }
    });

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#daterange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        $('#desde').val(start.format('YYYY-MM-DD'));
        $('#hasta').val(end.format('YYYY-MM-DD'));
        if (typeof ($table) != 'undefined') {
            $table.draw();
        }
    }
    $('#daterange').daterangepicker({
        startDate: start,
        endDate: end,
        locale: {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Personalizado",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agusto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        },
        autoApply: true,
        opens: 'right',
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Días': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Este Año': [moment().startOf('year'), moment().endOf('year')],
        },
        "alwaysShowCalendars": true
    }, cb);

    cb(start, end);

    $('.select2').select2({
        width: 'resolve'
    });

    $('.btn-edit-perfil').on('click', function () {
        $(this).load_dialog({
            title: $(this).attr("title"),
            script: baseurl + "assets/js/usuario/formulario.js",
            loaded: function ($dlg) {
                $dlg.find('form').submit(function () {
                    $(this).formPost(true, {}, function (data) {
                        if (data.exito) {
                            swal("", data.mensaje, "success");
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            $dlg.find('.error').html(data.mensaje);
                            $dlg.find('.error').show();
                        }
                    });
                    return false;
                })
            }
        });
        return false;
    })
    $('.btn-permision').on('click', function (e) {
        e.preventDefault();
        $this = $(this);
        swal({
            title: "",
            text: "¿Seguro que desea habilitar este terminal?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "Habilitar",
            cancelButtonText: "Cancelar",
            cancelButtonClass: "btn-secondary",
            closeOnConfirm: false
        }, function () {
            $.gs_loader.show();
            $.getJSON($this.attr('href'), function (data) {
                $.gs_loader.hide();
                if (data.exito)
                    swal("", data.mensaje, "success");
                else
                    swal("", data.mensaje, "error");
            });
        });
        return false;
    })

    getNotificaciones();

})

function getNotificaciones() {
    const dom_notificaciones = $('#notificaciones');
    const total_noty = $("#notificaciones #total-noty");
    let span_sin_grupo = $('#notificaciones #sin-grupo span');
    let span_sin_completar = $('#notificaciones #sin-completar span');
    let span_gastos_dia = $('#notificaciones #gastos-dia span');

    $.ajax({
        dataType: "json",
        method: "GET",
        url: baseurl + "empresa/getNotificaciones",
        success: function (resp) {
            let datos = resp.notificaiones;
            if (resp.exito) { //addClass
                dom_notificaciones.removeClass('hidden');
                span_sin_grupo.text(datos.sin_grupo.cantidad)
                span_sin_completar.text(datos.sin_completar.cantidad)
                span_gastos_dia.text(datos.gastos_dia.total)
                total_noty.text( parseInt(datos.sin_grupo.cantidad) +  parseInt(datos.sin_completar.cantidad) );
            } else {
                dom_notificaciones.addClass('hidden');
            }
        },
    });
}