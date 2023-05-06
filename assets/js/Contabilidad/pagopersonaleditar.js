var url = "";
var $table, $table_asesor;
var baseurl;

$(document).ready(function () {
    baseurl = $("#baseurl").val();
    var url = nameurl + '?json=true';

    var idpersona = $('#idpersona').val();
    console.log(idpersona);

    let fecha_ultimo_pago = $('#ultima-fecha-pago').val() != '' ? $('#ultima-fecha-pago').val() : '01/01/2021';

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
            '2021': ['01/01/2021', '31/12/2021'],
            '2022': ['01/01/2022', '31/12/2022'],
            '2023': ['01/01/2023', '31/12/2023'],
            '2024': ['01/01/2024', '31/12/2024'],
            '2025': ['01/01/2025', '31/12/2025'],
            '2026': ['01/01/2026', '31/12/2026'],
            '2027': ['01/01/2027', '31/12/2027'],
            '2028': ['01/01/2028', '31/12/2028'],
            '2029': ['01/01/2029', '31/12/2029'],
            '2030': ['01/01/2030', '31/12/2030'],
        },
        "startDate": fecha_ultimo_pago,
        "endDate": "01/01/2030"
    }, function (start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });

    //document.getElementById('comisiondirecta').value = directo_total.toFixed(2);
    //document.getElementById('comisionasesores').value = asesorado_total.toFixed(2);


    function botones(id, $ar) {
        if ($('#_tipo-usuario').val() == '1') {
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
        </div>`;
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
                        totales(idpersona);
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
    console.log(cols);

    $table = $('#mitabla').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "processing": true,
        "serverSide": true,
        "bResetDisplay": true,
        "order": [
            [1, "desc"]
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




    $('.ocform input,.ocform select').change(function () {
        totales(idpersona);
        return false;
    })
    totales(idpersona);

    $('form#form-2').submit(function () {
        $('#rango_comicion').val($('#rango').val());
        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();

            if (data.exito) {
                swal("", data.mensaje, "success");
                setTimeout(function () {
                    $(location).attr('href', $('#baseurl').val() + 'Contabilidad/reportepagos');
                }, 1000);
                $('.error-message-paquete').addClass('hidden');

            } else {
                // swal("", data.mensaje, "error");
                console.log(data);
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    });



});
const horas_dictado = document.getElementsByClassName('horas-cant');
const costos_hora = document.getElementsByClassName('costo-hora');
const total_dictado = document.getElementsByClassName('curso-total');

for (let index = 0; index < total_dictado.length; index++) {
    totalHorasCosto(index);
}

for (let index = 0; index < horas_dictado.length; index++) {
    horas_dictado[index].addEventListener("change", function (e) {
        totalHorasCosto(index)   
    });
    costos_hora[index].addEventListener("change", function (e) {
        totalHorasCosto(index)   
    });
}

function totalHorasCosto(index) {
    total_dictado[index].value = parseFloat(horas_dictado[index].value) * parseFloat(costos_hora[index].value);
    sum();
}


function sum() {

    //par el rol de docente 
    let docente_cursos = 0.00;
    for (let index = 0; index < total_dictado.length; index++) {
        docente_cursos = parseInt(docente_cursos) + parseInt(total_dictado[index].value);
    }
    let monto = document.getElementById('monto').value;
    let bono = document.getElementById('bono[]').value;
    let comisiondirecta = document.getElementById('comisiondirecta[]').value;
    let comisionasesores = document.getElementById('comisionasesores[]').value;

    //let horas = document.getElementById('horas[]').value;
    //let costohora = document.getElementById('costohora[]').value;
    //document.getElementById('sumaDocente[]').value = horas * costohora;

    let adelanto = document.getElementById('adelanto').value;
    var descuento = document.getElementById('descuento').value;

    if (comisiondirecta == null) {
        comisiondirecta = 0;
    }
    if (comisionasesores == null) {
        comisionasesores = 0;
    }

    var resultado = parseInt(monto) + parseInt(bono) + parseInt(comisiondirecta) + parseInt(comisionasesores); // + parseInt(horas) * parseInt(costohora);

    resultado = resultado - parseInt(descuento) - parseInt(adelanto);

    if (isNaN(resultado)) {
        resultado = 0;
    }

    if (!isNaN(resultado)) {

        resultado = parseInt(docente_cursos) + parseInt(resultado);
        document.getElementById('total').value = resultado;
    }
}


window.onload = function () {
    sum();
}



function totales(idpersona, fecha) {
    $.ajax({
        dataType: "json",
        method: "POST",
        url: baseurl + "Contabilidad/getcomisiones/" + idpersona,
        data: {
            mes: fecha
        },
        success: function (resp) {
            console.log(resp);
            let directo_total = parseFloat(resp.directo.total),
                asesorado_total = parseFloat(resp.asesorado.total),
                descuento_total = parseFloat(resp.descuento.total),
                adelanto_total = parseFloat(resp.adelanto.total);
            document.getElementById('comisiondirecta[]').value = directo_total.toFixed(2);
            document.getElementById('comisionasesores[]').value = asesorado_total.toFixed(2);
            document.getElementById('descuento').value = descuento_total.toFixed(2);
            document.getElementById('adelanto').value = adelanto_total.toFixed(2);
            sum();
        }
    });
}