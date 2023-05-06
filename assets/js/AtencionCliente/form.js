var url = "";
var $table;
$(document).ready(function () {
  $("#apoderado").hide();
  $('select[name="condiciones_id"]').change(function () {
    if ($('select[name="condiciones_id"]').val() == 2) {
      $("#apoderado").show();
    } else {
      $("#apoderado").hide();
    }
  })
  $('form').submit(function () {
    console.log("form");
    $.gs_loader.show();
    $(this).formPost(true, {}, function (data) {
      $.gs_loader.hide();
      console.log("data", data);

      if (data.exito) {
        swal("", data.mensaje, "success");
        if (data.url) {
          setTimeout(function () {
            $(location).attr('href', data.url);
          }, 1000);
        }
        $('.error-message-paquete').addClass('hidden');

      } else {
        // swal("", data.mensaje, "error");
        $('.error-message-paquete').html(data.mensaje);
        $('.error-message-paquete').removeClass('hidden');
      }
    });
    return false;
  })

});