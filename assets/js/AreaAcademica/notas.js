var $table;
let old_btn_active = false;
$(document).ready(function () {
    'use strict';

    var baseurl = $('#baseurl').val();

    $('#box-cursoPeriodo').cursoPeriodo({
        byDocente: true,
        changeNivel: changeNivel
    });
    $('#guardar').on('click', clickGuardar);
    $('#eliminar-nota').on('click', clickEliminarNota);
    $('#agregar-nota').on('click', clickAgregarNota);

    //var notaaprobatoria = $('#form').find('input[name="notaaprobatoria"]').val();

    $('#table-body').on('input', 'input[type="number"]', actualizarPromedios);

    function mostrarTabla() {
        $table = $('#tablanotas').DataTable({
            //dom: 'tB',
            dom: "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6 col-lg-6'B><'col-sm-8 col-lg-4 pull-right'f>>",
            buttons: [{
                    extend: 'excel',
                    text: '<span><i class="fa fa-file-excel-o"></i> EXCEL</span>',
                    title: function () {
                        return $('#nombre_curso').val();
                    },
                },
                {
                    extend: 'pdf',
                    text: '<span><i class="fa fa-file-pdf-o"></i> PDF</span>',
                    title: function () {
                        return $('#nombre_curso').val();
                    },
                },
                {
                    extend: 'print',
                    text: '<span><i class="fa fa-print"></i> IMPRIMIR</span>',
                    title: function () {
                        return $('#nombre_curso').val();
                    },
                },
            ],
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
            "paging": false,
            "searching": false,
            "retrieve": true,
            "destroy": true
        });
    }

    function clickAgregarNota() {
        var colspan = parseInt($('#tabla-titulo').attr('colspan')) + 1;
        if (colspan) {
            $('#tabla-titulo').attr('colspan', colspan);
            $('#second-row').append('<th>' + colspan + '</th>')

            console.log($('#second-row').append('<th>' + colspan + '</th>'));

            $('#table-body').children().each(function () {
                var inputs = $(this).find('input[type="number"]'),
                    td_last = inputs.last().parent(),
                    cloned = td_last.clone(),
                    input = cloned.children().first(),
                    name = input.attr('name'),
                    regex_number = /[0-9]+/g,
                    id = parseInt(name.match(regex_number)[1]) + 1,
                    t = 0,
                    name_new = name.replace(regex_number, function (match) {
                        t++;
                        return (t == 2) ? id : match;
                    });

                input.attr('name', name_new);
                input.val(0);
                td_last.after(cloned);
            });

            actualizarPromedios();
        }
    }

    function clickEliminarNota() {
        var colspan = parseInt($('#tabla-titulo').attr('colspan')) - 1;
        if (colspan) {
            $('#tabla-titulo').attr('colspan', colspan);
            $('#second-row').children().last().remove();

            $('#table-body').children().each(function () {
                var inputs = $(this).find('input[type="number"]');
                if (inputs) {
                    inputs.last().parent().remove();
                }
            });

            actualizarPromedios();
        }
    }

    function clickGuardar() {
        $.ajax({
            type: 'post',
            url: baseurl + '/AreaAcademica/notas_guardar',
            data: $('#form').serialize(),
            dataType: 'json',
            beforeSend: function () {
                $.gs_loader.show();
            },
            success: function (response) {
                if (response.exito) {
                    swal("", response.mensaje, "success");
                } else {
                    swal("", response.mensaje, "error");
                }
            },
            complete: function () {
                $.gs_loader.hide();
            }
        });
    }

    function actualizarPromedios() {
        var notaaprobatoria = document.getElementById("notaaprobatoria").value;
        console.log(notaaprobatoria);
        $('#table-body').children().each(function () {
            var $this = $(this);

            var promedio = 0,
                total = 0;
            $this.find('input[type="number"]').each(function () {
                promedio += parseInt($(this).val());
                total++;
            });

            promedio = Math.round(promedio / total);
            if (promedio >= notaaprobatoria) {
                $this.removeClass('danger');
                $this.addClass('info');
            } else {
                $this.removeClass('info');
                $this.addClass('danger');
            }
            $this.find('input[type="text"]').val(promedio);

            console.log("Promedio ", promedio);
            //$('#promedio').text(promedio);
            $this.find('#promedio').text(promedio);


        });
    }

    function obtenerNotaAprobatoria(nota) {
        return nota;
    }

    //SELECCIONAR GRUPO

    let docente_id = '';
    let grupo_id = '';

    let select_docente = $('#selectDocente');
    select_docente.mySelect2({
        url: baseurl + '/usuario/s2getByTipo/5?_type=query',
        minimumInputLength: 0,
        onSelect: getCursos,
    });

    let select_curso = $('#selectCurso');

    function getCursos(e) {
        let value = $(e.currentTarget).val();
        docente_id = value;
        select_curso.mySelect2({
            url: baseurl + 'producto/s2ByDocente/' + value,
            minimumInputLength: 0,
            onSelect: getPeriodos,
        });
    };

    let select_periodo = $('#selectPeriodo');

    function getPeriodos(e) {
        let value = $(e.currentTarget).val();
        select_periodo.mySelect2({
            url: baseurl + 'Periodo/s2GetByCurso/' + value,
            minimumInputLength: 0,
            minimumResultsForSearch: -1,
            onSelect: getGrupos,
        });
    };

    let select_grupo = $('#selectGrupo');

    function getGrupos(e) {
        let value = '';
        if ($(e.currentTarget).val()) {
            value = $(e.currentTarget).val();
        } else {
            value = e;
        }
        if (value != '') {
            select_grupo.find('option').remove().end().append('<option disabled selected> -- Grupo -- </option>')
            $.ajax({
                url: baseurl + 'Grupos/getByPeriodo/' + this.value + '/' + docente_id,
                dataType: 'json',
                success: function (data) {
                    $.each(data.data, function (key, value) {
                        select_grupo.append("<option value=" + value.id + ">" + value.label + "</option>");
                    });
                    console.log(data);
                }
            });
        }
    }

    select_grupo.change(function (e) {
        getNiveles($(e.currentTarget).val())
    })

    function getNiveles(value) {
        let content_tab_niveles = $('#pills-tab-niveles');
        content_tab_niveles.html('');
        grupo_id = value;

        $.ajax({
            url: baseurl + 'Grupos/s2NivelesByGrupo/' + value,
            type: 'GET',
            dataType: 'json',
            success: function (json) {
                console.log(json);
                json.items.forEach(nivel => {
                    $(htmlTapNivel(nivel.text, nivel.id)).appendTo(content_tab_niveles);
                });
                $(htmlTapNivelPromedio()).appendTo(content_tab_niveles);

            },
            complete: function (xhr, status) {
                $('#table-body').empty();
            }
        });
    }

    function setNotasByNivel(nivel) {

        let btn_current = $('#tap-nivel-' + nivel);
        if (!old_btn_active) {
            old_btn_active = btn_current.addClass('active-btn');
        } else {
            old_btn_active.removeClass('active-btn');
            old_btn_active = btn_current.addClass('active-btn');
        }
        changeNivel(nivel)
    }
    window.setNotasByNivel = setNotasByNivel;

    function changeNivel(e) { //getNotas
        let nivel_id = e;
        //var grupo_id = $(e.currentTarget).val();
        $.ajax({
            url: baseurl + '/Grupos/getNotas/' + nivel_id + '/' + grupo_id,
            dataType: 'json',
            success: function (response) {
                if ($table != undefined) {
                    $table.destroy();
                }
                if (response.exito && response.data.alumnos.length > 0) {
                    let nivel_nombre = response.data.alumnos[0].nive_nombre;
                    var sesiones = response.data.alumnos[0].notas ? response.data.alumnos[0].notas.split(',').length : 0;
                    var notaaprobatoria = response.data.producto.notaaprobatoria;

                    $.each(response.data.alumnos, function (key, value) {
                        if (value.notas != null) {
                            value.notas = sesiones ? value.notas.split(',') : ['0'];
                        }
                        value.numero = key + 1;
                    });

                    if (sesiones == 0) {
                        sesiones = 1;
                    }

                    $('#table-body').empty();
                    $('#form').find('input[name="sesiones"]').val(sesiones);
                    $('#form').find('input[name="grup_id"]').val(grupo_id);
                    $('#form').find('input[name="notaaprobatoria"]').val(notaaprobatoria);
                    $('#second-row').empty();

                    $('#tabla-titulo').attr('colspan', sesiones);

                    $('#tabla-titulo').html(nivel_nombre + ' - NOTAS')

                    for (let index = 1; index <= sesiones; index++) {
                        var $th = $('<th>');
                        $th.text(index);
                        $('#second-row').append($th);
                    }
                    $('#notas').tmpl(response.data.alumnos).appendTo($('#table-body'));
                    actualizarPromedios();
                    $('#nombre_curso').val(response.data.producto.nombre + ' - ' + nivel_nombre);
                    mostrarTabla();
                } else {
                    $('#table-body').empty();
                }
            }
        }).done(function (response) {
            if (response.exito && response.data.alumnos.length > 0) {
                //$table.destroy();
                //mostrarTabla();
            }
        });

    }

    function getNivelForm(id) {
        let href = baseurl + 'AreaAcademica/getNivelForm/' + id;
        $(this).load_dialog({
            custom_url: href,
            title: 'Editar Nivel',
            loaded: function ($dlg) {
                $('input[type=hidden]#grupo').val(grupo_id);
                $dlg.find('form').submit(function () {
                    $(this).formPost(true, {}, function (data) {
                        if (data.exito) {
                            swal("", data.mensaje, "success");
                            $dlg.find('.close').click();
                            $('#tap-nivel-' + data.nivel).html(data.nivel_nombre)
                            changeNivel(data.nivel);
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
    }
    window.getNivelForm = getNivelForm;

    function eliminarNivel(id) {
        let href = baseurl + 'AreaAcademica/eliminarNivel/' + id;
        swal({
            title: "",
            text: "¿Seguro que desea eliminar este Nivel?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Eliminar",
            cancelButtonText: "Cancelar",
            cancelButtonClass: "btn-secondary",
            closeOnConfirm: false
        }, function () {
            $.gs_loader.show();
            $.getJSON(href, function (data) {
                $.gs_loader.hide();
                if (data.exito) {
                    swal("", data.mensaje, "success");
                    getNiveles(grupo_id);
                    getNivelPromedios();
                } else
                    swal("", data.mensaje, "error");
            });
        });
        return false;
    }
    window.eliminarNivel = eliminarNivel;

    function getNivelPromedios() {

        let btn_current = $('#tab-final');
        if (!old_btn_active) {
            old_btn_active = btn_current.addClass('active-btn');
        } else {
            old_btn_active.removeClass('active-btn');
            old_btn_active = btn_current.addClass('active-btn');
        }

        $.ajax({
            url: baseurl + '/Grupos/getNotasNiveles/' + grupo_id,
            dataType: 'json',
            success: function (response) {

                if ($table != undefined) {
                    $table.destroy();
                }
                let alumnos = response.data.alumnos;
                let producto = response.data.producto;

                console.log(response);
                if (response.exito && alumnos.length > 0) {

                    let nivel_nombre = 'PROMEDIOS';
                    var sesiones = alumnos[0].notas ? alumnos[0].notas.split(',').length : 0;
                    var notaaprobatoria = producto.notaaprobatoria;

                    $.each(alumnos, function (key, value) {
                        if (value.notas != null) {
                            value.notas = sesiones ? value.notas.split(',') : ['0'];
                        }
                        value.numero = key + 1;
                    });

                    let array_niveles = alumnos[0].niveles.split(',');

                    if (sesiones == 0) {
                        sesiones = 1;
                    }

                    $('#form').find('input[name="notaaprobatoria"]').val(notaaprobatoria);
                    $('#table-body').empty();
                    $('#second-row').empty();
                    $('#tabla-titulo').attr('colspan', sesiones);
                    $('#tabla-titulo').html(nivel_nombre + ' - NOTAS')
                    array_niveles.forEach((nivel) => {
                        var $th = $('<th>');
                        $th.text(nivel);
                        $('#second-row').append($th);
                    });
                    $('#notas').tmpl(alumnos).appendTo($('#table-body'));
                    actualizarPromedios();
                    $('#nombre_curso').val(producto.nombre);
                    console.log($table);
                    mostrarTabla();
                } else {
                    $('#table-body').empty();
                }
            }
        }).done(function (response) {
            if (response.exito && response.data.alumnos.length > 0) {
                //$table.destroy();
                //mostrarTabla();
            }
        });
    }
    window.getNivelPromedios = getNivelPromedios;

    $('.form-nivel').on('click', function () {
        $(this).load_dialog({
            title: $(this).attr("title"),
            loaded: function ($dlg) {
                console.log(grupo_id);
                $('input[type=hidden]#grupo').val(grupo_id);
                $dlg.find('form').submit(function () {
                    $(this).formPost(true, {}, function (data) {
                        if (data.exito) {
                            swal("", data.mensaje, "success");
                            $dlg.find('.close').click();
                            $(htmlTapNivel(data.nivel_nombre, data.nivel)).appendTo('#pills-tab-niveles');
                            getNiveles(grupo_id);
                            setNotasByNivel(data.nivel);
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

});

function htmlTapNivel(text, nivel) {
    return `
    <div class="btn-group">
        <button type="button" id="tap-nivel-` + nivel + `" class="btn btn-primary btn-flat btn-nivel" onclick="setNotasByNivel(` + nivel + `)">
        ` + text + `
        </button>
        <button type="button" class="btn btn-info  btn-flat dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li class="">
                <a class="dropdown-item" onclick="getNivelForm(` + nivel + `)">
                <i class="fa fa-pen"></i>
                Editar</a>
            </li>
            <li class="text-red">
                <a class="dropdown-item text-red" onclick="eliminarNivel(` + nivel + `)">
                <i class="fa fa-trash"></i>Eliminar</a>
            </li>
        </ul>
    </div>`;
}

function htmlTapNivelPromedio() {

    return `
    <div class="btn-group">
        <button type="button" id="tab-final" class="btn btn-warning btn-flat" onclick="getNivelPromedios()" >
        FINAL
        </button>
    </div>`;
}