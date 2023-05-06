var url = "";
var $table;
var baseurl;
var ctx = document.getElementById('myChart');
var ctx1 = document.getElementById('myChart1');

let myChart;
let myChart1;



$(document).ready(function () {
    baseurl = $("#baseurl").val();
    var url = nameurl + '?json=true';

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
        "startDate": "01/01/2021",
        "endDate": "01/01/2030"
    }, function (start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        minimumInputLength: 0,
        placeholder: 'Seleccione una categoria',
    });

    function botones(id, $ar) {
        html = `
        <!--a href='{baseurl}Contabilidad/pagopersonal_crear/{id}' title="Editar rubro" class="btn btn-primary btn-sm editar"><i class="fa fa-edit"></i></a-->
        <!--a href='{baseurl}Contabilidad/pagopersonal_eliminar/{id}'title="Eliminar rubro" class="btn btn-danger btn-sm eliminar"><i class="fa fa-trash"></i></a-->`;

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
        cols.push({ "data": $(item).text(), className: "edit" });
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
        "columnDefs": [
            {
                "targets": [1],
                "visible": true,
                "searchable": true
            }
        ]
    });

    var cols_asesorados = Array();


    $('.ocform input').DTFilter($table);

    $('.ocform input,.ocform select').change(function () {
        $table.draw();
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
        url: baseurl + "Contabilidad/getresumenmensual",
        data: form.serialize(),
        success: function (resp) {
            var pagopersonal = parseFloat(resp.pagopersonal.egresopagopersonal);
            var adelantos_personal = parseFloat(resp.adelantos.total_adelantos);
            var flujocaja = parseFloat(resp.flujocaja.egresoflujocaja);
            var pagosalumnos = parseFloat(resp.pagos.ingresopagos);
            var numventas = parseFloat(resp.ventas.numventas);

            var enero = parseFloat(resp.enero.numventas);
            var febrero = parseFloat(resp.febrero.numventas);
            var marzo = parseFloat(resp.marzo.numventas);
            var abril = parseFloat(resp.abril.numventas);
            var mayo = parseFloat(resp.mayo.numventas);
            var junio = parseFloat(resp.junio.numventas);
            var julio = parseFloat(resp.julio.numventas);
            var agosto = parseFloat(resp.agosto.numventas);
            var setiembre = parseFloat(resp.setiembre.numventas);
            var octubre = parseFloat(resp.octubre.numventas);
            var noviembre = parseFloat(resp.noviembre.numventas);
            var diciembre = parseFloat(resp.diciembre.numventas);

            if (isNaN(pagopersonal)) {
                pagopersonal = 0;
            }
            if (isNaN(flujocaja)) {
                flujocaja = 0;
            }
            if (isNaN(pagosalumnos)) {
                pagosalumnos = 0;
            }
            if (isNaN(numventas)) {
                numventas = 0;
            }
            if (isNaN(enero)) {
                enero = 0;
            }
            if (isNaN(febrero)) {
                febrero = 0;
            }
            if (isNaN(marzo)) {
                marzo = 0;
            }
            if (isNaN(abril)) {
                abril = 0;
            }
            if (isNaN(mayo)) {
                mayo = 0;
            }
            if (isNaN(junio)) {
                junio = 0;
            }
            if (isNaN(julio)) {
                julio = 0;
            }
            if (isNaN(agosto)) {
                agosto = 0;
            }
            if (isNaN(setiembre)) {
                setiembre = 0;
            }
            if (isNaN(octubre)) {
                octubre = 0;
            }
            if (isNaN(noviembre)) {
                noviembre = 0;
            }
            if (isNaN(diciembre)) {
                diciembre = 0;
            }
            /** */
            dibujar(pagosalumnos, pagopersonal, flujocaja, adelantos_personal, adelantos_personal);
            /** */

            dibujarventas(enero, febrero, marzo, abril, mayo, junio, julio, agosto, setiembre, octubre, noviembre, diciembre);


            var ingresototal = 0;
            var egresototal = 0;
            var liquidez = 0;

            ingresototal = pagosalumnos;
            egresototal = pagopersonal + flujocaja + adelantos_personal;
            liquidez = ingresototal - egresototal;

            console.log(ingresototal);
            console.log(egresototal);
            console.log(liquidez);

            $('.ingresototal').html( 'S/. ' + ingresototal.toFixed(2));
            $('.egresototal').html('S/. ' +  egresototal.toFixed(2));
            $('.liquidez').html('S/. ' +  liquidez.toFixed(2));
        }
    });

};

function dibujar(pagosalumnos, pagopersonal, flujocaja,adelantos_personal) {
    if (myChart) {
        myChart.destroy();
    }

    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pago de los Alumnos', 'Pago a los Trabajadores', 'Egresos del Flujo de Caja', 'Adelantos a Trabajadores'],
            datasets: [{
                label: 'INGRESOS, EGRESOS DEL AÑO',
                data: [pagosalumnos, pagopersonal, flujocaja, adelantos_personal],
                backgroundColor: [
                    'rgba(0, 169, 90, 0.8)',
                    'rgba(221, 75, 57, 0.8)',
                    'rgba(243, 156, 18, 0.8)',
                    'rgba(20, 15, 240, 0.8)'
                ],
                borderColor: [
                    'rgba(0, 169, 90, 1)',
                    'rgba(221, 75, 57, 1)',
                    'rgba(243, 156, 18, 1)',
                    'rgba(20, 15, 240, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)'
                    }
                }
            }
        }

    });
}

function dibujarventas(enero, febrero, marzo, abril, mayo, junio, julio, agosto, setiembre, octubre, noviembre, diciembre) {
    if (myChart1) {
        myChart1.destroy();
    }

    myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'],
            datasets: [{
                label: 'Ventas',
                data: [enero, febrero, marzo, abril, mayo, junio, julio, agosto, setiembre, octubre, noviembre, diciembre],
                backgroundColor: [
                    'rgba(0, 169, 90, 0.8)',
                    'rgba(221, 75, 57, 0.8)',
                    'rgba(243, 156, 18, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(201, 203, 207, 0.8)'
                ],
                borderColor: [
                    'rgba(0, 169, 90, 1)',
                    'rgba(221, 75, 57, 1)',
                    'rgba(243, 156, 18, 1)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(201, 203, 207, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        color: 'rgb(255, 99, 132)'
                    }
                }
            }
        }

    });
}
