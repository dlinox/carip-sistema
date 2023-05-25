var $table;
$(document).ready(function () {
  "use strict";

  var baseurl = $("#baseurl").val();

  $("#box-cursoPeriodo").cursoPeriodo({
    byDocente: true,
    changeGrupo: changeGrupo,
  });

  $("#guardar").on("click", clickGuardar);

  function changeGrupo(e) {
    let grupo_id = "";
    if (typeof e === "object") {
      grupo_id = $(e.currentTarget).val();
    } else {
      grupo_id = e;
    }

    $.ajax({
      url: baseurl + "Grupos/getCertificados/" + grupo_id,
      dataType: "json",
      success: function (response) {
        // if ($table != undefined) {
        //   console.log($table);
        //   $table.destroy();
        // }

        if (response.data.alumnos.length > 0) {
          $("#table-body").empty();
          $("#form").find('input[name="grup_id"]').val(grupo_id);

          $("#template_certificados")
            .tmpl(response.data.alumnos)
            .appendTo($("#table-body"));
        } else {
          $("#table-body").empty();
        }

        //console.log(response.data.alumnos);
        let alumnos_ids = [];

        $("#selectCategoria").val("");
        $("#selectMencion").val("");
        $("input[name='fecha_emision']").val("");

        response.data.alumnos.forEach((element) => {
          if (element.codigo) {
            $("#selectCategoria").val(element.categoria);
            $("#selectMencion").val(element.mencion);
            $("input[name='fecha_emision']").val(element.fecha);
          }
          alumnos_ids.push(element.alum_id);
        });
        //console.log(alumnos_ids);

        $("input[name='alumnos_ids']").val(alumnos_ids.join());
        $("#nombre_curso").val(response.data.producto.nombre);
        $("#input_nombre_curso").val(response.data.producto.nombre);

        mostrarTabla();
        //$table.destroy();
      },
    });
  }

  function mostrarTabla() {
    $table = $("#tablacertificados").DataTable({
      //dom: 'Blfrtip',
      dom:
        "<'row'<'col-sm-6 col-lg-8'B><'col-sm-6 col-lg-4 text-right'f>>" +
        "<'row'<'col-sm-12'tr>>",
      buttons: [
        {
          extend: "excel",
          text: '<span ><i class="fa fa-file-excel-o"></i> EXCEL</span>',
        },
        {
          extend: "pdfHtml5",
          text: '<span><i class="fa fa-file-pdf-o"></i> PDF</span>',
          title: function () {
            return $("#nombre_curso").val();
          },
        },
        {
          extend: "print",
          text: '<span><i class="fa fa-print"></i> IMPRIMIR</span>',
          title: function () {
            return $("#nombre_curso").val();
          },
        },
      ],
      columns: [{ orderData: [1] }, { orderData: [2] }],

      language: {
        processing: "Procesando...",
        lengthMenu: "Mostrar _MENU_ por página",
        search: "Buscar:",
        zeroRecords: "No se encontraron datos",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay registros disponibles",
        paginate: {
          first: "Primero",
          last: "Ultimo",
          next: "Siguiente",
          previous: "Anterior",
        },
        infoFiltered: "(filtrado de _MAX_ registros totales)",
      },
      paging: false,
      searching: false,
      retrieve: true,
      destroy: true,
    });
  }

  function clickGuardar() {
    console.log($("#form").serializeArray());
    $.ajax({
      type: "post",
      url: baseurl + "/Certificados/certificados_guardar",
      data: $("#form").serialize(),
      dataType: "json",
      beforeSend: function () {
        $.gs_loader.show();
      },
      success: function (response) {
        if (response.exito) {
          swal("", response.mensaje, "success");
          let grupo = $("#form input[name=grup_id]").val();
          //$table.destroy();

          console.log(grupo);
          changeGrupo(grupo);

          mostrarTabla();
        } else {
          swal("", response.mensaje, "error");
        }

        //$table.destroy();
        //mostrarTabla();
      },
      complete: function () {
        $.gs_loader.hide();
        //$table.destroy();
        //mostrarTabla();
      },
    });
    //mostrarTabla();
    //$table.destroy();
    //mostrarTabla();
  }

  let select_docente = $("#selectDocente");
  let select_curso = $("#selectCurso");
  let select_periodo = $("#selectPeriodo");
  let select_grupo = $("#selectGrupo");
  let docente_id = "";

  select_docente.select2({
    width: "100%",
    ajax: {
      url: baseurl + "/usuario/s2getByTipo/5?_type=query",
      dataType: "json",
      delay: 250,
      placeholder: "Buscar Docente",
      allowClear: true,
      processResults: function (data) {
        return {
          results: data.items,
        };
      },
      // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
    },
  });

  select_docente.change(function () {
    if (this.value != "") {
      select_curso
        .find("option")
        .remove()
        .end()
        .append("<option disabled selected> -- Curso -- </option>");

      docente_id = this.value;

      $.ajax({
        url: baseurl + "producto/s2ByDocente/" + this.value,
        dataType: "json",
        success: function (data) {
          $.each(data.items, function (key, value) {
            select_curso.append(
              "<option value=" + value.id + ">" + value.text + "</option>"
            );
          });
        },
      });
    }
  });

  select_curso.change(function () {
    if (this.value != "") {
      select_periodo
        .find("option")
        .remove()
        .end()
        .append("<option disabled selected> -- Periodo -- </option>");

      $.ajax({
        url: baseurl + "Periodo/getByCurso/" + this.value,
        dataType: "json",
        success: function (data) {
          $.each(data.data, function (key, value) {
            select_periodo.append(
              "<option value=" + value.id + ">" + value.label + "</option>"
            );
          });
        },
      });
    }
  });

  select_periodo.change(function () {
    if (this.value != "") {
      select_grupo
        .find("option")
        .remove()
        .end()
        .append("<option disabled selected> -- Grupo -- </option>");

      $.ajax({
        url: baseurl + "Grupos/getByPeriodo/" + this.value + "/" + docente_id,
        dataType: "json",
        success: function (data) {
          $.each(data.data, function (key, value) {
            select_grupo.append(
              "<option value=" + value.id + ">" + value.label + "</option>"
            );
          });
        },
      });
    }
  });

  select_grupo.change(function (e) {
    if (this.value != "") {
      changeGrupo(e);
    }
  });
});
