var url = "";

$(document).ready(function () {
  // Carga los datos en el campo Select2

  const mencion = $("#aux_menc");
  const alumno = $("#aux_alum");

  $("#persona").mySelect2({
    url: baseurl + "persona/s2",
    onSelect: (e) => {
      let value = $(e.currentTarget).val();
      console.log(value);
    },
  });

  if (alumno.val()) {
    console.log("hay dato alumno");
    $("#persona").append(
      `<option value="${alumno.val()}">${alumno.attr("data-name")}</option>`
    );
  }

  // $("#persona").append(`<option value="3">Holaa</option>`);

  $("#mencion").mySelect2({
    url: baseurl + "certificados/mencionS2",
    onSelect: (e) => {
      let value = $(e.currentTarget).val();
      console.log(value);
    },
  });

  if (mencion.val()) {
    console.log(mencion.attr("data-name"));
    console.log(mencion.val());
    console.log("hay dato menc");
    $("#mencion").append(
      `<option value="${mencion.val()}">${mencion.attr("data-name")}</option>`
    );
  }

  $("form").submit(function () {
    $.gs_loader.show();
    $(this).formPost(true, {}, function (data) {
      $.gs_loader.hide();
      if (data.exito) {
       

        swal("", data.mensaje, "success", );

        $('form')[0].reset();
        $("#persona").empty();
        $("#mencion").empty();

      } else {
        swal("", data.mensaje, "error");
      }
    });
    return false;
  });
});
