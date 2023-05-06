(function($)
{
    'use strict';

    $(document).ready(readyDocument);

    function readyDocument()
    {
        _init();

        $('form#form-2').submit(formSubmit);

        function formSubmit()
        {
            $.gs_loader.show();
            $(this).formPost(true, {}, function (data)
            {
                $.gs_loader.hide();
    
                if (data.exito) {
                    swal("", data.mensaje, "success");
                    setTimeout(function () {
                        $(location).attr('href', $('#baseurl').val() + 'Ventas/lista');
                    }, 1000);
                    $('.error-message-paquete').addClass('hidden');
    
                } else {
                    // swal("", data.mensaje, "error");
                    $('.error-message-paquete').html(data.mensaje);
                    $('.error-message-paquete').removeClass('hidden');
                }
            });
            return false;
        }

        function _init()
        {
            disableForm();
        }

        function disableForm()
        {
            $('#form-1').find('input').attr('disabled', true);
            $('#form-1').find('select').attr('disabled', true);
            $('#form-1').find('button').addClass('hidden');
        }
    }
}
)(jQuery);