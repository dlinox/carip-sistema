var editar = false;
$(document).ready(function () {



    $('#btn-editar-empresa').on('click', () => {
        desbloquearFormularioEmpresa();
    })

    $('form').submit(function () {
        $.gs_loader.show();
        $(this).formPost(true, {}, function (data) {
            $.gs_loader.hide();

            if (data.exito) {
                swal("", data.mensaje, "success");
                $('.error-message-paquete').addClass('hidden');
                desbloquearFormularioEmpresa();

            } else {
                // swal("", data.mensaje, "error");
                $('.error-message-paquete').html(data.mensaje);
                $('.error-message-paquete').removeClass('hidden');
            }
        });
        return false;
    })

    function desbloquearFormularioEmpresa() {
        editar = !editar;
        editar ? $('#btn-editar-empresa').addClass('btn-danger').removeClass('btn-info').html('Cancelar') :
            $('#btn-editar-empresa').addClass('btn-info').removeClass('btn-danger').html('Editar');
        editar ? $("#btn-guardar-empresa").removeClass('no-edit').addClass('edit') :
            $("#btn-guardar-empresa").removeClass('edit').addClass('no-edit');

        $("#box-info-empresa :input").attr("readonly", !editar);
    }



    $('.btn-editar-logo').on('click', function () {
        $(this).load_dialog({
            title: $(this).attr("title"),
            loaded: function ($dlg) {
                $dlg.find('form').submit(function (e) {
                    e.preventDefault();
                    guardarLogo();
                })
            }
        });
        return false;
    })



});

var loadFile = function (event) {
    var output = document.getElementById('output-logo');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};

function guardarLogo() {

    $.gs_loader.show();

    let alerta = $('#alerta');

    alerta.addClass('esconder');
    let formData = new FormData();
    var files = $('#foto-logo')[0].files[0];
    formData.append('foto', files);

    $.ajax({
        url: baseurl + 'empresa/logoGuardar/',
        type: "post",
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (response) {

            if (response.exito) {
                if (!response.file) {
                    alerta.removeClass('esconder');
                    alerta.html(response.file_mensaje.error);

                    console.log(response.file_mensaje.error);
                } else {
                    setTimeout(function () {
                        $(location).attr('href', baseurl + 'empresa/');
                    }, 1000);

                    $.gs_loader.hide();
                    $('.modal').modal('hide')
                }

            } else {
                alerta.removeClass('esconder');
                alerta.html(response.mensaje);
            }
        }
    });

}