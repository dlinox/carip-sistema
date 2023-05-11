var url = "";
$(document).ready(function () {
  var url = nameurl + "?json=true";
  var $table;
  function botones(id, $ar) {
    html = `
        <a href='{baseurl}certificados/mencion_crear/{id}' title="Editar Mención" class="btn btn-primary btn-sm editar"><i class="fa fa-edit"></i></a>
        <a href='{baseurl}certificados/mencion_eliminar/{id}'title="Eliminar Mención" class="btn btn-danger btn-sm eliminar"><i class="fa fa-trash"></i></a>`;

    html = replaceAll(html, "{baseurl}", baseurl);
    html = replaceAll(html, "{id}", id);
    $ar.append(html);

    $ar.find(".editar").click(function () {
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

    $ar.find(".eliminar").click(function (e) {
      e.preventDefault();
      $this = $(this);
      swal(
        {
          title: "",
          text: "¿Seguro que desea eliminar la mención?",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Eliminar",
          cancelButtonText: "Cancelar",
          cancelButtonClass: "btn-secondary",
          closeOnConfirm: false,
        },
        function () {
          $.gs_loader.show();
          $.getJSON($this.attr("href"), function (data) {
            $.gs_loader.hide();
            if (data.exito) {
              swal("", data.mensaje, "success");
              $table.draw("page");
            } else swal("", data.mensaje, "error");
          });
        }
      );
      return false;
    });
  }

  var $dt = $("#mitabla"),
    conf = {
      data_source: url,
      cactions: ".ocform",
      order: [[1, "desc"]],
      onrow: function (row, data) {
        botones(data.DT_RowId, $(row).find("td .opts"));
      },
    };

  var buton = "<div class='opts'></div>";
  // $table = $dt.load_simpleTable(conf, true, buton);
  $table = $dt.load_simpleTable(conf, true, buton);
  $(".ocform input").DTFilter($table);

  $(".ocform input,.ocform select").change(function () {
    $table.draw();
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
