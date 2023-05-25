var url = "";

$(document).ready(function () {
  // Carga los datos en el campo Select2

  const input_prefix = $("input[name=cert_prefix]");
  const input_num = $("input[name=cert_num]");

  const mencion = $("#aux_menc");
  const alumno = $("#aux_alum");

  $("#persona").mySelect2({
    url: baseurl + "persona/s2",
    onSelect: async (e) => {
      let value = $(e.currentTarget).val();
    },
  });

  if (alumno.val()) {
    $("#persona").append(
      `<option value="${alumno.val()}">${alumno.attr("data-name")}</option>`
    );
  }

  // $("#persona").append(`<option value="3">Holaa</option>`);

  $("#mencion").mySelect2({
    url: baseurl + "certificados/mencionS2",
    onSelect: (e) => {
      let value = $(e.currentTarget).val();
      let titulo = $("#select2-mencion-container").attr("title");
      let iniciales = getIniciales(titulo);
      input_prefix.val(iniciales);
      getNextNum(iniciales);
    },
  });

  $("input[name=cert_prefix]").on("change", function () {
    getNextNum(input_prefix.val());
  });

  const getIniciales = (titulo) => {
    const palabras = titulo.split(" ");
    let primerasLetras = "";

    for (let i = 0; i < palabras.length; i++) {
      const primeraLetra = palabras[i].match(/[A-Z]/);
      if (primeraLetra) {
        primerasLetras += primeraLetra[0];
      }
    }
    return primerasLetras;
  };
  const getNextNum = async (prefix) => {
    let res = await fetch(baseurl + "certificados/next_num?prefix=" + prefix);
    let json = await res.json();
    input_num.val(json);
  };

  if (mencion.val()) {
    $("#mencion").append(
      `<option value="${mencion.val()}">${mencion.attr("data-name")}</option>`
    );
  }

  $("form").submit(function () {
    $.gs_loader.show();
    $(this).formPost(true, {}, function (data) {
      $.gs_loader.hide();
      if (data.exito) {
        swal("", data.mensaje, "success");

        $("form")[0].reset();
        $("#persona").empty();
        $("#mencion").empty();
      } else {
        swal("", data.mensaje, "error");
      }
    });
    return false;
  });

  $(".crear").on("click", function () {
    $(this).load_dialog({
      title: $(this).attr("title"),
      loaded: function ($dlg) {
        $dlg.find("form").submit(function () {
          $(this).formPost(true, {}, function (data) {
            if (data.exito) {
              swal("", data.mensaje, "success");
              $dlg.find(".close").click();
              $table.draw("page");
            } else {
              $dlg.find(".error-message").html(data.mensaje);
              $dlg.find(".error-message").show();
            }
          });
          return false;
        });
      },
    });
    return false;
  });
});
