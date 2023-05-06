var url = "";
var $table;
$(document).ready(function () {

    let select_categoria = $('#selectCategoria');
    select_categoria.mySelect2({
        url: baseurl + '/areaAcademica/s2GetCategorias',
        minimumInputLength: 0,
        onSelect: selectCategoria,
    });


    function selectCategoria(e) {
        let cate_id = $(e.currentTarget).val();
        $('#producto').s2producto({categoria: cate_id});
    }

    var _persona_id = $('#_persona_id').val();
    var _producto_id = $('#_producto_id').val();
    $('#persona').s2persona();
    $('#producto').s2producto();

    if (_persona_id) {
        $('#persona').s2persona('set', _persona_id);
    }
    if (_producto_id) {
        $('#producto').s2producto('set', _producto_id);
    }

    $("#apoderado").hide();
    $('select[name="condiciones_id"]').change(function () {
        if ($('select[name="condiciones_id"]').val() == 2) {
            $("#apoderado").show();
        } else {
            $("#apoderado").hide();
        }
    })
    $('form').submit(function () {
        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();

            if (data.exito) {
                swal("", data.mensaje, "success");
                $('.error-message-paquete').addClass('hidden');
                setTimeout(function () {
                    $(location).attr('href', $('#baseurl').val() + 'Llamadas/lista');
                }, 1000);

            } else {
                // swal("", data.mensaje, "error");
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    })
});

function getDatosSunat() {
    let dni = $('#input-dni').val();
  
    if(!isNaN(dni) && dni.length == 8){
        $.gs_loader.show();
        $.ajax({
            url: baseurl + 'Persona/getPersonaApiSunat/' + dni,
            dataType: 'json',
            success: function (resp) {
                if(resp.success){
                    let nombres = resp.data.nombres;
                    let apellidos = resp.data.apellido_paterno + ' '+ resp.data.apellido_materno;
                    $('#input-nombre').val(nombres);
                    $('#input-apellidos').val(apellidos);
                }
                else{
                    alert('No se puedo encontrar registros para el dni, o paso la cantidad de consultas mensuales (200)');
                }
            },
            complete: () =>{
                $.gs_loader.hide();
            }
        });
    }
    else{
        alert('Error, intente mas tarde')
        $.gs_loader.hide();
    }

}