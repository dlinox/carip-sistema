$(document).ready(function () {
    'use strict';

    var baseurl = $('#baseurl').val();

    $('#box-cursoPeriodo').cursoPeriodo({
        byDocente: true,
        changeGrupo: changeGrupo
    });
    $('#guardar').on('click', clickGuardar);

    function changeGrupo(e) {
        var grupo_id = $(e.currentTarget).val();

        $.ajax({
            url: baseurl + '/Grupos/getPagos/' + grupo_id,
            dataType: 'json',
            success: function (response) {
                $('#table-body').empty();

                var sesiones = response.data.producto.sesiones;
                $('#form').find('input[name="sesiones"]').val(sesiones);
                $('#form').find('input[name="grup_id"]').val(grupo_id);

                //$('#tabla-titulo').attr('colspan', sesiones);
                //$('#second-row').empty();

                /*for(let index = 1; index <= sesiones; index++)
                {
                    var $th = $('<th>');
                    $th.text(index);
                    $('#second-row').append($th);
                }

                $.each(response.data.alumnos, function(key, value)
                {
                    var total_commas = null;

                    if(value.notas)
                    {
                        total_commas = (value.notas.match(/,/g) || []).length;
                    }
                    else { value.notas = '0'; total_commas = 0; }

                    for(let index = total_commas; index < sesiones - 1; index++) { value.notas += ',0'; }
                    value.notas = value.notas.split(',');
                    value.numero = key + 1;
                });*/
                $('#tmpl-pagos').tmpl(response.data.alumnos).appendTo($('#table-body'));
                $('.range').attr('max', response.data.producto.cuotas);
                $('.agregar-cuota').on('click', function (e) {
                    var $this = $(this);
                    var input = $this.closest('tr').find('input[type="number"]');
                    var value = parseInt(input.val()) + 1;
                    //input.val(input.val() + 1);
                    $this.closest('tr').find('input[type="number"]').val(value);
                    $this.closest('tr').find('input[type="range"]').val(value);

                    //console.log($this);
                });
            }
        })
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
});