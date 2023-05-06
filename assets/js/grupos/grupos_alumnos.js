var url = "";
$(document).ready(function ()
{
    // $('#curso_id').mySelect2({url: baseurl + 'producto/s2', onSelect: selectCurso});

    $('#box-curso-periodo').cursoPeriodo();

    var url = nameurl + '?json=true';
    var $table;
    function botones(data, $ar) {
        html =`<a type="button" href='{baseurl}grupos/eliminar_grupo_alumno/` + data.DT_grup_id + `/` + data.ID + `/'title="remover alumno del grupo" class="btn btn-warning btn-sm eliminar"><span class="glyphicon glyphicon-trash"></span></a>`;
        html = replaceAll(html, "{baseurl}", baseurl);
        html = replaceAll(html, "{id}", data.ID);
        $ar.append(html);
        $ar.find('.eliminar').click(function (e) {
            e.preventDefault();
            $this = $(this);
            swal({
                title: "",
                text: "¿Seguro que desea eliminar el alumno de este grupo,se perderan las notas y asistencias registradas?",
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
                    } else
                        swal("", data.mensaje, "error");
                });
            });
            return false;
        });
    }

    var $dt = $('#mitabla'),
        conf = {
            data_source: url,
            cactions: ".ocform",
            order: [
                [1, "desc"]
            ],
            onrow: function (row, data) { botones(data, $(row).find('td .opts')); }
        };

    var buton = "<div class='opts'></div>";
    $table = $dt.load_simpleTable(conf, true, buton);
    $('.ocform select, .ocform input').change(function ()
    {
        if($('select[name="grupo_id"]').val()) { $table.draw(); }
        return false;
    })
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
                    term: params.term,
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
            if (data.exito)
            {
                swal("", data.mensaje, "success");
                $('.id_alumno').val(null).trigger('change');
                $table.draw();
                $('.error-message-paquete').addClass('hidden');
            }
            else { $('.error-message-paquete').html(data.mensaje); $('.error-message-paquete').removeClass('hidden'); }
        });
        return false;
    })
});