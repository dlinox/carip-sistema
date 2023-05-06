var url = "";
var $table;
$(document).ready(function()
{
    $('#curso').s2producto({onSelect: selectCurso});
    $('#docente').s2docente();
    // $('#dias').multiselect();

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        minimumInputLength: 0,
        onSelect: selectCategoria,
    });


    function selectCategoria(e) {
        let cate_id = $(e.currentTarget).val();
        $('#curso').s2producto({categoria: cate_id});
    }

    function selectCurso(e)
    {
        var select_val = $(e.currentTarget).val();

        $.ajax(
        {
            url: baseurl + 'Periodo/getByCurso/' + select_val, 
            dataType: 'json', 
            success: function(response)
            {
                $('#periodo').empty();

                $.each(response.data, function(key, value)
                {
                    var $option = $('<option>');
                    $option.attr('value', value.id);
                    $option.text(value.label);
                    $('#periodo').append($option);
                });
                $('#periodo').removeAttr('disabled');
                
                var _peri_id = $('#_peri_id').val();
                if(_peri_id) { $('#periodo').val(_peri_id); }
            }
        });
    }

    $('form').submit(function ()
    {
        $(this).formPost(true, {}, function (data)
        {
            if (data.exito)
            {
                $('.error-message-paquete').addClass('hidden');
                setTimeout(function () {
                    $(location).attr('href', $('#baseurl').val() + 'grupos/lista');
                }, 10);
            }
            else
            {
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    });

    var _prod_id = $('#_prod_id').val();
    if(_prod_id) { $('#curso').s2producto('set', _prod_id); }
    var _docente_id = $('#_docente_id').val();
    if(_docente_id) { $('#docente').s2docente('set', _docente_id); }
});